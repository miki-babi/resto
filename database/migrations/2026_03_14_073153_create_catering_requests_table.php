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
        Schema::create('catering_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('catering_package_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('contact');

            $table->text('note')->nullable();

            $table->string('status')->default('pending');
            // pending, contacted, confirmed, cancelled

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catering_requests');
    }
};
