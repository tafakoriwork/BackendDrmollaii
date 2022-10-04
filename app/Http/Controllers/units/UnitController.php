<?php

namespace App\Http\Controllers\units;

use App\Http\Controllers\Controller;
use App\Http\Controllers\multimedia\AudioController;
use App\Http\Controllers\multimedia\PictureController;
use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\User;

class UnitController extends Controller
{
    public function store(Request $request)
    {
        $newUnit = Unit::create([
            'title' => $request->title,
            'lesson_id' => $request->parent_id,
        ]);

        if ($newUnit)
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function delete($id)
    {
        $pictureOBJ = new PictureController();
        $pictureOBJ->Delete($id, "App\Models\Unit");
        if (Unit::destroy($id))
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function update(Request $request, $id)
    {
        $unit = Unit::find($id);
        $unit->title = $request->title;
        $unit->color = $request->color;
        if ($request->file('picture')) {
            $mediaRequest1 = clone $request;
            $mediaRequest1->replace([
                'id' => $id,
                'type' => 'App\Models\Unit',
            ]);
            UnitController::upload($mediaRequest1, 'picture');
        }
        if ($request->file('audio')) {
            $mediaRequest2 = clone $request;
            $mediaRequest2->replace([
                'id' => $id,
                'type' => 'App\Models\Unit',
            ]);
            UnitController::upload($mediaRequest2, 'audio');
        }
        if ($unit->save())
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function show($id)
    {
        $Unit = Unit::where('id', $id)->with(['flashcards', 'audio', 'picture'])->first();
        if ($Unit)
            return json_encode(['msg' => 'system_success', 'result' => $Unit]);
        else return json_encode(['msg' => 'system_error']);
    }

    public function showall($parent_id)
    {
        $Unit = Unit::where('lesson_id', $parent_id)->with(['flashcards', 'audio', 'picture'])->get();
        if ($Unit)
            return json_encode(['msg' => 'system_success', 'result' => $Unit]);
        else return json_encode(['msg' => 'system_error']);
    }

    public function showallforapp(Request $request, $parent_id)
    {
    
      $api_token = $request->bearerToken();
        // 'api_ip' => 
        $user = User::where(['api_token' => $api_token])->first();
        $Unit = Unit::where('lesson_id', $parent_id)->withCount('flashcards')->with(['audio', 'picture', 'lesson', 'lesson.paid' => function($q) use ($user){
            $q->where('user_id', $user->id);
            }])->get();
        if ($Unit)
            return json_encode(['msg' => 'system_success', 'result' => $Unit]);
        else return json_encode(['msg' => 'system_error']);
    }

    protected static function upload(Request $request, $mediatype)
    {
        if ($mediatype == 'picture')
            PictureController::upload($request);
        else if ($mediatype == 'audio')
            AudioController::upload($request);
    }
}
