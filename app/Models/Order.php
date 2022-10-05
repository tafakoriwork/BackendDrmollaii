<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    protected $fillable = ['orderable_id', 'orderable_type', 'user_id', 'ref_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orderable() {
        return $this->morphTo();
    }
}
