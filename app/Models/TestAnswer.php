<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestAnswer extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['text', 'question_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
    ];
}
