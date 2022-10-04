<?php

namespace App\Http\Controllers\test;

use App\Http\Controllers\Controller;
use App\Models\TestAnswer;
use Illuminate\Http\Request;

class TestAnswerController extends Controller
{
    public function index()
    {
        return TestAnswer::all();
    }

    public function store(Request $request)
    {
        $TestAnswer = TestAnswer::create([
            'text' => $request->text,
            'question_id' => $request->question_id
        ]);
        if ($TestAnswer)
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function delete($id)
    {
        if (TestAnswer::destroy($id))
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function update(Request $request)
    {
        $TestAnswer = TestAnswer::where('id', $request->id)->update([
            'text' => $request->text,
            'question_id' => $request->question_id
        ]);
        if ($TestAnswer)
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function show($id)
    {
        return TestAnswer::find($id);
    }

}
