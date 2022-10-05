<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotseenTK extends Model {
    protected $table = 'notseentk';
    protected $fillable = ['user_id', 'ticket_id'];

}
