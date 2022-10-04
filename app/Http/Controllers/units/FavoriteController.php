<?php

namespace App\Http\Controllers\units;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Flashcard;
use App\Models\User;

class FavoriteController extends Controller
{
    public function store(Request $request)
    {
          $api_token = $request->bearerToken();
        // 'api_ip' => 
        $user = User::where(['api_token' => $api_token])->first();
        if ($user)
            $newFavorite = Favorite::create([
                'user_id' => $user->id,
                'flashcard_id' => $request->id,
            ]);

        if ($newFavorite)
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function showall(Request $request)
    {
          $api_token = $request->bearerToken();
        // 'api_ip' => 
        $user = User::where(['api_token' => $api_token])->first();
        if ($user) {
            $favorites = Favorite::where('user_id', $user->id)
            ->with(['flashcard', 'flashcard.picture'])
            ->orderBy('id', 'DESC')
            ->skip($request->step)
            ->take(5)
            ->get()
            ->pluck('flashcard')
            ->all();
            
            return json_encode(['msg' => 'system_success', 'result' => $favorites]);
        } else return json_encode(['msg' => 'system_error']);
    }

    public function getIds(Request $request)
    {
          $api_token = $request->bearerToken();
        // 'api_ip' => 
        $user = User::where(['api_token' => $api_token])->first();
        if ($user) {
            $favorites = Favorite::where('user_id', $user->id)
            ->with(['flashcard', 'flashcard.picture'])
            ->orderBy('id', 'DESC')
            ->skip($request->step)
            ->take(5)
            ->get()
            ->pluck('flashcard')
            ->all();
            $arr = [];
            foreach ($favorites as $fav) {
                array_push($arr, $fav->id);
            }
            
            return json_encode(['msg' => 'system_success', 'result' => $arr]);
        } else return json_encode(['msg' => 'system_error']);
    }

    public function delete(Request $request)
    {
          $api_token = $request->bearerToken();
        // 'api_ip' => 
        $user = User::where(['api_token' => $api_token])->first();
        if ($user) {
            $rmfav = Favorite::where(['user_id' => $user->id, 'flashcard_id' => $request->id])->delete();
            if($rmfav)
            return json_encode(['msg' => 'system_success']);
        } else return json_encode(['msg' => 'system_error']);
    }

}
