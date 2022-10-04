<?php

namespace App\Http\Controllers\sms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class smsController extends Controller
{
    public function send($phonenumber, $uniqid)
    {
        $passManager = new passwordMakerController();
        return $passManager->passGenerator($phonenumber, $uniqid);
    }
}
