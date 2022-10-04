<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model {
    protected $fillable = ['title', 'major_id', 'payment_id', 'price', 'desc'];
    
    public function units()
    {
        return $this->hasMany(Unit::class, 'lesson_id');
    }
    
    public function audio()
    {
        return $this->morphOne(Audio::class, 'audioable');
    }

    public function picture()
    {
        return $this->morphOne(Picture::class, 'picturable');
    }

    public function paid()
    {
        return $this->morphOne(Order::class, 'orderable');
    }

    public function paids()
    {
        return $this->morphMany(Order::class, 'orderable');
    }
}
