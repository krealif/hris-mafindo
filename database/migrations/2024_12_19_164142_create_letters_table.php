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
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->nullable()->constrained('letter_templates')->onDelete('set null');
            $table->foreignId('submitted_by_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('submitted_for_id')->nullable()->constrained('users')->onDelete('set null');
            $table->json('content');
            $table->string('status', 20);
            $table->string('message')->nullable();
            $table->string('file')->nullable();
            $table->string('uploaded_by')->nullable();
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
