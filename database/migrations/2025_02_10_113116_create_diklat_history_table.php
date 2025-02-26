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
        Schema::create('diklat_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('license_id')->constrained('license')->onDelete('cascade');
            $table->string('notes', 150)->nullable();
            $table->enum('result', ['GRADUATED', 'UNGRADUATED'])->nullable();
            $table->enum('status', ['ON GOING', 'DONE']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diklat_history');
    }
};
