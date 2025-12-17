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
    public function showSchool(Request $request, $id)
    {
        $school = CaoSchool::with(['courses' => function ($q) {
            $q->with(['fields', 'locations', 'level']);
        }])->findOrFail($id);

        if (view()->exists('pages.finder.school')) {
            return view('pages.finder.school', compact('school'));
        }

        return response()->json($school);
    }

    /**
     * Show single course with related school, fields and locations.
     */
    public function showCourse(Request $request, $id)
    {
        $course = CaoCourse::with(['school', 'fields', 'locations', 'level'])->findOrFail($id);

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

        if (view()->exists('pages.finder.course')) {
            return view('pages.finder.course', compact('course', 'relatedCourses'));
        }

        return response()->json(array_merge($course->toArray(), ['related' => $relatedCourses->toArray()]));
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
            $query->where('school_id', $request->input('school'));
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
            $query->where('school_id', $initialFilters['school']);
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
