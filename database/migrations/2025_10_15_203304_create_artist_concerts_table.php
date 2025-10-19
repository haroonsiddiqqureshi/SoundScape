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
        Schema::create('artist_concerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artist_id')->constrained('artists')->onDelete('cascade'); // Foreign key to artists table
            $table->foreignId('concert_id')->constrained('concerts')->onDelete('cascade'); // Foreign key to concerts table
            $table->unique(['artist_id', 'concert_id']); // Ensure unique pairs
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artist__concerts');
    }
};
