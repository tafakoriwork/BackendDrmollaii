<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticketmessage extends Model {
    protected $fillable = ['text', 'ticket_id', 'is_response'];

    public function picture()
    {
        return $this->morphOne(Picture::class, 'picturable');
    }
}
