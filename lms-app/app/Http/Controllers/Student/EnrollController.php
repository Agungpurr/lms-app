<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;

class EnrollController extends Controller
{
    public function enroll(Course $course)
    {
        $user = auth()->user();

        abort_if($course->status !== 'published', 404);

        if ($user->isEnrolledIn($course)) {
            return redirect()->route('student.courses.show', $course)
                ->with('info', 'Kamu sudah terdaftar di course ini.');
        }

        Enrollment::create([
            'user_id'     => $user->id,
            'course_id'   => $course->id,
            'enrolled_at' => now(),
            'status'      => 'active',
        ]);

        return redirect()->route('student.courses.show', $course)
            ->with('success', '🎉 Berhasil join course! Selamat belajar.');
    }

    public function show(Course $course)
    {
        $user = auth()->user();

        abort_if(! $user->isEnrolledIn($course), 403, 'Kamu belum enroll di course ini.');

        $course->load(['modules.lessons', 'instructor']);

        $completedLessonIds = $user->lessonProgress()
            ->whereIn('lesson_id', $course->modules->flatMap->lessons->pluck('id'))
            ->where('completed', true)
            ->pluck('lesson_id')
            ->toArray();

        $progress = $user->progressIn($course);

        return view('student.courses.show', compact('course', 'completedLessonIds', 'progress'));
    }

    public function lesson(Course $course, Lesson $lesson)
    {
        $user = auth()->user();

        abort_if(! $user->isEnrolledIn($course), 403);

        $lesson->load('module');

        $isCompleted = $lesson->isCompletedBy($user);

        $allLessons   = $course->modules->flatMap->lessons;
        $currentIndex = $allLessons->search(fn($l) => $l->id === $lesson->id);
        $prevLesson   = $allLessons[$currentIndex - 1] ?? null;
        $nextLesson   = $allLessons[$currentIndex + 1] ?? null;

        return view('student.course.lesson', compact(
            'course', 'lesson', 'isCompleted', 'prevLesson', 'nextLesson'
        ));
    }
}