<?php

namespace App\Http\Controllers\units;

use App\Http\Controllers\Controller;
use App\Http\Controllers\multimedia\AudioController;
use App\Http\Controllers\multimedia\PictureController;
use Illuminate\Http\Request;
use App\Models\Flashcard;
use App\Models\FlashcardHistory;
use App\Models\User;

class FlashcardController extends Controller
{
    protected $ob;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ob = new Flashcard();
    }

    public function store(Request $request)
    {

        $this->ob->front = $request->front;
        $this->ob->back = $request->back;
        $this->ob->unit_id = $request->parent_id;
        $this->ob->order = Flashcard::count() + 1;
        if ($this->ob->save())
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function editFree(Request $request)
    {
          $api_token = $request->bearerToken();
        // 'api_ip' => 
        $user = User::where(['api_token' => $api_token])->first();
        $card = Flashcard::where(['id' => $request->id, 'user_id' => $user->id])->first();
        $card->front = $request->front;
        $card->back = $request->back;
        if ($card->save()) {
            if ($request->file('picture')) {
                $card->url && unlink($card->url);
                $mediaRequest1 = clone $request;
                $mediaRequest1->replace([
                    'id' => $card->id,
                    'type' => 'App\Models\Flashcard',
                ]);
                FlashcardController::upload($mediaRequest1, 'picture');
            }
            return json_encode(['msg' => 'system_success']);
        } else return json_encode(['msg' => 'system_error']);
    }

    public function storeFree(Request $request)
    {
          $api_token = $request->bearerToken();
        // 'api_ip' => 
        $user = User::where(['api_token' => $api_token])->first();
        $this->ob->front = $request->front;
        $this->ob->back = $request->back;
        $this->ob->user_id = $user->id;
        $this->ob->freeflashcardscategory_id = $request->parent_id;
        $this->ob->order = Flashcard::count() + 1;
        if ($this->ob->save()) {
            if ($request->file('picture')) {
                $mediaRequest1 = clone $request;
                $mediaRequest1->replace([
                    'id' => $this->ob->id,
                    'type' => 'App\Models\Flashcard',
                ]);
                FlashcardController::upload($mediaRequest1, 'picture');
            }
            return json_encode(['msg' => 'system_success']);
        } else return json_encode(['msg' => 'system_error']);
    }

    public function delete($id)
    {
        $pictureOBJ = new PictureController();
        $pictureOBJ->Delete($id, "App\Models\Flashcard");
        if (Flashcard::destroy($id))
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function orederEdit(Request $request)
    {
        $oldOrder = Flashcard::find($request->id)->order;
        if ($request->state == 'plus') {
            $next = Flashcard::where('order', '>', $oldOrder)->orderBy('order', 'asc')->first();
            $newOrder = $next->order;
            Flashcard::where('id', $next->id)->update(['order' => $oldOrder]);
        } else if ($request->state == 'minus') {
            $prev = Flashcard::where('order', '<', $oldOrder)->orderBy('order', 'desc')->first();
            $newOrder = $prev->order;
            Flashcard::where('id', $prev->id)->update(['order' => $oldOrder]);
        }
        if (Flashcard::where('id', $request->id)->update(['order' => $newOrder]))
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }


    public function update(Request $request, $id)
    {
        $flashcard = Flashcard::find($id);
        $flashcard->front = $request->front;
        $flashcard->back = $request->back;
        if ($request->file('picture')) {
            $mediaRequest1 = clone $request;
            $mediaRequest1->replace([
                'id' => $id,
                'type' => 'App\Models\Flashcard',
            ]);
            FlashcardController::upload($mediaRequest1, 'picture');
        }
        if ($request->file('audio')) {
            $mediaRequest2 = clone $request;
            $mediaRequest2->replace([
                'id' => $id,
                'type' => 'App\Models\Flashcard',
            ]);
            FlashcardController::upload($mediaRequest2, 'audio');
        }
        if ($flashcard->save())
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function show(Request $request, $id)
    {
        $api_token = $request->bearerToken();
        // 'api_ip' => 
        $user = User::where(['api_token' => $api_token])->first();
        $Flashcard = Flashcard::where('id', $id)->with(['picture', 'audio', 'unit', 'unit.lesson', 'history' => function($q) use ($user) {
            $q->where('user_id', $user->id);
        }, 'fav' => function($q) use ($user) {
            $q->where('user_id', $user->id);
        }])->first();
        if ($Flashcard)
            return json_encode(['msg' => 'system_success', 'result' => $Flashcard]);
        else return json_encode(['msg' => 'system_error']);
    }

    public function saveNote(Request $request) {
        $api_token = $request->bearerToken();
        // 'api_ip' => 
        $user = User::where(['api_token' => $api_token])->first();
        return FlashcardHistory::updateOrCreate([
            'flashcard_id' => $request->id,
            'user_id' => $user->id,
        ],[
            'note' => $request->text,
        ]);
    }

    public function getIds($parent_id)
    {
        $Flashcard = Flashcard::where('unit_id', $parent_id)->pluck('id')->toArray();
        if ($Flashcard)
            return json_encode(['msg' => 'system_success', 'result' => $Flashcard]);
        else return json_encode(['msg' => 'system_error']);
    }

    public function showall($parent_id)
    {
        $Flashcard = Flashcard::where('unit_id', $parent_id)->orderBy('order', 'ASC')->with(['picture', 'audio'])->get();
        if ($Flashcard)
            return json_encode(['msg' => 'system_success', 'result' => $Flashcard]);
        else return json_encode(['msg' => 'system_error']);
    }

    public function showallFree(Request $request)
    {
          $api_token = $request->bearerToken();
        // 'api_ip' => 
        $user = User::where(['api_token' => $api_token])->first();
        $Flashcard = Flashcard::where('freeflashcardscategory_id', $request->freeflashcardscategory_id)->orderBy('order', 'DESC')->with(['picture'])->get();
        if ($Flashcard)
            return json_encode(['msg' => 'system_success', 'result' => $Flashcard]);
        else return json_encode(['msg' => 'system_error']);
    }

    
    public function deletefree(Request $request, $id)
    {
          $api_token = $request->bearerToken();
        // 'api_ip' => 
        $user = User::where(['api_token' => $api_token])->first();
        if ($user) {
            $rmfav = Flashcard::where(['user_id' => $user->id, 'id' => $id])->delete();
            if($rmfav)
            return json_encode(['msg' => 'system_success']);
        } else return json_encode(['msg' => 'system_error']);
    }

    public function upload(Request $request, $mediatype)
    {
        if ($mediatype == 'picture')
            return PictureController::upload($request);
        else if ($mediatype == 'audio')
            return AudioController::upload($request);
    }

    public function search(Request $request)
    {
        return Flashcard::where('front', 'LIKE', "%$request->text%")->with(['picture', 'audio'])->get();
    }

    public function srch(Request $request)
    {
        return Flashcard::where('front', 'LIKE', "%$request->txt%")->get()->pluck('id')->toArray();
    }
}
