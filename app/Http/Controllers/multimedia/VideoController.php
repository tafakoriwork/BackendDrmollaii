<?php

namespace App\Http\Controllers\multimedia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\Videocategory;
use Illuminate\Support\Facades\File;

class VideoController extends Controller
{
    public function upload(Request $request)
    {
        $type = $request->type == 1 ? 'video' : 'audio';
        $Video = $request->file('mediafile')->getClientOriginalName();
        $VideoName = uniqid() . '_' . $Video;
        $path = 'uploads' . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR;
        File::makeDirectory($path, 0777, true, true);
        $file = $request->file('mediafile')->move($path, $VideoName);
        $_video = Video::create(
            [
                'title' => $request->title,
                'category_id' => $request->parent_id,
                'url' => $path . $VideoName,
                'price' => $request->price,
                'type' => $type,
                'payment_id' => $request->payment_id,
                'desc' => $request->desc,
            ]
        );
        if ($file) {
            if ($request->file('picture')) {
                $mediaRequest1 = clone $request;
                $mediaRequest1->replace([
                    'id' => $_video->id,
                    'type' => 'App\Models\Video',
                ]);
                VideoController::uploadpic($mediaRequest1, 'picture');
            }
            return json_encode(['msg' => 'system_success']);
        } else return json_encode(['msg' => 'system_error']);
    }

    
    public function edit(Request $request, $id)
    {
        $edit = json_decode($request->edit, true);
        $video = Video::find($id);
        $video->title = $edit['title'];
        $video->price = $edit['price'];
        $video->desc = $edit['desc'];
        if ($request->file('picture')) {
            $mediaRequest1 = clone $request;
            $mediaRequest1->replace([
                'id' => $id,
                'type' => 'App\Models\Video',
            ]);
            VideoController::uploadpic($mediaRequest1, 'picture');
        }
        if ($request->file('mediafile')) {
            $type = $video->type == 1 ? 'video' : 'audio';
            if ($video->url) unlink($video->url);
            $PDF = $request->file('mediafile')->getClientOriginalName();
            $PDFName = uniqid() . '_' . $PDF;
            $path = 'uploads' . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR;
            File::makeDirectory($path, 0777, true, true);
            $file = $request->file('mediafile')->move($path, $PDFName);
            $video->url = $file;
        }
        if ($video->save())
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function Delete($id)
    {
        $Video = Video::find($id);
        if ($Video && $Video->url && unlink($Video->url))
            return Video::destroy($id);
    }
      
    protected static function uploadpic(Request $request, $mediatype)
    {
        if ($mediatype == 'picture')
            PictureController::upload($request);
    }

    public function buyers($parent_id)
    {
        $buyers = Video::where('id', $parent_id)->with(['paids', 'paids.user'])->get();
        return $buyers;
    }
}
