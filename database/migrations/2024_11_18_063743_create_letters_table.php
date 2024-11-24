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
        Schema::create('letter_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('view')->unique();
            $table->timestamps();
        });

        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('letter_template_id')->constrained();
            $table->json('contents');
            $table->string('status', 10);
            $table->string('file')->nullable();
            $table->string('admin')->nullable();
            $table->string('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letters');
        Schema::dropIfExists('submission_letters');
    }
};
