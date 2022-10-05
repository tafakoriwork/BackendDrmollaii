<?php

namespace App\Http\Controllers\units;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\User;
use App\Http\Controllers\multimedia\AudioController;
use App\Http\Controllers\multimedia\PictureController;

class LessonController extends Controller
{
    public function store(Request $request)
    {
        $newLesson = Lesson::create([
            'title' => $request->title,
            'price' => $request->price,
            'major_id' => $request->parent_id,
            'payment_id' => $request->payment_id,
            'desc' => $request->desc,
        ]);
        if ($request->file('picture')) {
            $mediaRequest1 = clone $request;
            $mediaRequest1->replace([
                'id' => $newLesson->id,
                'type' => 'App\Models\Lesson',
            ]);
            LessonController::upload($mediaRequest1, 'picture');
        }
        if ($request->file('audio')) {
            $mediaRequest2 = clone $request;
            $mediaRequest2->replace([
                'id' => $newLesson->id,
                'type' => 'App\Models\Lesson',
            ]);
            LessonController::upload($mediaRequest2, 'audio');
        }
        return json_encode(['msg' => 'system_success']);
    }

    public function delete($id)
    {
        $pictureOBJ = new PictureController();
        $pictureOBJ->Delete($id, "App\Models\Lesson");
        if (Lesson::destroy($id))
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function update(Request $request, $id)
    {
        $lesson = Lesson::find($id);
        $lesson->title = $request->title;
        $lesson->price = $request->price;
        $lesson->payment_id = $request->payment_id;
        $lesson->desc = $request->desc;
        if ($request->file('picture')) {
            $mediaRequest1 = clone $request;
            $mediaRequest1->replace([
                'id' => $id,
                'type' => 'App\Models\Lesson',
            ]);
            LessonController::upload($mediaRequest1, 'picture');
        }
        if ($request->file('audio')) {
            $mediaRequest2 = clone $request;
            $mediaRequest2->replace([
                'id' => $id,
                'type' => 'App\Models\Lesson',
            ]);
            LessonController::upload($mediaRequest2, 'audio');
        }
        if ($lesson->save())
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function show($id)
    {
        $Lesson = Lesson::where('id', $id)->with(['units', 'audio', 'picture'])->first();
        if ($Lesson)
            return json_encode(['msg' => 'system_success', 'result' => $Lesson]);
        else return json_encode(['msg' => 'system_error']);
    }

    public function showall(Request $request,$parent_id)
    {
          $api_token = $request->bearerToken();
        // 'api_ip' => 
        $user = User::where(['api_token' => $api_token])->first();
        $Lesson = Lesson::where('major_id', $parent_id)->with(['units', 'audio', 'picture', 'paid' => function($q) use($user){
            $q->where('user_id', $user->id);
            }])->get();
        if ($Lesson)
            return json_encode(['msg' => 'system_success', 'result' => $Lesson]);
        else return json_encode(['msg' => 'system_error']);
    }

    protected static function upload(Request $request, $mediatype)
    {
        if ($mediatype == 'picture')
            PictureController::upload($request);
        else if ($mediatype == 'audio')
            AudioController::upload($request);
    }

    public function buyers($parent_id)
    {
        $buyers = Lesson::where('id', $parent_id)->with(['paids', 'paids.user'])->get();
        return $buyers;
    }
}
