<?php

namespace App\Http\Controllers\User;

use App\Events\MessageEvent;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ShopProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMessageController extends Controller
{

    public function sendMessage(Request $request)
    {
        $senderID = Auth::id();

        $request->validate([
            "receiver_id" => "required|integer|exists:users,id|different:" . $senderID,
            "message_content" => "required|string",
        ]);

        $receiverID = (int) $request->receiver_id;
        $messageContent = trim((string) $request->message_content);
        if ($messageContent === '') {
            return response([
                "status" => "error",
                "message" => "Please type a message before sending."
            ], 422);
        }

        $isNewConversation =  count(Chat::whereIn('receiver_id', [$senderID, $receiverID])
            ->whereIn('sender_id', [$senderID, $receiverID])
            ->get()) <= 0 ? true : false;

        $message = Chat::create([
            "receiver_id" => $receiverID,
            "sender_id" => $senderID,
            "message" => $messageContent,
        ]);

        broadcast(new MessageEvent($message->message, $senderID, $receiverID, $message->created_at));
        if (Auth::user()->role == 'user') {
            return response([
                "status" => "success",
                "receiver" => ShopProfile::where("user_id", $request->receiver_id)->first(),
                "isNewConversation" => $isNewConversation,
            ]);
        } else {
            return response([
                "status" => "success",
            ]);
        }
    }
    public function getMessage(Request $request)
    {
        $senderID = $request->sender_id;
        $receiverID = $request->receiver_id;
        $receiverName = User::find($receiverID)->name;
        $chat = Chat::whereIn('receiver_id', [$senderID, $receiverID])
            ->whereIn('sender_id', [$senderID, $receiverID])
            ->orderBy('created_at', 'asc')
            ->get();
        // Set seen for all previous message
        foreach ($chat as $c) $c->update(['seen' => 1]);
        $lastChat = $chat->last();

        return response([
            'status' => "success",
            "chat" => $chat,
            "lastChat" => $lastChat,
            "receiverName" => $receiverName,
        ]);
    }
}
