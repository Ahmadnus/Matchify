<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('notifications', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // المستلم
        $table->foreignId('sender_id')->nullable()->constrained('users')->onDelete('set null'); // المرسل (اختياري)
        $table->string('title');
        $table->text('body');
        $table->string('type')->nullable(); // Enum ممكن تستخدمه لاحقًا
        $table->json('data')->nullable(); // بيانات إضافية (chat_id, redirect, ...etc)
        $table->boolean('is_read')->default(false); // لمعرفة إذا تم فتحه
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
