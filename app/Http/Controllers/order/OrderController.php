<?php

namespace App\Http\Controllers\order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function insertOrder(Request $request ,$orderable_id, $orderable_type, $user_id)
    {
        if($user_id)
        $order = Order::create([
            'orderable_id' => $orderable_id,
            'orderable_type' => $orderable_type,
            'user_id' => $user_id,
        ]);

        if ($order)
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }
}
