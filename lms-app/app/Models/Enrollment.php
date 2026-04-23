<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $fillable = ['user_id', 'course_id', 'status', 'enrolled_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class); // ✅ fix: Course bukan User
    }

    public function getProgressAttribute(): int
    {
        $course = $this->course->load('modules.lessons');
        $totalLessons = $course->modules->flatMap->lessons->count();

        if ($totalLessons === 0) return 0;

        $completed = LessonProgress::where('user_id', $this->user_id)
            ->whereIn('lesson_id', $course->modules->flatMap->lessons->pluck('id')) // ✅ fix typo
            ->where('completed', true)
            ->count();

        return (int) round(($completed / $totalLessons) * 100);
    }
}