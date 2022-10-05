<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsedTakhfif extends Model
{
    protected $table = 'usedtakhfif';
   
    protected $fillable = ['user_id', 'takhfif_id'];

}
