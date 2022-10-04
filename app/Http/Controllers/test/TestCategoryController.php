<?php

namespace App\Http\Controllers\test;

use App\Http\Controllers\Controller;
use App\Models\TestCategory;
use Illuminate\Http\Request;

class TestCategoryController extends Controller
{
    public function index()
    {
        return TestCategory::all();
    }

    public function store(Request $request)
    {
        $TestCategory = TestCategory::create([
            'name' => $request->name,
        ]);
        if ($TestCategory)
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function delete($id)
    {
        if (TestCategory::destroy($id))
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function update(Request $request)
    {
        $TestCategory = TestCategory::where('id', $request->id)->update([
            'name' => $request->name,
        ]);
        if ($TestCategory)
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function show($id)
    {
        return TestCategory::where('id', $id)->with(['tests', 'tests.questions', 'tests.answers'])->first();
    }
}
