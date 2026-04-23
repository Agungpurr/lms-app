<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi sebagai Instructor 
    public function courses()
    {
        return $this->hasMany(Course::class, 'user_id');
    }

    // Relasi sebagai student
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'enrollments')
                    ->withPivot('status', 'enrolled_at')
                    ->withTimestamps();
    }

    public function quizResults()
    {
        return $this->hasMany(QuizResult::class); // Perbaiki: QuizResult bukan Quizresult
    }

    // Relasi untuk lesson progress
    public function lessonProgress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    // Helper method
    // Cek apakah student sudah enroll di course tertentu
    public function isEnrolledIn(Course $course): bool
    {
        return $this->enrollments()
                    ->where('course_id', $course->id)
                    ->exists();
    }

    // Ambil progress (%) di course tertentu
    public function progressIn(Course $course): int
    {
        $lessonIds = $course->modules
                            ->flatMap(function($module) {
                                return $module->lessons;
                            })
                            ->pluck('id');
        
        $total = $lessonIds->count();
        if ($total === 0) return 0;
        
        $completed = $this->lessonProgress()
                        ->whereIn('lesson_id', $lessonIds)
                        ->where('completed', true)
                        ->count();
        
        return (int) round(($completed / $total) * 100);
    }
}