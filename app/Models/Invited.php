<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invited extends Model
{
    protected $table = 'inviteds';
   
    protected $fillable = ['user_id', 'phonenumber'];

}
