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
        Schema::create('highlights', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable(); // Title of the highlight
            $table->string('picture_url'); // URL to the highlight picture
            $table->text('description')->nullable(); // Description of the highlight
            $table->string('link')->nullable(); // e.g., YouTube or Vimeo link
            $table->foreignId('concert_id')->nullable()->constrained('concerts')->onDelete('cascade'); // Associated concert
            $table->boolean('is_active')->default(false); // Active status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('highlights');
    }
};
