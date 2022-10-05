<?php

namespace App\Http\Controllers\multimedia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Audio;
use Illuminate\Support\Facades\File;

class AudioController extends Controller
{
    public static function upload(Request $request)
    {
        $self = new AudioController();
        if ($self->ExistCheck($request->id, $request->type))
            $self->Delete($request->id, $request->type);

        $Audio = $request->file('audio')->getClientOriginalName();
        $AudioName = uniqid() . '_' . $Audio;
        $path = 'uploads' . DIRECTORY_SEPARATOR . 'audios' . DIRECTORY_SEPARATOR;
        File::makeDirectory($path, 0777, true, true);
        $file = $request->file('audio')->move($path, $AudioName);
        Audio::updateOrCreate(
            [
                'audioable_id' => $request->id,
                'audioable_type' => $request->type,
            ],
            [
                'url' => $path . $AudioName,
            ]
        );
        if ($file) {
            return json_encode(['msg' => 'system_success']);
        } else return json_encode(['msg' => 'system_error']);
    }

    public function ExistCheck($id, $type)
    {
        return Audio::where([
            'audioable_id' => $id,
            'audioable_type' => $type,
        ])->exists();
    }

    public function Delete($id, $type)
    {
        $Audio = Audio::where([
            'audioable_id' => $id,
            'audioable_type' => $type,
        ])->first();
        if ($Audio->url)
            return unlink($Audio->url);
    }
}
