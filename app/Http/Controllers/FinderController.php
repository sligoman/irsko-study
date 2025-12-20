<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Sligoman\Caofinder\Models\CaoField;
use Sligoman\Caofinder\Models\CaoLevel;
use Sligoman\Caofinder\Models\CaoCourse;
use Sligoman\Caofinder\Models\CaoSchool;

class FinderController extends Controller
{
    /**
     * List all schools with course counts.
     */
    public function schools(Request $request)
    {
        $schools = CaoSchool::withCount('courses')->orderBy('name')->get();

        // If blade view exists, return HTML page; otherwise return JSON (API-friendly fallback)
        if (view()->exists('pages.finder.schools')) {
            return view('pages.finder.schools', compact('schools'));
        }

        return response()->json($schools);
    }

    /**
     * Show single school and its courses.
     */
    public function showSchool(Request $request, $url)
    {
        // Accept either numeric id or school_id (slug) in the {url} parameter

        // Load school locations and courses (courses also load their locations)
        $query = CaoSchool::with([
            'locations',
            'courses' => function ($q) {
                $q->with(['fields', 'locations', 'level']);
            }
        ]);

        // Prefer explicit `url` column on schools. If not found, fall back to numeric id,
        // then to the legacy `school_id` slug.
        $school = null;
        if (!is_numeric($url)) {
            $school = $query->where('url', $url)->first();
        }

        if (!$school && is_numeric($url)) {
            $school = $query->find($url);
        }

        if (!$school) {
            $school = $query->where('school_id', $url)->first();
        }

        if (!$school) {
            abort(404);
        }

        // Build explicit canonical for school pages (prefer `url` column, then `school_id`)
        if (!empty($school->url)) {
            $schoolCanonical = url('/vysoke-skoly/' . $school->url);
        } elseif (!empty($school->school_id)) {
            $schoolCanonical = url('/vysoke-skoly/' . $school->school_id);
        } else {
            $schoolCanonical = url('/vysoke-skoly/' . $school->id);
        }

        if (view()->exists('pages.finder.school')) {
            return view('pages.finder.school', compact('school'))->with('canonical', $schoolCanonical);
        }

        return response()->json(array_merge($school->toArray(), ['canonical' => $schoolCanonical]));
    }

    /**
     * Show single course with related school, fields and locations.
     */
    public function showCourse(Request $request, $url)
    {
        // Resolve course by flexible URL slug or numeric id.
        // Primary lookup uses the `url` column (string slug). If not found, fall back to
        // numeric id, then school-code + course code, then plain code.
        $course = null;

        // Try direct url column match first for string values
        if (!is_numeric($url)) {
            $course = CaoCourse::with(['school', 'fields', 'locations', 'level'])
                ->where('url', $url)
                ->first();
        }

        // If not found yet, and parameter is numeric, try find by id
        if (!$course && is_numeric($url)) {
            $course = CaoCourse::with(['school', 'fields', 'locations', 'level'])->find($url);
        }

        // If still not found, try slug pattern school-school_id + '-' + code
        if (!$course && is_string($url) && str_contains($url, '-')) {
            [$schoolCode, $courseCode] = explode('-', $url, 2);
            $school = CaoSchool::where('school_id', $schoolCode)->first();
            if ($school) {
                $course = CaoCourse::with(['school', 'fields', 'locations', 'level'])
                    ->where('school_id', $school->id)
                    ->where('code', $courseCode)
                    ->first();
            }
        }

        // Fallback: try to find by code column
        if (!$course) {
            $course = CaoCourse::with(['school', 'fields', 'locations', 'level'])
                ->where('code', $url)
                ->first();
        }

        if (!$course) {
            abort(404);
        }

        // Related courses: other courses that share any field with the current course
        $fieldIds = $course->fields->pluck('id')->toArray();
        $relatedCourses = collect();
        if (!empty($fieldIds)) {
            $relatedCourses = CaoCourse::with('school')
                ->whereHas('fields', function ($q) use ($fieldIds) {
                    $q->whereIn('field_id', $fieldIds);
                })
                ->where('id', '!=', $course->id)
                ->inRandomOrder()
                ->distinct()
                ->limit(6)
                ->get();
        }

            // Ensure related courses include `url` attribute
            $relatedCourses = $relatedCourses->map(function ($c) {
                if (empty($c->url)) {
                    if (!empty($c->school) && !empty($c->school->school_id) && !empty($c->code)) {
                        $c->url = strtolower($c->school->school_id . '-' . $c->code);
                    } else {
                        $c->url = $c->code ?? null;
                    }
                }
                return $c;
            });

        // Build explicit canonical for the course page (prefer DB `url` column)
        if (!empty($course->url)) {
            $courseCanonical = url('/kurzy/' . $course->url);
        } else {
            if (!empty($course->school) && !empty($course->school->school_id) && !empty($course->code)) {
                $courseCanonical = url('/kurzy/' . strtolower($course->school->school_id . '-' . $course->code));
            } elseif (!empty($course->code)) {
                $courseCanonical = url('/kurzy/' . $course->code);
            } else {
                $courseCanonical = url('/kurzy/' . $course->id);
            }
        }

        if (view()->exists('pages.finder.course')) {
            return view('pages.finder.course', compact('course', 'relatedCourses'))->with('canonical', $courseCanonical);
        }

        return response()->json(array_merge($course->toArray(), ['related' => $relatedCourses->toArray(), 'canonical' => $courseCanonical]));
    }

    /**
     * Basic search / filter for courses.
     * Supported query params: q (text), school (id), field (id), level (id)
     */
    public function search(Request $request)
    {

        $query = CaoCourse::with(['school', 'fields', 'locations', 'level']);

        if ($request->filled('q')) {
            $term = $request->input('q');
            $query->where(function ($q) use ($term) {
                // search both English and Czech titles and descriptions
                $q->where('title_en', 'like', "%{$term}%")
                  ->orWhere('title_cs', 'like', "%{$term}%")
                  ->orWhere('description_en', 'like', "%{$term}%")
                  ->orWhere('description_cs', 'like', "%{$term}%");
            });
        }

        if ($request->filled('school')) {
            $schoolParam = $request->input('school');
            if (is_numeric($schoolParam)) {
                $query->where('school_id', $schoolParam);
            } else {
                $school = CaoSchool::where('school_id', $schoolParam)->first();
                if ($school) {
                    $query->where('school_id', $school->id);
                } else {
                    // no matching school slug: force empty result
                    $query->whereRaw('0=1');
                }
            }
        }

        if ($request->filled('field')) {
            $fieldId = $request->input('field');
            $query->whereHas('fields', function ($q) use ($fieldId) {
                $q->where('field_id', $fieldId);
            });
        }

        if ($request->filled('level')) {
            $query->where('level_id', $request->input('level'));
        }
        

        $results = $query->paginate(20);

            // Ensure initial paginated items include a `url` property
            $results->getCollection()->transform(function ($course) {
                if (empty($course->url)) {
                    if (!empty($course->school) && !empty($course->school->school_id) && !empty($course->code)) {
                        $course->url = strtolower($course->school->school_id . '-' . $course->code);
                    } else {
                        $course->url = $course->code ?? null;
                    }
                }
                return $course;
            });



        if (view()->exists('pages.finder.search')) {
            return view('pages.finder.search', compact('results'));
        }

        return response()->json($results);
    }

    /**
     * Render courses listing page with initial filter data.
     */
    public function courses(Request $request)
    {
        $schools = CaoSchool::orderBy('name')->get();
        $fields = CaoField::orderBy('name')->get();
        $levels = CaoLevel::orderBy('name')->get();

        // Build base query
        $query = CaoCourse::with(['school', 'fields', 'locations', 'level']);

        // Collect initial filters from request so view/component can preselect
        $initialFilters = [
            'q' => $request->query('q'),
            'school' => $request->query('school'),
            'field' => $request->query('field'),
            'level' => $request->query('level'),
            'page' => $request->query('page', 1),
        ];

        // Apply filters server-side when provided to produce initial results
        if (!empty($initialFilters['q'])) {
            $term = $initialFilters['q'];
            $query->where(function ($q) use ($term) {
                $q->where('title_en', 'like', "%{$term}%")
                  ->orWhere('title_cs', 'like', "%{$term}%")
                  ->orWhere('description_en', 'like', "%{$term}%")
                  ->orWhere('description_cs', 'like', "%{$term}%");
            });
        }

        if (!empty($initialFilters['school'])) {
            $schoolParam = $initialFilters['school'];
            if (is_numeric($schoolParam)) {
                $query->where('school_id', $schoolParam);
            } else {
                $school = CaoSchool::where('school_id', $schoolParam)->first();
                if ($school) {
                    $query->where('school_id', $school->id);
                } else {
                    $query->whereRaw('0=1');
                }
            }
        }

        if (!empty($initialFilters['field'])) {
            $fieldId = $initialFilters['field'];
            $query->whereHas('fields', function ($q) use ($fieldId) {
                $q->where('field_id', $fieldId);
            });
        }

        if (!empty($initialFilters['level'])) {
            $query->where('level_id', $initialFilters['level']);
        }

        $results = $query->paginate(12, ['*'], 'page', $initialFilters['page']);

        // Ensure initial paginated items include a `url` property
        $results->getCollection()->transform(function ($course) {
            if (empty($course->url)) {
                if (!empty($course->school) && !empty($course->school->school_id) && !empty($course->code)) {
                    $course->url = strtolower($course->school->school_id . '-' . $course->code);
                } else {
                    $course->url = $course->code ?? null;
                }
            }
            return $course;
        });

        $initialData = [
            'schools' => $schools,
            'fields' => $fields,
            'levels' => $levels,
            'results' => $results,
            'filters' => $initialFilters,
        ];

        return view('pages.finder.courses', compact('initialData'));
    }
    /**
     * List universities from the database (cao_schools) with course counts.
     */
    public function universities()
    {
        $schools = CaoSchool::withCount('courses')->orderBy('name')->get();

        return view('pages.universities', compact('schools'));
    }
}
