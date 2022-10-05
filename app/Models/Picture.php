<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Picture extends Model {
    protected $fillable = ['url', 'picturable_type', 'picturable_id'];

    public function picturable()
    {
        return $this->morphTo();
    }
}
