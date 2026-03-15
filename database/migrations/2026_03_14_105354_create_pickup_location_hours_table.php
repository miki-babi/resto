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
        Schema::create('pickup_location_hours', function (Blueprint $table) {
    $table->id();

    $table->foreignId('pickup_location_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->tinyInteger('day_of_week'); 
    // 0 = Sunday, 6 = Saturday

    $table->tinyInteger('hour_slot'); 
    // Ethiopian hour (1–12)

    $table->enum('period', ['day', 'night']); 
    // day = 6am–6pm, night = 6pm–6am

    $table->boolean('is_active')->default(true);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pickup_location_hours');
    }
};
