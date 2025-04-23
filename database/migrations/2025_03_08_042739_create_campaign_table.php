<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('campaign', function (Blueprint $table) {
            $table->id();
            $table->json('vendor_id')->nullable(); // Prefer JSON for multi IDs
            $table->json('fieldagent_id')->nullable(); // Prefer JSON for multi IDs
            $table->string('campaign_name'); 
            $table->json('images')->nullable();
            $table->string('pdf')->nullable();
            $table->text('description')->nullable();
            $table->date('start_date'); // Set default date
            $table->date('end_date'); // Set default date
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign');
    }
};
