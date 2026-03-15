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
        Schema::create('feedback_sms_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Default Review Request"
            $table->text('content'); // e.g., "Hi! Please leave a review: {link}"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback_sms_templates');
    }
};
