<?php

namespace App\Http\Controllers;

use App\Models\Option;
use Illuminate\Http\Request;

class OptionsController extends Controller
{
    public function store(Request $request)
    {
        $newOption = Option::updateOrCreate([
            'name' => $request->name
        ], [
            'title' => $request->title,
            'text' => $request->text,
        ]);
        if ($newOption) {
            return json_encode(['msg' => 'system_success']);
        } else return json_encode(['msg' => 'system_error']);
    }

    public function delete($id)
    {
        if (Option::destroy($id))
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function show($name)
    {
        $Option = Option::where('name', $name)->first();
        if ($Option)
            return json_encode(['msg' => 'system_success', 'result' => $Option]);
        else return json_encode(['msg' => 'system_error']);
    }

    public function showall()
    {
        $Option = Option::get();
        if ($Option)
            return json_encode(['msg' => 'system_success', 'result' => $Option]);
        else return json_encode(['msg' => 'system_error']);
    }
}
