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
    Schema::create('blocks', function (Blueprint $table) {
        $table->id();

        // اللي عمل الحظر
        $table->foreignId('blocker_id')->constrained('users')->onDelete('cascade');

        // المستخدم اللي تم حظره
        $table->foreignId('blocked_id')->constrained('users')->onDelete('cascade');

        $table->timestamps();

        // نمنع تكرار الحظر لنفس الزوج
        $table->unique(['blocker_id', 'blocked_id']);
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocks');
    }
};
