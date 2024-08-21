<?php

namespace App\Http\Controllers\Api\ParentRequests;

use App\Events\ChatSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\ParentRequest\MessageRequest;
use App\Http\Requests\PaymentRequest\ChatsRequest;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;

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
    }

    //Send Message
    public function sendMessage(MessageRequest $request)
    {
        $data = $request->validated();
        $data['sender'] = auth()->user()->id;

        $chat = Chat::whereIn('sender', [$data['sender'], $data['receiver']])->where('closed', 0)->first();

        if (!$chat) {
            $chat =  Chat::create($data);
        }

        $data['chat_id'] = $chat->id;

        $message =  Message::create($data);
        $receiver = User::find($data['receiver']);
        broadcast(new ChatSent($receiver, $message))->toOthers();
        return contentResponse($message, 'Send Message Successfully');
    }

    public function getChatRequest(string $nursery_id)
    {
        $chats =  Chat::with('message')->with('sender')->where('receiver', $nursery_id)->get();
        return contentResponse($chats, 'Fetch Chats Request Successfully');
    }
}
