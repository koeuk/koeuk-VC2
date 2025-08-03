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
        Schema::create('fixing_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fixer_id');
            $table->unsignedBigInteger('booking_id');        
            $table->string('action')->default('progress');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixing_progress');
    }
};
