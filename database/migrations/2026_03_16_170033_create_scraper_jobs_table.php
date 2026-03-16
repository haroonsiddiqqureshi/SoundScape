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
        Schema::create('scraper_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('target_type');
            $table->string('target_website')->nullable();
            $table->string('status')->default('running');
            $table->integer('progress')->default(0);
            $table->json('results')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scraper_jobs');
    }
};
