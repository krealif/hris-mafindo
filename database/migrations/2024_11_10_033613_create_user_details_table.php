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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('panggilan')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('agama', 20)->nullable();
            $table->string('disabilitas')->nullable();
            $table->string('no_wa')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('alamat')->nullable();
            $table->string('bidang_keahlian')->nullable();
            $table->string('bidang_mafindo')->nullable();
            $table->year('thn_bergabung')->nullable();
            $table->integer('pdr')->nullable();
            $table->json('medsos')->nullable();
            $table->json('pendidikan')->nullable();
            $table->json('pekerjaan')->nullable();
            $table->json('sertifikat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
