<?php

namespace App\Http\Controllers\sms;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use \Ghasedak\Laravel\GhasedakFacade;
class passwordMakerController extends Controller
{
    public function passGenerator($receptor, $uniqid)
    {
        $pass = rand(1000, 9999);
        $save_result = $this->savePass($pass, $receptor, $uniqid);
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

    public function savePass($pass, $phonenumber, $uniqid)
    {
        $hashedPass = Hash::make($pass);
        $usercheck = User::where(['phonenumber' => $phonenumber])->first();
        $user = User::updateOrCreate(['phonenumber' => $phonenumber], [
            'phonenumber' => $phonenumber,
            'password' => $hashedPass,
        ]);
        if(empty($usercheck))
        {
            $user->limit = 600;
            $user->save();
        }
        // switch (true) {
        //     case empty($user->api_ip1) || $user->api_ip1 == $_SERVER['REMOTE_ADDR']:
        //         $user->api_ip1 = $_SERVER['REMOTE_ADDR'];
        //         $user->save();
        //         break;
        //     case empty($user->api_ip2) || $user->api_ip2 == $_SERVER['REMOTE_ADDR']:
        //         $user->api_ip2 = $_SERVER['REMOTE_ADDR'];
        //         $user->save();
        //         break;
        //     case empty($user->api_ip3) || $user->api_ip3 == $_SERVER['REMOTE_ADDR']:
        //         $user->api_ip3 = $_SERVER['REMOTE_ADDR'];
        //         $user->save();
        //         break;
        // }
        
        return $user;
    }
}
