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
        Schema::create('concert_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('cascade'); // Admin who made the change
            $table->foreignId('promoter_id')->nullable()->constrained('promoters')->onDelete('cascade'); // Promoter who made the change
            $table->foreignId('concert_id')->constrained('concerts')->onDelete('cascade'); // Associated concert
            $table->string('field_name'); // Name of the field that was changed
            $table->text('old_value')->nullable(); // Previous value
            $table->text('new_value')->nullable(); // New value
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concert_logs');
    }
};
