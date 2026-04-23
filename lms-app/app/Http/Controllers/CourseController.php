<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CoursePublicController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::with('instructor')
            ->where('status', 'published')
            ->when($request->search, fn ($q) =>
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%")
            )
            ->when($request->category, fn ($q) =>
                $q->where('category', $request->category)
            )
            ->when($request->level, fn ($q) =>
                $q->where('level', $request->level)
            )
            ->withCount('enrollments')
            ->latest()
            ->paginate(12)
            ->withQueryString(); // pertahankan filter saat pindah halaman

        $categories = Course::where('status', 'published')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort()
            ->values();

        return view('courses.index', compact('courses', 'categories'));
    }

    public function show(Course $course)
    {
        abort_if($course->status !== 'published', 404);

        $course->load(['instructor', 'modules.lessons']);

        $isEnrolled  = auth()->check() && auth()->user()->isEnrolledIn($course);
        $totalLesson = $course->modules->flatMap->lessons->count();

        return view('courses.show', compact('course', 'isEnrolled', 'totalLesson'));
    }
}