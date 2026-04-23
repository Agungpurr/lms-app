<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;

class ProgressController extends Controller
{
    public function markComplete(Lesson $lesson)
    {
        $user = auth()->user();

        LessonProgress::updateOrCreate(
            [
                'user_id'   => $user->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'completed'    => true,
                'completed_at' => now(),
            ]
        );

        $course   = $lesson->module->course;
        $progress = $user->progressIn($course);

        if ($progress >= 100) {
            Enrollment::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->update(['status' => 'completed']);
        }

        return back()->with('success', '✅ Lesson ditandai selesai!');
    }

    public function markIncomplete(Lesson $lesson)
    {
        LessonProgress::where('user_id', auth()->id())
            ->where('lesson_id', $lesson->id)
            ->update(['completed' => false, 'completed_at' => null]);

        return back()->with('info', 'Lesson ditandai belum selesai.');
    }
}