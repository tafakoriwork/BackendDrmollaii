<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['fname', 'lname', 'phonenumber', 'password', 'api_token', 'api_ip1', 'api_ip2', 'api_ip3', 'uniqid', 'is_admin', 'limit'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [];


    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function tests() {
        return $this->hasMany(Testresults::class, 'user_id');
    }
}
