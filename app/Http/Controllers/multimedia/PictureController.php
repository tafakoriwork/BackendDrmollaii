<?php

namespace App\Http\Controllers\multimedia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Picture;
use Illuminate\Support\Facades\File;

class PictureController extends Controller
{
    public static function upload(Request $request)
    {
        $self = new PictureController();
        if ($self->ExistCheck($request->id, $request->type))
            $self->Delete($request->id, $request->type);

        $picture = $request->file('picture')->getClientOriginalName();
        $pictureName = uniqid() . '_' . $picture;
        $path = 'uploads' . DIRECTORY_SEPARATOR . 'pictures' . DIRECTORY_SEPARATOR;
        File::makeDirectory($path, 0777, true, true);
        $file = $request->file('picture')->move($path, $pictureName);
        Picture::updateOrCreate(
            [
                'picturable_id' => $request->id,
                'picturable_type' => $request->type,
            ],
            [
                'url' => $path . $pictureName,
            ]
        );
        if ($file) {
            return json_encode(['msg' => 'system_success']);
        } else return json_encode(['msg' => 'system_error']);
    }

    public function ExistCheck($id, $type)
    {
        return Picture::where([
            'picturable_id' => $id,
            'picturable_type' => $type,
        ])->exists();
    }

    public function Delete($id, $type)
    {
        $picture = Picture::where([
            'picturable_id' => $id,
            'picturable_type' => $type,
        ])->first();
        if ($picture && $picture->url)
            return unlink($picture->url);
    }
}
