<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDF extends Model {
    protected $fillable = ['url', 'title', 'category_id', 'price', 'payment_id', 'desc'];
    protected $table = "pdfs";
    
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
