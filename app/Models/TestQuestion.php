<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestQuestion extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['text', 'correct_answer', 'test_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
    ];

    public function answers() {
        return $this->hasMany(TestAnswer::class, 'question_id');
    }

    public function correct() {
        return $this->belongsTo(TestAnswer::class, 'correct_answer');
    }

    public function picture()
    {
        return $this->morphOne(Picture::class, 'picturable');
    }
}
