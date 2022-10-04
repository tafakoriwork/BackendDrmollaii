<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model {
    protected $fillable = ['title', 'lesson_id'];

    public function flashcards()
    {
        return $this->hasMany(Flashcard::class, 'unit_id');
    }
    public function audio()
    {
        return $this->morphOne(Audio::class, 'audioable');
    }

    public function picture()
    {
        return $this->morphOne(Picture::class, 'picturable');
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }
}
