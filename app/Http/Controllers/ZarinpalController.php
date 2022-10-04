<?php

namespace App\Http\Controllers;

use App\Models\Invited;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Takhfif;
use App\Models\Ticket;
use App\Models\Ticketmessage;
use App\Models\UsedTakhfif;
use App\Models\User;
use Illuminate\Http\Request;

class ZarinpalController extends Controller
{
    public function createPayment(Request $request)
    {
        $api_token = $request->bearerToken();
        $user = User::where(['api_token' => $api_token])->first();
        $Payment = Payment::find($request->payment_id);
        $curl = curl_init("https://api.zarinpal.com/pg/v4/payment/request.json");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, [
            'merchant_id' => $Payment->merchant,
            'currency' =>  env("ZARINPAL_CURRENCY"),
            'amount' => $request->amount,
            'callback_url' => "https://drmollaii.ir/v1/public/zarinpal/paymentresponse?amount=$request->amount&orderable_id=$request->orderable_id&orderable_type=$request->orderable_type&user_id=$user->id&merchant=$Payment->merchant&code=$request->code",
            'description' => $request->description,
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($response, true);
        if ($result['data'] && $result['data']['message'] == 'Success')
            return "https://www.zarinpal.com/pg/StartPay/" . $result['data']['authority'];
        else return "error";
    }

    public function paymentResponse(Request $request)
    {
        $Status = $request->Status;
        $Authority = $request->Authority;
        if ($Status == 'OK')
        return $this->createSuccess($Authority, $request->amount, $request->orderable_id, $request->orderable_type, $request->user_id, $request->merchant, $request->code);
        else return $this->createFailed();
    }

    public function createSuccess($Authority, $amount, $orderable_id, $orderable_type, $user_id, $merchant, $code)
    {
       
        $user= User::find($user_id);
        $curl = curl_init("https://api.zarinpal.com/pg/v4/payment/verify.json");

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, [
            'merchant_id' => $merchant,
            'amount' => $amount,
            'authority' => $Authority,
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($response, true);
        
       

        if ($result['data']) {
            $tget = Takhfif::where('code', $code)->first();
            if($tget)
            UsedTakhfif::create([
               'user_id' => $user_id,
               'takhfif_id' => $tget->id,
           ]);
               $this->makeTakhfif($user->phonenumber);
            if ($result['data']['message'] == 'Verified' || $result['data']['message'] == 'Paid') {
                Order::create([
                    'orderable_id' => $orderable_id,
                    'orderable_type' => "App\\Models\\".$orderable_type,
                    'user_id' => $user_id,
                    'ref_id' => $result['data']['ref_id'],
                ]);
               return file_get_contents('success.html');

            } else return file_get_contents('success.html');
        }
    }

 

    public function createFailed()
    {
       
        return file_get_contents('failed.html');
    }


    
    public function makeTakhfif($phonenumber) {
        $inviter = Invited::where('phonenumber', $phonenumber)->first();
        if($inviter) {
            $Takhfif = Takhfif::create([
                'user_id' => $inviter->user_id,
                'percent' => '30',
                'count' => 1,
                'code' => substr(md5(microtime()),rand(0,26),5),
                'expire_date' => \Carbon\Carbon::now()->addDays(5),
            ]);

            $ticketcat = Ticket::create([
                'title' => "کد تخفیف دعوت $phonenumber",
                'user_id' => $inviter->user_id,
                'admin_id' => 1,
            ]);

            Ticketmessage::create([
                'ticket_id' => $ticketcat->id,
                'is_response' => 1,
                'text' => "با تشکر از همکاری شما، کد تخفیف ۳۰ درصدی $Takhfif->code برای خرید با مهلت استفاده ۵ روز تقدیم می گردد",
            ]);
            Invited::where('phonenumber', $phonenumber)->delete();
        }
        else
            return false;
    }
}
