<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeitnerBox extends Model {
    protected $table = "leitnerbox";
    protected $fillable = ['user_id', 'flashcard_id', 'level'];

    public function flashcard() {
        return $this->belongsTo(Flashcard::class, 'flashcard_id');
    }

    protected function serializeDate($date)
{
    return \Carbon\Carbon::parse($date)->toDateTimeString();;
}
}
