<?php

namespace App\Http\Controllers\units;

use App\Http\Controllers\Controller;
use App\Http\Controllers\multimedia\AudioController;
use App\Http\Controllers\multimedia\PictureController;
use Illuminate\Http\Request;
use App\Models\Major;

class MajorController extends Controller
{
    protected $ob;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ob = new Major();
    }

    public function store(Request $request)
    {
        $major = Major::create([
            "title" => $request->title,
            "majormain_id" => $request->parent_id
        ]);

        if ($request->file('picture')) {
            $mediaRequest1 = clone $request;
            $mediaRequest1->replace([
                'id' => $major->id,
                'type' => 'App\Models\Major',
            ]);
            MajorController::upload($mediaRequest1, 'picture');
        }
        
        if ($major)
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function delete($id)
    {
        if (Major::destroy($id))
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function update(Request $request, $id)
    {
        
        $Major = Major::find($id);
        $Major->title = $request->title;
        if ($request->file('picture')) {
            $mediaRequest1 = clone $request;
            $mediaRequest1->replace([
                'id' => $id,
                'type' => 'App\Models\Major',
            ]);
            MajorController::upload($mediaRequest1, 'picture');
        }
        if ($Major->save())
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);



        if ($request->file('picture')) {
            $mediaRequest1 = clone $request;
            $mediaRequest1->replace([
                'id' => $id,
                'type' => 'App\Models\Lesson',
            ]);
            MajorController::upload($mediaRequest1, 'picture');
        }
        if (Major::where('id', $id)->update(['title' => $request->title]))
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function show($id)
    {
        $Major = Major::where('id', $id)->with(['lessons', 'picture'])->first();
        if ($Major)
            return json_encode(['msg' => 'system_success', 'result' => $Major]);
        else return json_encode(['msg' => 'system_error']);
    }

    public function showall($parent_id)
    {
        $Major = Major::where('majormain_id', $parent_id)->with('picture')->get();
        if ($Major)
            return json_encode(['msg' => 'system_success', 'result' => $Major]);
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
