<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'       => User::count(),
            'total_students'    => User::role('student')->count(),
            'total_instructors' => User::role('instructor')->count(),
            'total_courses'     => Course::count(),
            'published_courses' => Course::where('status', 'published')->count(),
            'total_enrollments' => Enrollment::count(),
        ];

        $latestUsers   = User::latest()->take(5)->get();
        $latestCourses = Course::with('instructor')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'latestUsers', 'latestCourses'));
    }
}