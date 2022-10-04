<?php

namespace App\Http\Controllers\multimedia;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Videocategory;

class VideoCategoryController extends Controller
{
    protected $ob;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ob = new Videocategory();
    }

    public function store(Request $request)
    {
        
        $this->ob->title = $request->title;
        if ($this->ob->save())
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function delete($id)
    {
        if (Videocategory::destroy($id))
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function update(Request $request, $id)
    {
        if (Videocategory::where('id',$id)->update(['title' => $request->title]))
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function show(Request $request, $id)
    {
        $api_token = $request->bearerToken();
        // 'api_ip' => 
        $user = User::where(['api_token' => $api_token])->first();
        $Videocategory = Videocategory::where('id', $id)->with(['videos', 'videos.picture', 'videos.paid' => function($q) use($user){
            $q->where('user_id', $user->id);
            }])->first();
        if ($Videocategory)
            return json_encode(['msg' => 'system_success', 'result' => $Videocategory]);
        else return json_encode(['msg' => 'system_error']);
    }

    public function showall()
    {
        $Videocategory = Videocategory::with(['videos', 'videos.picture'])->get();
        if ($Videocategory)
            return json_encode(['msg' => 'system_success', 'result' => $Videocategory]);
        else return json_encode(['msg' => 'system_error']);
    }

}
