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
        Schema::create('imageuploadcamp', function (Blueprint $table) {
            $table->unsignedBigInteger('campaign_id')->nullable();
            $table->id();
            $table->unsignedBigInteger('fieldagent_id')->nullable();
            $table->string('image');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longtitude', 10, 7);
            $table->date('date')->default(DB::raw('CURRENT_DATE')); // Set default date
            $table->timestamps();
            // Foreign Key Constraint
            $table->foreign('campaign_id')
            ->references('id')
            ->on('campaign')->onDelete('cascade')->onUpdate('cascade');
            
            $table->foreign('fieldagent_id')
            ->references('id')
            ->on('fieldagent')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imageuploadcamp');
    }
};
