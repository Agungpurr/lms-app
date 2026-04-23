<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;

class InstructorDashboardController extends Controller
{
    public function index()
    {
        $instructor = auth()->user();

        $courses = Course::where('user_id', $instructor->id)
            ->withCount('enrollments')
            ->latest()
            ->get();

        $stats = [
            'total_courses'     => $courses->count(),
            'published'         => $courses->where('status', 'published')->count(),
            'total_students'    => Enrollment::whereIn('course_id', $courses->pluck('id'))->count(),
            'total_revenue'     => $this->calculateRevenue($courses),
        ];

        return view('instructor.dashboard', compact('stats', 'courses'));
    }

    private function calculateRevenue($courses): float
    {
        return $courses->sum(fn($c) => $c->price * $c->enrollments_count);
    }
}