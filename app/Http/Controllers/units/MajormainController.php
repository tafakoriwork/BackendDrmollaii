<?php

namespace App\Http\Controllers\units;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Majormain;

class MajormainController extends Controller
{
    protected $ob;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ob = new Majormain();
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
        if (Majormain::destroy($id))
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function update(Request $request, $id)
    {
        if (Majormain::where('id',$id)->update(['title' => $request->title]))
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function show($id)
    {
        $Majormain = Majormain::where('id', $id)->with('majors')->first();
        if ($Majormain)
            return json_encode(['msg' => 'system_success', 'result' => $Majormain]);
        else return json_encode(['msg' => 'system_error']);
    }

    public function showall(Request $request)
    {
        $Majormain = Majormain::get();
        if ($Majormain)
            return json_encode(['msg' => 'system_success', 'result' => $Majormain]);
        else return json_encode(['msg' => 'system_error']);
    }

}
