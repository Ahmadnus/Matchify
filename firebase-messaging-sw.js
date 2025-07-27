importScripts("https://www.gstatic.com/firebasejs/11.8.1/firebase-app-compat.js");
importScripts("https://www.gstatic.com/firebasejs/11.8.1/firebase-messaging-compat.js");

// إعداد Firebase داخل الـ Service Worker
const firebaseConfig = {
    apiKey: "AIzaSyBWRxyjHmjKYawxKc76MIQepbgRQKEDvS8",
    authDomain: "date-app-9713b.firebaseapp.com",
    projectId: "date-app-9713b",
    storageBucket: "date-app-9713b.firebasestorage.app",
    messagingSenderId: "960292577912",
    appId: "1:960292577912:web:95d4a33f408f24713e838d"
  };


// تفعيل خدمة Firebase Messaging
const messaging = firebase.messaging();

// الاستماع للإشعار عند وصوله في الخلفية
messaging.onBackgroundMessage(function(payload) {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);

  const notificationTitle = payload.notification?.title || 'New Notification';
  const notificationOptions = {
    body: payload.notification?.body || '',
    icon: '/icon.png' // أيقونة الإشعار (اختياري)
  };

  self.registration.showNotification(notificationTitle, notificationOptions);
});
