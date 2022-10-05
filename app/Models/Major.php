<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Major extends Model {
    protected $fillable = ['title', 'majormain_id'];

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'major_id');
    }

    public function picture()
    {
        return $this->morphOne(Picture::class, 'picturable');
    }
}
