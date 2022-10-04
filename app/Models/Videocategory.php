<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Videocategory extends Model {
    protected $fillable = ['title'];

    public function videos()
    {
        return $this->hasMany(Video::class, 'category_id');
    }
}
