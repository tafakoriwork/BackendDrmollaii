<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Updating extends Model
{
    protected $table = 'updating';
   
    protected $fillable = ['url', 'title'];

}
