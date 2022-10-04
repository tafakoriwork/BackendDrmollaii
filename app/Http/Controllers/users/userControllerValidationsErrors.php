<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class userControllerValidationsErrors extends Controller
{
    public static function preSignup() {
        $errors = [
            'phonenumber.required' => 'لطفا شماره همراه را وارد کنید',
            'phonenumber.digits' => 'شماره همراه باید دقیقا ۱۱ رقم باشد',
            'phonenumber.numeric' => 'شماره همراه باید عددی باشد',
        ];
        return $errors;
    }
}
