<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title', 'description', 'thumbnail',
        'category', 'level', 'price', 'status', 'user_id'
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function models()
    {
        return $this->hasMany(Module::class)->orderBy('order');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments');
    }

    // Hitung total lesson di course
    public function getTotalLessonsAttribute()
    {
        return $this->modules->sum(fn($m) => $m->lessons->count());
    }
}