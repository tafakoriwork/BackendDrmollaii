<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['name', 'category_id', 'time', 'price','payment_id', 'url'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
    ];

    public function questions() {
        return $this->hasMany(TestQuestion::class, 'test_id');
    }

    public function answers() {
        return $this->hasMany(TestAnswer::class, 'test_id');
    }

    public function resulted() {
        return $this->hasOne(Testresults::class, 'test_id');
    }
    

    public function paid()
    {
        return $this->morphOne(Order::class, 'orderable');
    }

    public function paids()
    {
        return $this->morphMany(Order::class, 'orderable');
    }
}
