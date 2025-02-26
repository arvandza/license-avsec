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
        Schema::create('rekuren_schedule', function (Blueprint $table) {
            $table->id();
            $table->foreignId('license_id')->constrained('license')->onDelete('cascade');
            $table->date('date');
            $table->enum('status', ['ON GOING', 'DONE']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekuren_schedule');
    }
};
