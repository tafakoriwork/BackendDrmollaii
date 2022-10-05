<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = "notifications";
    protected $fillable = ['text'];
    
    public function audioable()
    {
        return $this->morphTo();
    }
}
