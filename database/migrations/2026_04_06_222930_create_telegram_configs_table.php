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
        Schema::create('telegram_configs', function (Blueprint $table) {
            $table->id();
            $table->string('bot_token');
            $table->string('miniapp_url')->nullable();
            $table->text('start_message')->nullable();
            $table->text('help_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_configs');
    }
};
