<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class CoursePublicController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with(['instructor', 'enrollments'])
            ->withCount(['modules as modules_count', 'enrollments as enrollments_count']) // ✅ fix
            ->where('status', 'published');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $courses = $query->latest()->paginate(12);

        $categories = Course::where('status', 'published')
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->toArray();

        if (Auth::check()) {
            $courses->each(function($course) {
                $course->user_enrolled = $course->enrollments
                    ->where('user_id', Auth::id())
                    ->isNotEmpty();
            });
        }

        return view('courses.index', compact('courses', 'categories'));
    }

    public function show($id)
    {
        $course = Course::with(['instructor', 'modules.lessons', 'enrollments']) // ✅ fix
            ->withCount(['modules as modules_count', 'enrollments as enrollments_count']) // ✅ fix
            ->findOrFail($id);

        $isEnrolled = false;
        if (Auth::check()) {
            $isEnrolled = $course->enrollments
                ->where('user_id', Auth::id())
                ->isNotEmpty();
        }

        return view('courses.show', compact('course', 'isEnrolled'));
    }
}