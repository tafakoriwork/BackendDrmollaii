<?php

namespace App\Http\Controllers\units;

use App\Http\Controllers\Controller;
use App\Models\Flashcard;
use App\Models\FlashcardHistory;
use App\Models\LeitnerBox;
use App\Models\User;
use Illuminate\Http\Request;

class LeitnerController extends Controller
{
    public function moveToBox(Request $request)
    {
          $api_token = $request->bearerToken();
        // 'api_ip' => 
        $user = User::where(['api_token' => $api_token])->first();
        $flashcard_id = $request->id;
        if ($user) {
            $inBox = LeitnerBox::where(['user_id' => $user->id, 'flashcard_id' => $flashcard_id])->first();
            if (!$inBox) {
                LeitnerBox::create(
                    [
                        'user_id' => $user->id,
                        'flashcard_id' => $flashcard_id,
                        'level' => 1,
                    ]
                );
            }
            return json_encode(['msg' => 'system_success', $flashcard_id]);
        } else return json_encode(['msg' => 'system_error']);
    }

    public function removeCard(Request $request, $id)
    {
          $api_token = $request->bearerToken();
        // 'api_ip' => 
        $user = User::where(['api_token' => $api_token])->first();
        if ($user) {
            LeitnerBox::where(['user_id' => $user->id, 'flashcard_id' => $id])->delete();
            return json_encode(['msg' => 'system_success']);
        } else return json_encode(['msg' => 'system_error']);
    }

    public function getBox(Request $request)
    {
          $api_token = $request->bearerToken();
        // 'api_ip' => 
        $user = User::where(['api_token' => $api_token])->first();
        if ($user) {
            $box = LeitnerBox::where(['user_id' => $user->id])->with(['flashcard'])->get();
            return json_encode(['msg' => 'system_success', 'result' => $box]);
        } else return json_encode(['msg' => 'system_error']);
    }

    public function getUserFlashcards(Request $request)
    {
          $api_token = $request->bearerToken();
        // 'api_ip' => 
        $user = User::where(['api_token' => $api_token])->first();
        if ($user) {
            $box = LeitnerBox::where(['user_id' => $user->id])->where('level', '<>', 17)->get()->pluck('id')->toArray();
            return json_encode(['msg' => 'system_success', 'result' => $box]);
        } else return json_encode(['msg' => 'system_error']);
    }

    public function getUserFlashcardsbylevel(Request $request, $id)
    {
        $box = null;
          $api_token = $request->bearerToken();
        // 'api_ip' => 
        $user = User::where(['api_token' => $api_token])->first();
        if ($user)
            switch ($id) {
                case 1:
                    $box = LeitnerBox::where(['user_id' => $user->id])->whereIn('level', [1])->get()->pluck('id')->toArray();
                    break;
                case 2:
                    $box = LeitnerBox::where(['user_id' => $user->id])->whereIn('level', [2, 3])->get()->pluck('id')->toArray();
                    break;
                case 3:
                    $box = LeitnerBox::where(['user_id' => $user->id])->whereIn('level', [4, 5, 6, 7, 8])->get()->pluck('id')->toArray();
                    break;
                case 4:
                    $box = LeitnerBox::where(['user_id' => $user->id])->whereIn('level', [9, 10, 11, 12, 13, 14, 15, 16])->get()->pluck('id')->toArray();
                    break;
            }
        if (isset($box)) {
            return json_encode(['msg' => 'system_success', 'result' => $box]);
        } else return json_encode(['msg' => 'system_error']);
    }

    public function getUserReadyFlashcards(Request $request)
    {
          $api_token = $request->bearerToken();
        // 'api_ip' => 
        $user = User::where(['api_token' => $api_token])->first();
        if ($user) {
            $now = \Carbon\Carbon::today();
            $box = LeitnerBox::where(['user_id' => $user->id])->where('updated_at', '<', $now->toDateTimeString())->where(function ($query) {
                return $query->where('level', 1)->orWhere('level', 3)->orWhere('level', 8)->orWhere('level', 16);
            })->get()->pluck('id')->toArray();
            return json_encode(['msg' => 'system_success', 'result' => $box]);
        } else return json_encode(['msg' => 'system_error']);
    }

    public function getUserFlashcard(Request $request, $id)
    {
          $api_token = $request->bearerToken();
        // 'api_ip' => 
        $user = User::where(['api_token' => $api_token])->first();
        if ($user) {
            $card = LeitnerBox::where('id', $id)->with(['flashcard', 'flashcard.unit', 'flashcard.unit.lesson', 'flashcard.picture', 'flashcard.history' => function($q) use ($user) {
                $q->where('user_id', $user->id);
            },'flashcard.fav' => function($q) use ($user) {
                $q->where('user_id', $user->id);
            },])->first();
            return json_encode(['msg' => 'system_success', 'result' => $card]);
        } else return json_encode(['msg' => 'system_error']);
    }

    public function getUserFlashcardIds(Request $request)
    {
          $api_token = $request->bearerToken();
        // 'api_ip' => 
        $user = User::where(['api_token' => $api_token])->first();
        if ($user) {
            $box = LeitnerBox::where(['user_id' => $user->id])->where('level', '<>', 17)->get()->pluck('flashcard_id')->toArray();
            return json_encode(['msg' => 'system_success', 'result' => $box]);
        } else return json_encode(['msg' => 'system_error']);
    }

    public function getUserFlashcardsFinished(Request $request)
    {
          $api_token = $request->bearerToken();
        // 'api_ip' => 
        $user = User::where(['api_token' => $api_token])->first();
        if ($user) {
            $box = LeitnerBox::where(['user_id' => $user->id, 'level' => 17])->get()->pluck('flashcard_id')->toArray();
            return json_encode(['msg' => 'system_success', 'result' => $box]);
        } else return json_encode(['msg' => 'system_error']);
    }

    public function sync(Request $request)
    {
          $api_token = $request->bearerToken();
        // 'api_ip' => 
        $user = User::where(['api_token' => $api_token])->first();
        if ($user) {
            $now = \Carbon\Carbon::now()->setTime(0, 0, 0);
            $forCheck = LeitnerBox::where(['user_id' => $user->id])->get();
            foreach ($forCheck as $card) {
                $cardTime =  \Carbon\Carbon::parse($card->updated_at)->setTime(0, 0, 0);
                $diff = \Carbon\Carbon::parse($cardTime)->diffInDays($now);
                $leitnerItem = LeitnerBox::find($card->id);
                if ($diff > 0) {
                    $i = $diff;
                    while($i > 0) {
                        if ($leitnerItem->level + 1 < 17 && !in_array($leitnerItem->level, [1, 3, 8, 16])) {
                            $leitnerItem->increment('level', 1);
                        }
                        $i--;
                    }
                }
            }
            return json_encode(['msg' => 'system_success']);
        } else return json_encode(['msg' => 'system_error']);
    }

    public function grow(Request $request)
    {
          $api_token = $request->bearerToken();
        // 'api_ip' => 
        $user = User::where(['api_token' => $api_token])->first();
      
        if ($user) {
            $fhistory = FlashcardHistory::firstOrCreate(['user_id' => $user->id], [
                'flashcard_id' =>  $request->id
            ]);
            $fhistory->increment('history', 1);
            $card = LeitnerBox::where(['user_id' => $user->id, 'flashcard_id' => $request->id])->first();
            if ($card->level < 17)
                $card->increment('level', 1);
            else {
                $card->level = 17;
                $card->save();
            }
            return json_encode(['msg' => 'system_success']);
        } else return json_encode(['msg' => 'system_error']);
    }

    public function counter(Request $request)
    {
          $api_token = $request->bearerToken();
        $user = User::where(['api_token' => $api_token])->first();
        if ($user) {
            $counters = array([
                'L1' => LeitnerBox::where(['user_id' => $user->id])->whereIn('level', [1])->count(),
                'L2' => LeitnerBox::where(['user_id' => $user->id])->whereIn('level', [2,3])->count(),
                'L3' => LeitnerBox::where(['user_id' => $user->id])->whereIn('level', [4,5,6,7,8])->count(),
                'L4' => LeitnerBox::where(['user_id' => $user->id])->whereIn('level', [9,10,11,12,13,14,15,16])->count(),
            ]);
            return json_encode(['msg' => 'system_success', 'result' => $counters]);
        } else return json_encode(['msg' => 'system_error']);
    }

    public function fall(Request $request)
    {
          $api_token = $request->bearerToken();
        // 'api_ip' => 
        $user = User::where(['api_token' => $api_token])->first();

        if ($user) {
            $fhistory = FlashcardHistory::firstOrCreate(['user_id' => $user->id], ['flashcard_id' =>  $request->id]);
            $fhistory->increment('history', 1);
            LeitnerBox::where(['user_id' => $user->id, 'flashcard_id' => $request->id])->update(['level' => 1]);
            return json_encode(['msg' => 'system_success']);
        } else return json_encode(['msg' => 'system_error']);
    }

    public function readyToRead(Request $request)
    {
          $api_token = $request->bearerToken();
        // 'api_ip' => 
        $user = User::where(['api_token' => $api_token])->first();
        if ($user) {
            $now = \Carbon\Carbon::today();
            $count = LeitnerBox::where(['user_id' => $user->id])->where('updated_at', '<', $now->toDateTimeString())->where(function ($query) {
                return $query->where('level', 1)->orWhere('level', 3)->orWhere('level', 8)->orWhere('level', 16);
            })->count();
            return json_encode(['msg' => 'system_success', 'result' => $count]);
        } else return json_encode(['msg' => 'system_error']);
    }
}
