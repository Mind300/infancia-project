<?php

namespace App\Http\Controllers\Api\ParentRequests;

use App\Events\ChatSent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function chatForm(string $receiver)
    {
        $sender = auth()->user()->id;

        $chats = Message::where(function ($query) use ($sender, $receiver) {
            $query->where('sender', $sender)
                ->where('receiver', $receiver);
        })->orWhere(function ($query) use ($sender, $receiver) {
            $query->where('sender', $receiver)
                ->where('receiver', $sender);
        })->orderBy('created_at', 'asc')->get();

        return response()->json(['content' => $chats]);
        // return contentResponse($chats, 'Fetch Chats Successfully');
    }

    //Send Message
    public function sendMessage(MessageRequest $request)
    {
        $data = $request->validated();
        $data['sender'] = auth()->user()->id;

        $message =  Message::create($data);
        $receiver = User::find($data['receiver']);

        broadcast(new ChatSent($receiver, $message))->toOthers();
        return contentResponse($message, 'Send Message Successfully');
    }
}
