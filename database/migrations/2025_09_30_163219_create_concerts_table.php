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
        Schema::create('concerts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('status')->default('Scheduled');
            $table->string('venue_name');
            $table->string('city');
            $table->decimal('latitude', 10, 6);
            $table->decimal('longitude', 10, 6);
            $table->dateTime('concert_datetime');
            $table->decimal('price', 10, 2);
            $table->text('picture_url');
            $table->text('ticket_link')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('cascade');
            $table->foreignId('promoter_id')->nullable()->constrained('promoters')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concerts');
    }
};
