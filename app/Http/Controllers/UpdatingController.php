<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Updating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class UpdatingController extends Controller
{
    public function uploadFile(Request $request)
    {
        $app = $request->file('apk')->getClientOriginalName();
        $appName = uniqid() . '_' . $app;
        $path = 'uploads' . DIRECTORY_SEPARATOR . 'apk' . DIRECTORY_SEPARATOR;
        File::makeDirectory($path, 0777, true, true);
        $file = $request->file('apk')->move($path, $appName);
        $app = Updating::updateOrCreate(['id' => 1],
            [
                'title' => $request->title,
                'url' => $file,
            ]
        );
        if ($app)
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function getApp() {
        return Updating::first();
    }

    public function deleteApp() {
        Updating::truncate();
        return 1;
    }
}
