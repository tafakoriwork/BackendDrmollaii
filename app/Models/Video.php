<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model {
    protected $fillable = ['url', 'title', 'category_id', 'price', 'type', 'payment_id', 'desc'];
    
    public function paid()
    {
        return $this->morphOne(Order::class, 'orderable');
    }

    public function paids()
    {
        return $this->morphMany(Order::class, 'orderable');
    }
    
    public function picture()
    {
        return $this->morphOne(Picture::class, 'picturable');
    }
    
}
