<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notseen extends Model {
    protected $table = 'notseenpm';
    protected $fillable = ['user_id', 'notif_id'];

}
