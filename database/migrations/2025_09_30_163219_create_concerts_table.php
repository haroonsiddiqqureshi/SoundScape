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
            $table->string('name'); // Name of the concert
            $table->text('description'); // Description of the concert
            $table->json('artists')->nullable(); // List of artists as JSON
            $table->string('genre')->nullable(); // Genre of the concert
            $table->string('status')->default('Scheduled'); // Status of the concert
            $table->string('venue_name'); // Name of the Location/Venue
            $table->string('city'); // City where the concert is held
            $table->decimal('price', 10, 2)->nullable(); // Ticket price
            $table->dateTime('start_datetime'); // Start date and time
            $table->dateTime('end_datetime')->nullable(); // End date and time
            $table->decimal('latitude', 10, 6)->nullable(); // Latitude
            $table->decimal('longitude', 10, 6)->nullable(); // Longitude
            $table->text('picture_url'); // Picture URL
            $table->text('ticket_link')->nullable(); // Ticket link
            $table->integer('view_count')->nullable()->default(0); // View count
            $table->integer('like_count')->nullable()->default(0); // Like count
            $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('cascade'); // Admin who created the concert
            $table->foreignId('promoter_id')->nullable()->constrained('promoters')->onDelete('cascade'); // Promoter who created the concert
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
