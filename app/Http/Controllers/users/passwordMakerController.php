<?php

namespace App\Http\Controllers\sms;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use \Ghasedak\Laravel\GhasedakFacade;
class passwordMakerController extends Controller
{
    public function passGenerator($receptor)
    {
        $pass = rand(1000, 9999);
        $save_result = $this->savePass($pass, $receptor);
        if ($save_result) {
            $response = GhasedakFacade::Verify(
                $receptor,
                1,
                "drmolaei",
                $pass,
            );
        }
        return $response;
    }

    public function savePass($pass, $phonenumber)
    {
        
        $hashedPass = Hash::make($pass);
        $user = User::updateOrCreate(['phonenumber' => $phonenumber],[
            'phonenumber' => $phonenumber,
            'password' => $hashedPass,
            'api_token' => bin2hex(random_bytes(32)),
            'api_ip' => $_SERVER['REMOTE_ADDR'],
        ]);
        return $user;
    }
}
