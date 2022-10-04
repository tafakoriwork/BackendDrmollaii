<?php

namespace App\Http\Controllers;

use App\Models\Invited;
use App\Models\Takhfif;
use App\Models\TGroup;
use App\Models\TGroupUser;
use App\Models\UsedTakhfif;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;

class TakhfifController extends Controller
{
    public function store(Request $request)
    {
        $ed = new DateTime($request->expire_date);
        $expire = $ed->format("Y-m-d");
        $newTakhfif = Takhfif::create([
            'percent' => $request->percent,
            'count' => $request->count,
            'code' => $request->code,
            'user_id' => $request->type == 1 ? $request->user_id : null,
            'group_id' => $request->type == 2 ? $request->group_id : null,
            'expire_date' => $expire,
        ]);

        if ($newTakhfif) {
            return json_encode(['msg' => 'system_success']);
        } else return json_encode(['msg' => 'system_error']);
    }

    public function checkCode(Request $request)
    {
        $api_token = $request->bearerToken();
        // 'api_ip' => 
        $user = User::where(['api_token' => $api_token])->first();
        $userGroups = TGroupUser::where('user_id', $user->id)->get()->pluck('tgroup_id')->toArray();
        $tget = Takhfif::where(['code' => $request->code])->first();
        $usedcount = 0;
        if($tget)
        {
            $usedcount = UsedTakhfif::where(['user_id' => $user->id, 'takhfif_id' => $tget->id])->count();
        }
        $code_exists = Takhfif::where(function($q) use ($usedcount, $request, $user) {
            return $q->where(['user_id' => $user->id, 'code' => $request->code])->where('count', '>', $usedcount)->whereDate( 'expire_date', '>=', \Carbon\Carbon::now() );
        })->orWhere(function ($q) use ($request, $usedcount) {
            return $q->whereNull('group_id')->where('user_id', null)->where('code', $request->code)->where('count', '>', $usedcount)->whereDate( 'expire_date', '>=', \Carbon\Carbon::now() );
        })->orWhere(function ($q) use ($userGroups, $request, $usedcount) {
            return $q->whereNotNull('group_id')->whereIn('group_id', $userGroups)->where('code', $request->code)->where('count', '>', $usedcount)->whereDate( 'expire_date', '>=', \Carbon\Carbon::now() );
        })->first();
        return $code_exists;
    }

    public function Groups()
    {
        return TGroup::with(['users', 'users.user'])->get();
    }

    public function DeleteGroup($id)
    {
        Takhfif::where('group_id', $id)->delete();
        return TGroup::destroy($id);
    }

    public function makeGroup(Request $request)
    {
        $users = $request->users;
        $title = $request->title;
        $tgroup = TGroup::create([
            'title' => $title,
        ]);

        foreach ($users as $key => $user) {
            TGroupUser::create([
                'user_id' => $user,
                'tgroup_id' => $tgroup->id,
            ]);
        }
        return json_encode(['msg' => 'system_success']);
    }

    public function delete($id)
    {
        if (Takhfif::destroy($id))
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function show($id)
    {
        $Takhfif = Takhfif::where('id', $id)->first();
        if ($Takhfif)
            return json_encode(['msg' => 'system_success', 'result' => $Takhfif]);
        else return json_encode(['msg' => 'system_error']);
    }

    public function showall()
    {
        $Takhfif = Takhfif::with(['user', 'group'])->get();
        if ($Takhfif)
            return json_encode(['msg' => 'system_success', 'result' => $Takhfif]);
        else return json_encode(['msg' => 'system_error']);
    }

    public function addInvited(Request $request)
    {
        $api_token = $request->bearerToken();
        // 'api_ip' => 
        try {
            $user = User::where(['api_token' => $api_token])->first();
            Invited::create([
                'user_id' => $user->id,
                'phonenumber' => $request->phonenumber,
            ]);
            return 1;
        } catch (\Throwable $th) {
            return 0;
            //throw $th;
        }
    }
}
