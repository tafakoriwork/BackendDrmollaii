<?php

namespace App\Http\Controllers\multimedia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PDF;
use Illuminate\Support\Facades\File;

class PDFController extends Controller
{
    public function upload(Request $request)
    {
        $PDF = $request->file('mediafile')->getClientOriginalName();
        $PDFName = uniqid() . '_' . $PDF;
        $path = 'uploads' . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR;
        File::makeDirectory($path, 0777, true, true);
        $file = $request->file('mediafile')->move($path, $PDFName);
        $_pdf = PDF::create(
            [
                'title' => $request->title,
                'category_id' => $request->parent_id,
                'price' => $request->price,
                'url' => $path . $PDFName,
                'payment_id' => $request->payment_id,
                'desc' => $request->desc,
            ]
        );


        if ($file) {

            if ($request->file('picture')) {
                $mediaRequest1 = clone $request;
                $mediaRequest1->replace([
                    'id' => $_pdf->id,
                    'type' => 'App\Models\PDF',
                ]);
                PDFController::uploadpic($mediaRequest1, 'picture');
            }
            return json_encode(['msg' => 'system_success']);
        } else return json_encode(['msg' => 'system_error']);
    }

    public function Delete($id)
    {
        $PDF = PDF::find($id);
        if ($PDF->url && unlink($PDF->url))
            return PDF::destroy($id);
    }


    public function edit(Request $request, $id)
    {
        $edit = json_decode($request->edit, true);
        $pdf = PDF::find($id);
        $pdf->title = $edit['title'];
        $pdf->price = $edit['price'];
        $pdf->desc = $edit['desc'];
        if ($request->file('picture')) {
            $mediaRequest1 = clone $request;
            $mediaRequest1->replace([
                'id' => $id,
                'type' => 'App\Models\PDF',
            ]);
            PDFController::uploadpic($mediaRequest1, 'picture');
        }
        if ($request->file('mediafile')) {
            if ($pdf->url) unlink($pdf->url);
            $PDF = $request->file('mediafile')->getClientOriginalName();
            $PDFName = uniqid() . '_' . $PDF;
            $path = 'uploads' . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR;
            File::makeDirectory($path, 0777, true, true);
            $file = $request->file('mediafile')->move($path, $PDFName);
            $pdf->url = $file;
        }
        if ($pdf->save())
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }


    protected static function uploadpic(Request $request, $mediatype)
    {
        if ($mediatype == 'picture')
            PictureController::upload($request);
    }

    public function buyers($parent_id)
    {
        $buyers = PDF::where('id', $parent_id)->with(['paids', 'paids.user'])->get();
        return $buyers;
    }
}
