<?php

namespace App\Http\Controllers\ticket;

use App\Http\Controllers\Controller;
use App\Http\Controllers\multimedia\PictureController;
use App\Models\NotseenTK;
use App\Models\Ticket;
use App\Models\Ticketmessage;
use App\Models\User;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        return Ticket::with(['messages', 'messages.picture', 'user'])->orderBy('updated_at', 'DESC')->get();
    }

    public function show($parent_id)
    {
        return Ticket::where('id', $parent_id)->with(['messages', 'messages.picture', 'user'])->first();
    }

    public function getTickets(Request $request)
    {
        $api_token = $request->bearerToken();
        // 'api_ip' => 
        $user = User::where(['api_token' => $api_token])->first();
        $tickets = Ticket::where('user_id', $user->id)->with(['messages', 'messages.picture'])->orderBy('updated_at', 'DESC')->get();
        
        foreach($tickets as $tk){
        foreach($tk->messages as $msg){
            if($msg->is_response)
            NotseenTK::firstOrCreate([
                'user_id' => $user->id,
                'ticket_id' => $msg->id
            ]);
        }
        }
        return $tickets;
    }

    public function createTicket(Request $request)
    {
        $api_token = $request->bearerToken();
        // 'api_ip' => 
        $user = User::where(['api_token' => $api_token])->first();
        $newTicket = Ticket::create([
            'title' => $request->title,
            'user_id' =>  $user->id,
        ]);

        if ($newTicket)
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function sendMessage(Request $request)
    {
        $ticket_id = $request->parent_id;
        $ticketMessage = Ticketmessage::create([
            'text' => $request->text,
            'ticket_id' => $ticket_id,
        ]);
        if (isset($request->mediafile)) {
            $mediaRequest = clone $request;
            $mediaRequest->replace([
                'id' => $ticketMessage->id,
                'type' => 'App\Models\Ticketmessage',
                'mediafile' => $request->mediafile,
            ]);
            return PictureController::upload($mediaRequest);
        }
        if ($ticketMessage)
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function receiveMessage(Request $request)
    {
        $ticket_id = $request->parent_id;
        $api_token = $request->bearerToken();
        // 'api_ip' => 
        $admin = User::where(['api_token' => $api_token])->first();
        if ($admin)
            Ticket::where('id', $ticket_id)->update(['admin_id' => $admin->id]);
        $ticketMessage = Ticketmessage::create([
            'text' => $request->text,
            'ticket_id' => $ticket_id,
            'is_response' => 1,
        ]);
        if (isset($request->mediafile)) {
            $mediaRequest = clone $request;
            $mediaRequest->replace([
                'id' => $ticketMessage->id,
                'type' => 'App\Models\Ticketmessage',
                'mediafile' => $request->mediafile,
            ]);
            return PictureController::upload($mediaRequest);
        }
        if ($ticketMessage)
            return json_encode(['msg' => 'system_success']);
        else return json_encode(['msg' => 'system_error']);
    }

    public function getTicketsNotseen(Request $request) {

        $api_token = $request->bearerToken();
        // 'api_ip' => 
        $user = User::where(['api_token' => $api_token])->first();
        $notseens = NotseenTK::where('user_id', $user->id)->get()->pluck('ticket_id')->toArray();
        $tickets = Ticket::where('user_id', $user->id)->with(['messages' => function($q) use ($notseens){
            $q->where('is_response', 1)->whereNotIn('id', $notseens);
        }, 'messages.picture'])->orderBy('updated_at', 'DESC')->get();
        $i = 0;
        foreach ($tickets as $tk) {
            foreach ($tk->messages as $msg) {
                $i++;
            }
        }
        return json_encode(['msg' => 'system_success', 'result' => $i]);
       
    }
}
