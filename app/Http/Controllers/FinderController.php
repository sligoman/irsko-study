<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sligoman\Caofinder\Models\CaoSchool;
use Sligoman\Caofinder\Models\CaoCourse;
use Sligoman\Caofinder\Models\CaoField;
use Sligoman\Caofinder\Models\CaoLevel;

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

        if (view()->exists('pages.finder.course')) {
            return view('pages.finder.course', compact('course'));
        }

        return response()->json($course);
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

        // initial paginated results (no filters)
        $results = CaoCourse::with(['school', 'fields', 'locations', 'level'])->paginate(12);

        $initialData = [
            'schools' => $schools,
            'fields' => $fields,
            'levels' => $levels,
            'results' => $results,
        ];

        return view('pages.finder.courses', compact('initialData'));
    }
}
