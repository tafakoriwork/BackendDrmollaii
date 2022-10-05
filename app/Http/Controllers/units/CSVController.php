<?php

namespace App\Http\Controllers\units;

use App\Http\Controllers\Controller;
use App\Models\ErteghaFlashCard;
use App\Models\ErteghaPart;
use App\Models\ErteghaSubunits;
use App\Models\Flashcard;
use App\Models\PreEntryMajorFlashCard;
use App\Models\PreEntryMajorUnit;
use App\Models\PreEntryMinorFlashCard;
use App\Models\PreEntryMinorUnit;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CSVController extends Controller
{
    public function createFlashcards($file, $parent_id)
    {
        $row = 1;
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $unit = Unit::firstOrCreate(['lesson_id' => $parent_id, 'title' => $data[3]]);
                Flashcard::create([
                    'front' => $data[0],
                    'back' => $data[1],
                    'unit_id' => $unit->id,
                    'order' => $row,
                ]);
                $row++;
            }
            fclose($handle);
        }
    }

    public function upload(Request $request)
    {
        $csvName = $request->file('csv')->getClientOriginalName();
        $csvName = uniqid() . '_' . $csvName;
        $path = 'uploads' . DIRECTORY_SEPARATOR . 'csv' . DIRECTORY_SEPARATOR;
        $destinationPath = $path;
        File::makeDirectory($destinationPath, 0777, true, true);
        $file = $request->file('csv')->move($destinationPath, $csvName);
        if ($file) {
            $this->createFlashcards($file, $request->parent_id);
            return json_encode(['msg' => 'system_success']);
        } else return json_encode(['msg' => 'system_error']);
    }
}
