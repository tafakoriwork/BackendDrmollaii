<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Majormain extends Model
{
    protected $fillable = ['title'];
    
    public function majors()
    {
        return $this->hasMany(Major::class, 'majortype_id');
    }
}
