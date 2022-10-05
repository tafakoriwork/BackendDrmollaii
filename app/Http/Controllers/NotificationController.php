<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Notseen;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function store(Request $request)
    {
        $newNotification = Notification::updateOrCreate([
            'text' => $request->text
        ]);

        if ($newNotification) {
            return json_encode(['msg' => 'system_success']);
        } else return json_encode(['msg' => 'system_error']);
    }

    public function delete($id)
    {
        if (Notification::destroy($id))
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function show($id)
    {
        $Notification = Notification::where('id', $id)->first();
        if ($Notification)
            return json_encode(['msg' => 'system_success', 'result' => $Notification]);
        else return json_encode(['msg' => 'system_error']);
    }

    public function update(Request $request)
    {
        $Notification = Notification::where('id', $request->id)->update([
            'text' => $request->text
        ]);
        if ($Notification)
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function showall(Request $request)
    {
        $api_token = $request->bearerToken();
       $user = User::where(['api_token' => $api_token])->first();
        $NotificationsId = Notification::orderBy('id', 'DESC')->get()->pluck('id')->toArray();
        foreach ($NotificationsId as $key => $value) {
            Notseen::firstOrCreate(
                [
                    'user_id' =>  $user->id,
                    'notif_id' => $value,
                ]
            );
        }

        $Notification = Notification::orderBy('id', 'DESC')->get();
        if ($Notification)
            return json_encode(['msg' => 'system_success', 'result' => $Notification]);
        else return json_encode(['msg' => 'system_error']);
    }

    public function notseen(Request $request)
    {
        $api_token = $request->bearerToken();
       $user = User::where(['api_token' => $api_token])->first();
        $seens = Notseen::where('user_id', $user->id)->get()->pluck('notif_id')->toArray();
        $Notification = Notification::whereNotIn('id', $seens)->orderBy('id', 'DESC')->count();
        if ($Notification)
            return json_encode(['msg' => 'system_success', 'result' => $Notification]);
        else return json_encode(['msg' => 'system_error']);
    }
}
