<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Takhfif extends Model
{
    protected $table = "takhfifs";
    protected $fillable = ['code', 'expire_date', 'user_id', 'group_id', 'percent', 'count'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function group() {
        return $this->belongsTo(TGroup::class, 'group_id');
    }

}
