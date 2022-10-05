<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashcardHistory extends Model
{
    protected $table = "flashcardhistory";

    protected $fillable = ['note', 'flashcard_id', 'user_id', 'history'];
  
}
