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
    Schema::create('reports', function (Blueprint $table) {
        $table->id();

        // المستخدم اللي قدم البلاغ
        $table->foreignId('reporter_id')->constrained('users')->onDelete('cascade');

        // المستخدم اللي جاي عليه البلاغ
        $table->foreignId('reported_id')->constrained('users')->onDelete('cascade');

        // سبب البلاغ أو وصف المشكلة
        $table->text('reason');

        // حالة البلاغ (مفتوح، مقفل، مرفوض، مقبول)
        $table->enum('status', ['open', 'closed', 'rejected', 'accepted'])->default('open');

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
