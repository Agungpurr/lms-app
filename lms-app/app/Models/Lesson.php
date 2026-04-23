<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'module_id', 'title', 'content',
        'video_url', 'file', 'type', 'order'
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function progress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function isCompletedBy(User $user): bool
    {
        return $this->progress()
                    ->where('user_id', $user->id)
                    ->exists(); // ✅ fix: existss() → exists()
    }
}