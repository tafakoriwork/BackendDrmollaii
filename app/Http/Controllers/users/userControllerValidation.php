<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class userControllerValidation extends Controller
{
    public static function preSignup()
    {
        $rules = [
            'phonenumber' => 'required|digits:11|numeric',
        ];
        return $rules;
    }
}
