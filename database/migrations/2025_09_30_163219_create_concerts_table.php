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

            // Core Information
            $table->string('name')->unique(); // Name of the concert
            $table->text('description'); // Description of the concert
            $table->string('event_type')->nullable(); // Type of the event
            $table->string('genre')->nullable(); // Genre of the concert
            $table->text('picture_url'); // Picture URL

            // Location
            $table->string('venue_name')->nullable(); // Name of the Location/Venue
            $table->decimal('latitude', 10, 6)->nullable(); // Latitude
            $table->decimal('longitude', 10, 6)->nullable(); // Longitude

            // Prices
            $table->decimal('price_min', 10)->nullable(); // Minimum ticket price
            $table->decimal('price_max', 10)->nullable(); // Maximum ticket price

            // Dates and Times
            $table->date('start_sale_date')->nullable(); // Start sale date
            $table->date('end_sale_date')->nullable(); // End sale date
            $table->date('start_show_date')->nullable(); // Start date
            $table->time('start_show_time')->nullable(); // Start time
            $table->date('end_show_date')->nullable(); // End date
            $table->time('end_show_time')->nullable(); // End time

            // Additional Information
            $table->text('ticket_link')->nullable(); // Ticket link
            $table->integer('view_count')->nullable()->default(0); // View count
            $table->integer('like_count')->nullable()->default(0); // Like count
            
            //Foreign keys
            $table->foreignId('province_id')->nullable()->constrained('provinces');
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
