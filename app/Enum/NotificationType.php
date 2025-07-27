<?php

namespace App\Enum;

enum NotificationType: string
{
  // طلبات المواعدة
  case NEW_DATE_REQUEST = 'Notif-1';
  case DATE_REQUEST_ACCEPTED = 'Notif-2';
  case DATE_REQUEST_DECLINED = 'Notif-3';

  // رسائل ومحادثات
  case NEW_MESSAGE = 'Notif-4';

  // إعجابات ومتابعات
  case NEW_LIKE = 'Notif-5';
  case NEW_MATCH = 'Notif-6';
  case NEW_FOLLOWER = 'Notif-7';

  // تنبيهات الأمان أو الإعدادات
  case ACCOUNT_REPORTED = 'Notif-8';
  case ACCOUNT_BANNED = 'Notif-9';
  case PROFILE_VERIFIED = 'Notif-10';

  // أحداث عامة
  case GENERAL_ANNOUNCEMENT = 'Notif-11';

  // إشعارات النشاط
  case SOMEONE_VIEWED_YOUR_PROFILE = 'Notif-12';
  case SOMEONE_UNMATCHED_YOU = 'Notif-13';

  // إشعارات التوافق
  case COMPATIBLE_MATCH_FOUND = 'Notif-14';

  // إشعارات الأحداث أو العروض
  case NEW_EVENT_NEARBY = 'Notif-15';
  case PROMOTION_AVAILABLE = 'Notif-16';
}
