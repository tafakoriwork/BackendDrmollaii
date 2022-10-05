<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FreeFlashcard extends Model
{
    protected $table = 'freeflashcardscategory';
    protected $fillable = ['title', 'user_id'];
}
