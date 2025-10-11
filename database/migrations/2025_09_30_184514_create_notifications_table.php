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
            $table->foreignId('follow_id')->constrained('follows')->onDelete('cascade'); // Associated follow
            $table->foreignId('concert_log_id')->nullable()->constrained('concert_logs')->onDelete('cascade'); // Associated concert log
            $table->text('message'); // Notification message
            $table->json('notify_platforms')->nullable(); // Platforms to notify (e.g., email, SMS)
            $table->boolean('is_sent')->default(false)->index(); // Sent status
            $table->timestamp('sent_at')->nullable(); // when it was actually sent
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
