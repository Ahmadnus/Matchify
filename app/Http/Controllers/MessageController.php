<?php

namespace App\Http\Controllers;

use App\Enum\NotificationType;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Events\MessageSent;
use App\Http\Requests\SendMessageRequest;
use App\Models\Chat;
use App\Models\User;
use App\Services\MessageService;
use App\Services\NotificationService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;


class MessageController extends Controller
{
    use ApiResponse;

    public function __construct(private MessageService $messageService) {}

    public function send(SendMessageRequest $request, Chat $chat)
    {
        $message = $this->messageService->sendMessage($chat, $request->message);

        return $this->successResponse('Message sent and notification dispatched', $message);
    }

    public function index($chatId)
    {
        $chat = Chat::findOrFail($chatId);

        $data = $this->messageService->getMessagesForChat($chat);

        return $this->successResponse('Messages retrieved', $data);
    }

    public function indexx()
    {
        $chats = $this->messageService->getAllUserChats();

        return $this->successResponse('User chats retrieved', ['chats' => $chats]);
    }
}
