<?php

namespace App\Http\Controllers\multimedia;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PDFcategory;
use App\Models\User;

class PDFCategoryController extends Controller
{
    protected $ob;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ob = new PDFcategory();
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
        if (PDFcategory::destroy($id))
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function update(Request $request, $id)
    {
        if (PDFcategory::where('id',$id)->update(['title' => $request->title]))
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function show(Request $request, $id)
    {
        $api_token = $request->bearerToken();
        // 'api_ip' => 
        $user = User::where(['api_token' => $api_token])->first();
        $PDFcategory = PDFcategory::where('id', $id)->with(['pdfs', 'pdfs.picture', 'pdfs.paid' => function($q) use($user){
            $q->where('user_id', $user->id);
            }])->first();
        if ($PDFcategory)
            return json_encode(['msg' => 'system_success', 'result' => $PDFcategory]);
        else return json_encode(['msg' => 'system_error']);
    }

    public function showall()
    {
        $PDFcategory = PDFcategory::with(['pdfs', 'pdfs.picture'])->get();
        if ($PDFcategory)
            return json_encode(['msg' => 'system_success', 'result' => $PDFcategory]);
        else return json_encode(['msg' => 'system_error']);
    }

}
