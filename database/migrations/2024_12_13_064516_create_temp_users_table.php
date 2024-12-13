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
        Schema::create('temp_users', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            $table->string('email')->unique();
            $table->string('no_relawan')->nullable()->unique();
            $table->integer('branch_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('user_detail_id')->constrained()->onDelete('constrained');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_users');
    }
};
