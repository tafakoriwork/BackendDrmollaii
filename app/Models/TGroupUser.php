<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TGroupUser extends Model
{
    protected $table = 'tgroupusers';
  
    protected $fillable = ['user_id', 'tgroup_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
