<?php

namespace App\Http\Controllers\units;

use App\Http\Controllers\Controller;
use App\Models\Flashcard;
use App\Models\FreeFlashcard;
use App\Models\User;
use Illuminate\Http\Request;

class FreeFlashcardController extends Controller
{
    public function index(Request $request)
    {
        $api_token = $request->bearerToken();
        $user = User::where(['api_token' => $api_token])->first();
        return FreeFlashcard::where('user_id', $user->id)->get();
    }

    public function ids(Request $request)
    {
        return Flashcard::where('freeflashcardscategory_id', $request->id)->get()->pluck('id');
    }

    public function store(Request $request)
    {
        $api_token = $request->bearerToken();
        $user = User::where(['api_token' => $api_token])->first();
        return FreeFlashcard::create([
            'user_id' => $user->id,
            'title' => $request->title,
        ]);
    }

    public function delete($id)
    {
        return FreeFlashcard::destroy($id);
    }

    public function createCSV(Request $request)
    {
        $flashcards = Flashcard::where('user_id', $request->user_id)->with('category')->get();
        $Array_data = [];

        foreach ($flashcards as $flashcard) {
            if($flashcard->category)
            array_push($Array_data, [$flashcard->front, $flashcard->back, '', $flashcard->category->title]);
        }

        $Filename = rand(10000, 999999).'_file.csv';
        header('Content-Type: text/csv; charset=utf-8');
        Header('Content-Type: application/force-download');
        header('Content-Disposition: attachment; filename=' . $Filename . '');
        // create a file pointer connected to the output stream
        $output = fopen('php://output', 'w');
        foreach ($Array_data as $row) {
            fputcsv($output, $row);
        }
        fclose($output);
    }
}
