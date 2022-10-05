<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestCategory extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['name'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
    ];

    public function tests() {
        return $this->hasMany(Test::class, 'category_id');
    }
}
