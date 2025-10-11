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
        Schema::create('promoter_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promoter_id')->constrained('promoters')->onDelete('cascade'); // Associated promoter
            $table->string('field_name'); // Name of the field that was changed
            $table->text('old_value'); // Previous value
            $table->text('new_value'); // New value
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promoter_logs');
    }
};
