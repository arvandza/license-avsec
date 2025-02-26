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
        Schema::create('license', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->date('end_date');
            $table->enum('license_type', ['BASIC AVSEC', 'JUNIOR AVSEC', 'SENIOR AVSEC']);
            $table->string('license_number', 100);
            $table->string('notes', 150)->nullable();
            $table->enum('license_status', ['ACTIVE', 'INACTIVE']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekuren_log');
    }
};
