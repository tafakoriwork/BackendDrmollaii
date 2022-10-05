<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use App\Http\Controllers\sms\smsController;
use App\Models\Invited;
use App\Models\Notification;
use App\Models\Takhfif;
use App\Models\Testresults;
use App\Models\Ticket;
use App\Models\Ticketmessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\TestResult;

class userController extends Controller
{
    public function index(Request $request)
    {
        return User::get();
    }

    public function show($id)
    {
        return User::find($id);
    }


    //sign up just with phone number
    public function preSignup(Request $request)
    {
        $this->validate($request, userControllerValidation::preSignup(), userControllerValidationsErrors::preSignup());
        $phonenumber = $request->phonenumber;
        $uniqid = $request->uniqid;
        $accept = $request->accept;
        $user = User::where('phonenumber', $phonenumber)->first();
        if ($user && $user->limit == -1)
            return json_encode(['msg' => 'limit']);
        if ($user && !$user->is_admin)
            if (empty($accept) && isset($user->uniqid) && $user->uniqid != $uniqid)
                return 'exists';

        $smsManager = new smsController();
        $response = $smsManager->send($phonenumber, $uniqid);
        if ($response->result->code == 200)
            return json_encode(['msg' => 'system_success', 'phonenumber' => $phonenumber]);
        else return json_encode(['msg' => 'system_error']);
    }

    public function checkOTP(Request $request)
    {
        $code = $request->code;
        $phonenumber = $request->phonenumber;
        $uniqid = $request->uniqid;
        $user = User::where(['phonenumber' => $phonenumber])->first();
        $hashCheck = Hash::check($code, $user->password);
        if ($hashCheck == false)
            return json_encode(['msg' => 'wrong_code']);
        else
        if ($user &&  isset($user->uniqid) && $user->uniqid != $uniqid) {
            $user->limit = -1;
        }
        if ($hashCheck && (isset($user->uniqid) ? $user->uniqid == $uniqid || $user->is_admin || $user->limit = -1 : true)) {
         
            if ($user->limit == 600)
                $user->limit = 0;
            $user->uniqid = $uniqid;
            $user->api_token = bin2hex(random_bytes(32));
            $user->save();
            return json_encode(['msg' => 'system_success', 'api_token' => $user->api_token, 'is_admin' => $user->is_admin, 'phonenumber' => $user->phonenumber, 'fname' => $user->fname]);
        } else return json_encode(['msg' => 'system_error']);
    }

    // complete sign up with add first name and last name
    public function  completeSignup(Request $request)
    {
        $phonenumber = $request->phonenumber;
        $fname = $request->fname;
        $lname = $request->lname;
        $user = User::where('phonenumber', $phonenumber)->first();
        if (empty($user))
            return json_encode(['msg' => 'system_error']);
        else {
            $user->fname = $fname;
            $user->lname = $lname;
            if ($user->save())
                return json_encode(['msg' => 'system_success']);
        }
    }

    public function changeadmin(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        $change = User::where('id', $request->id)->update(['is_admin' => $user->is_admin == 1 ? 0 : 1]);
        $user = User::where('id', $request->id)->first();
        if ($user->is_admin) {
            $user->limit = -100000000000;
            $user->save();
        } else {
            $user->limit = 0;
            $user->save();
        }
        if ($change)
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function reset(Request $request)
    {
        $user = User::where('id', $request->id)->first();

        $user->limit = 0;
        if ($user->save())
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function findPosition($user_id, $test_id) {

        $tests = Testresults::where('test_id', $test_id)->orderBy('rate', 'DESC')->get();
        $rotbeh = json_encode(['position' => 1, 'from' => 1]);
        $counter = 0;
        foreach($tests as $i => $test) {
            $counter = count($tests);
            if($test->user_id == $user_id)
            {
                $pos = $i + 1;
                $rotbeh = "$pos از $counter";
            }
        }
        return $rotbeh;
    }

    public function findPositionAXIOS(Request $request) {
        $api_token = $request->bearerToken();
        $user = User::where(['api_token' => $api_token])->first();
        $tests = Testresults::where('test_id', $request->test_id)->orderBy('rate', 'DESC')->get();
        $rotbeh = "1 از 1";
        $counter = 0;
        foreach($tests as $i => $test) {
            $counter = count($tests);
            if($test->user_id == $user->id)
            {
                $pos = $i + 1;
                $rotbeh = "$pos از $counter";
            }
        }
        return $rotbeh;
    }

    public function usersInfo($id) {
        $user = User::where('id', $id)->with(['orders' => function($q) {
            $q->orderBy('created_at', 'DESC');
        }, 'orders.orderable', 'tests' => function($q) {
            $q->orderBy('created_at', 'DESC');
        }, 'tests.testname'])->first();

        foreach ($user->tests as $key => $test) {
            $testArray = $test;
        $testArray['position'] = $this->findPosition($id, $test->test_id);
           $user->tests = $testArray;
        }
        return $user;
    }

}
