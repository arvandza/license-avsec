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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('fullname', 150)->unique();
            $table->string('nip', 50)->unique();
            $table->string('place_of_birth', 50);
            $table->date('date_of_birth');
            $table->enum('education', ['SD', 'SMP', 'SMA/SMK', 'D1', 'D2', 'D3', 'S1', 'S2', 'S3']);
            $table->string('competence')->nullable();
            $table->string('rank', 50);
            $table->enum('position', ['Kanit Avsec', 'Danru 1', 'Danru 2', 'Danru 3', 'Anggota', 'Admin']);
            $table->string('email', 100)->unique();
            $table->string('contact', 15)->unique();
            $table->string('photo_url')->unique()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee');
    }
};
