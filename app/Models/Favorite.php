<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model {
    protected $fillable = ['user_id', 'flashcard_id',];

    public function flashcard() {
        return $this->belongsTo(Flashcard::class, 'flashcard_id');
    }
}
