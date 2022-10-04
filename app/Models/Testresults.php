<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testresults extends Model {
    protected $table = 'testresults';

    protected $fillable = ['user_id', 'result', 'test_id', 'rate'];

    public function testname() {
        return $this->belongsTo(Test::class, 'test_id');
    }

}
