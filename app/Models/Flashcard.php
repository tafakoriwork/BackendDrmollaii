<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flashcard extends Model
{
    protected $fillable = ['front', 'back', 'unit_id', 'order', 'user_id', 'freeflashcardscategory_id'];
    
    public function audio()
    {
        return $this->morphOne(Audio::class, 'audioable');
    }

    public function picture()
    {
        return $this->morphOne(Picture::class, 'picturable');
    }

    public function unit() {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function history() {
        return $this->hasOne(FlashcardHistory::class, 'flashcard_id');
    }

    public function category() {
        return $this->belongsTo(FreeFlashcard::class, 'freeflashcardscategory_id');
    }

    public function fav() {
        return $this->hasOne(Favorite::class, 'flashcard_id');
    }
}
