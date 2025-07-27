<?php

namespace App\Http\Controllers;

use App\Enum\NotificationType;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Events\MessageSent;
use App\Models\Chat;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    /**
     * Show the form for creating a new resource.
     */

     public function send(Request $request, Chat $chat)
     {
         $request->validate([
             'message' => 'required|string|max:1000',
         ]);

         $sender = Auth::user();

         // إنشاء الرسالة
         $message = Message::create([
             'chat_id'   => $chat->id, // ✅ هذا هو التعديل
             'sender_id' => $sender->id,
             'message'   => $request->message,
         ]);

         // تحديد المستلم
         $receiverId = ($chat->user_one_id === $sender->id) ? $chat->user_two_id : $chat->user_one_id;
         $receiver = User::findOrFail($receiverId);

         // إرسال الإشعار
         app(NotificationService::class)->sendToUser(
             recipient: $receiver,
             title: trans('New Message'),
             body: trans(':name sent you a message', ['name' => $sender->name]),
             data: [
                 'chat_id'    => $chat->id,
                 'message_id' => $message->id,
             ],
             type: NotificationType::NEW_MESSAGE,
             sender: $sender
         );

         // بث الرسالة في الوقت الحقيقي
         broadcast(new MessageSent($message))->toOthers();

         return response()->json([
             'status'  => 'Message sent and notification dispatched',
             'message' => $message,
         ]);
     }    /**
     * Store a newly created resource in storage.
     */
    public function index($chatId)
    {
        $chat = Chat::with(['messages.sender']) // لو عندك علاقة sender داخل Message
        ->findOrFail($chatId);

    // تحقق إن المستخدم جزء من هذه المحادثة
    if ($chat->user_one_id !== Auth::id() && $chat->user_two_id !== Auth::id()) {
        abort(403, 'Unauthorized');
    }

    return response()->json([
        'chat' => $chat,
        'messages' => $chat->messages
    ]);
    }

public function indexx()
{
    $user = Auth::user();

    $chatsAsUserOne = $user->chatsAsUserOne()->with(['userTwo'])->get();
    $chatsAsUserTwo = $user->chatsAsUserTwo()->with(['userOne'])->get();

    // دمج النتيجتين
    $chats = $chatsAsUserOne->merge($chatsAsUserTwo);

    return response()->json([
        'chats' => $chats
    ]);
}}