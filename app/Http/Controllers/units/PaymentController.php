<?php

namespace App\Http\Controllers\units;

use App\Http\Controllers\Controller;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index() {
        return Payment::get();
    }
}
