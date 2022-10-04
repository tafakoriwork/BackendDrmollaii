<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audio extends Model
{
    protected $table = "audios";
    protected $fillable = ['url', 'audioable_type', 'audioable_id'];
    
    public function audioable()
    {
        return $this->morphTo();
    }
}
