<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model {
    protected $fillable = ['title', 'user_id', 'admin_id'];

    public function messages() {
        return $this->hasMany(Ticketmessage::class, 'ticket_id');
    }
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
