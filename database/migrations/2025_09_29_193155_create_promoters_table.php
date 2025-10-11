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
        Schema::create('promoters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade'); // Link to users table
            $table->string('fullname'); // Full name of the promoter
            $table->string('business_name'); // Name of the business
            $table->string('business_category'); // Category of the business
            $table->text('business_address'); // Address of the business
            $table->json('social_links')->nullable(); // Social media links
            $table->string('bio', 500)->nullable(); // Short biography
            $table->boolean('is_verified')->default(false); // Verification status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promoters');
    }
};
