<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\QuizResult;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $enrollments = Enrollment::where('user_id', $user->id)
            ->with(['course.modules.lessons'])
            ->latest()
            ->get();

        $stats = [
            'active_courses'    => $enrollments->where('status', 'active')->count(),
            'completed_courses' => $enrollments->where('status', 'completed')->count(),
            'certificates'      => $enrollments->where('status', 'completed')->count(),
            'quiz_passed'       => QuizResult::where('user_id', $user->id)
                                             ->where('passed', true)->count(),
        ];

        return view('student.dashboard', compact('stats', 'enrollments', 'user'));
    }
}