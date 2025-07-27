<?php

namespace App\Services;

use App\Enum\NotificationType as EnumNotificationType;
use App\Enums\NotificationType;
use App\Models\Notification as ModelNotification;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use Kreait\Laravel\Firebase\Facades\Firebase;




class NotificationService
{
    protected $messaging;

    public function __construct()
    {
        $this->messaging = Firebase::messaging();
    }

    /**
     * إرسال إشعار إلى مستخدم محدد
     */
    public function sendToUser(
        User $recipient,
        string $title,
        string $body,
        array $data = [],
        EnumNotificationType $type = EnumNotificationType::NEW_DATE_REQUEST,
        ?User $sender = null
    ): void {
        // الحصول على توكنات المستخدم
        $tokens = $recipient->fcmTokens()->pluck('token')->toArray();

        if (empty($tokens)) {
            Log::info("📭 No FCM tokens found for user #{$recipient->id}");
        } else {
            // إرسال عبر FCM
            $firebaseNotification = FirebaseNotification::create($title, $body);
            $message = CloudMessage::new()
                ->withNotification($firebaseNotification)
                ->withData([
                    'type' => $type->value,
                    'extra' => json_encode($data),
                ]);

            try {
                $this->messaging->sendMulticast($message, $tokens);
                Log::info("✅ Notification sent to user #{$recipient->id}");
            } catch (\Throwable $e) {
                Log::error("❌ Error sending notification to user #{$recipient->id}: " . $e->getMessage());
            }
        }

        // تخزين الإشعار بقاعدة البيانات
        ModelNotification::create([
            'user_id'  => $recipient->id,
            'sender_id' => $sender?->id,
            'title'    => $title,
            'body'     => $body,
            'type'     => $type->value,
            'data' => json_encode($data), // ✅ التحويل إلى JSON هنا
        ]);
    }
}