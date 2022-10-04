<?php

namespace App\Http\Controllers\test;

use App\Http\Controllers\Controller;
use App\Http\Controllers\multimedia\AudioController;
use App\Http\Controllers\multimedia\PictureController;
use App\Models\TestAnswer;
use App\Models\TestQuestion;
use Illuminate\Http\Request;

class TestQuestionController extends Controller
{
    public function index(Request $request)
    {
        return TestQuestion::where('test_id', $request->parent_id)->skip($request->offset)->take(5)->with(['answers', 'correct', 'picture'])->get();
    }

    public function index2(Request $request)
    {
        return TestQuestion::where('test_id', $request->parent_id)->with(['answers', 'correct', 'picture'])->get();
    }

    public function count(Request $request) {
        return TestQuestion::where('test_id', $request->parent_id)->skip($request->offset)->take(5)->with(['answers', 'correct', 'picture'])->count();
    }

    public function store(Request $request)
    {
        $answers = json_decode($request->answers, true);
        $TestQuestion = TestQuestion::create([
            'text' => $request->question_text,
            'test_id' => $request->parent_id,
        ]);

        if ($TestQuestion) {
            foreach ($answers as $key => $answer) {
                if ($key != 'correct') {
                    if ($answers['correct'] != $key)
                        TestAnswer::create([
                            'text' => $answer,
                            'question_id' => $TestQuestion->id,
                        ]);
                    else {
                        $answer = TestAnswer::create([
                            'text' => $answer,
                            'question_id' => $TestQuestion->id,
                        ]);
                        $TestQuestion = TestQuestion::find($TestQuestion->id);
                        $TestQuestion->correct_answer = $answer->id;
                        $TestQuestion->save();
                    }
                }
            }

            if ($request->file('picture')) {
                $mediaRequest1 = clone $request;
                $mediaRequest1->replace([
                    'id' => $TestQuestion->id,
                    'type' => 'App\Models\TestQuestion',
                ]);
                TestQuestionController::upload($mediaRequest1, 'picture');
            }
        

            return json_encode(['msg' => 'system_success']);
        } else return json_encode(['msg' => 'system_error']);
    }

    public function delete($id)
    {
        if (TestQuestion::destroy($id))
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function update(Request $request, $id)
    {
        $edit = json_decode($request->edit, true);
        $TestQuestion = TestQuestion::where('id', $id)->update([
            'text' => $edit['text'],
            'correct_answer' => $edit['correct_answer'],
        ]);
        
        foreach ($edit['answers'] as $answer) {
            TestAnswer::where('id', $answer['id'])->update([
                'text' => $answer['text']
            ]);
        }
        $TestQuestion = TestQuestion::find($id);
        if ($request->file('picture')) {
            $mediaRequest1 = clone $request;
            $mediaRequest1->replace([
                'id' => $TestQuestion->id,
                'type' => 'App\Models\TestQuestion',
            ]);
            TestQuestionController::upload($mediaRequest1, 'picture');
        }
        if ($TestQuestion)
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function show($id)
    {
        return TestQuestion::find($id);
    }

    protected static function upload(Request $request, $mediatype)
    {
        if ($mediatype == 'picture')
            PictureController::upload($request);
    }
}
