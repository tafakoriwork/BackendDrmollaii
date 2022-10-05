<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TGroup extends Model
{
    protected $table = 'tgroup';

    protected $fillable = ['title'];

    public function users()
    {
        return $this->hasMany(TGroupUser::class, 'tgroup_id');
    }
}
