<?php

namespace App\Http\Controllers\test;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\Testresults;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $api_token = $request->bearerToken();
        $user = User::where(['api_token' => $api_token])->first();
        return Test::Where('category_id', $request->parent_id)->with(['resulted' => function ($q) use ($user) {
            $q->where('user_id', $user->id);
        },'paid' => function($q) use($user){
            $q->where('user_id', $user->id);
            } ])->get();

            
    }

    public function store(Request $request)
    {
        if($request->file('mediafile')){
            $PDF = $request->file('mediafile')->getClientOriginalName();
            $PDFName = uniqid() . '_' . $PDF;
            $path = 'uploads' . DIRECTORY_SEPARATOR . 'pdftests' . DIRECTORY_SEPARATOR;
            File::makeDirectory($path, 0777, true, true);
            $file = $request->file('mediafile')->move($path, $PDFName);
            $test = Test::create([
                'name' => $request->name,
                'time' => $request->time,
                'price' => $request->price,
                'payment_id' => $request->payment_id,
                'category_id' => $request->parent_id,
                'url' => $file,
            ]);
        } else {
            $test = Test::create([
                'name' => $request->name,
                'time' => $request->time,
                'price' => $request->price,
                'payment_id' => $request->payment_id,
                'category_id' => $request->parent_id,
            ]);
        }
        if ($test)
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function delete($id)
    {
        if (Test::destroy($id))
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function update(Request $request)
    {
        $test = Test::where('id', $request->id)->update([
            'name' => $request->name,
            'time' => $request->time,
            'price' => $request->price,
            'payment_id' => $request->payment_id,
        ]);

        if($request->file('mediafile')){
            $test = Test::find($request->id);
            $test->url && unlink($test->url);
            $PDF = $request->file('mediafile')->getClientOriginalName();
            $PDFName = uniqid() . '_' . $PDF;
            $path = 'uploads' . DIRECTORY_SEPARATOR . 'pdftests' . DIRECTORY_SEPARATOR;
            File::makeDirectory($path, 0777, true, true);
            $file = $request->file('mediafile')->move($path, $PDFName);
            $test->url = $file;
            $test->save();
        }
        if ($test)
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function show1(Request $request, $id)
    {


        $api_token = $request->bearerToken();
        $user = User::where(['api_token' => $api_token])->first();
        return Test::Where('id', $id)->with(['resulted' => function ($q) use ($user) {
            $q->where('user_id', $user->id);
        },'paid' => function($q) use($user){
            $q->where('user_id', $user->id);
            } ])->first();
    }

    public function show($id)
    {
        return Test::find($id);
    }

    public function saveresult(Request $request)
    {
        $api_token = $request->bearerToken();
        $user = User::where(['api_token' => $api_token])->first();
        $result = Testresults::create([
            'result' => json_encode($request->result),
            'rate' => $request->rate,
            'test_id' => $request->parent_id,
            'user_id' => $user->id,
        ]);

        return $result;
    }

    public function buyers($parent_id)
    {
        $buyers = Test::where('id', $parent_id)->with(['paids', 'paids.user'])->get();
        return $buyers;
    }
}
