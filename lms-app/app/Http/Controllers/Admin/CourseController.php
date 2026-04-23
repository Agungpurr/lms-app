<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with(['instructor', 'enrollments'])
            ->latest()
            ->paginate(15);

        return view('admin.courses.index', compact('courses'));
    }

    public function show(Course $course)
    {
        $course->load(['instructor', 'modules.lessons', 'enrollments.user']);

        $stats = [
            'total_students' => $course->enrollments->count(),
            'total_lessons'  => $course->modules->flatMap->lessons->count(),
            'avg_progress'   => $this->getAverageProgress($course),
        ];

        return view('admin.courses.show', compact('course', 'stats'));
    }

    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,published',
            'level'  => 'required|in:beginner,intermediate,advanced',
            'price'  => 'required|numeric|min:0',
        ]);

        $course->update($validated);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course berhasil diperbarui.');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course berhasil dihapus.');
    }

    private function getAverageProgress(Course $course): int
    {
        if ($course->enrollments->isEmpty()) return 0;

        $total = $course->enrollments->sum(fn($e) => $e->progress);

        return (int) round($total / $course->enrollments->count());
    }
}