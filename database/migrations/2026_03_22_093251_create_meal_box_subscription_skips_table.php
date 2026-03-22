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
        Schema::create('meal_box_subscription_skips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meal_box_subscription_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->date('skip_date');

            $table->timestamps();

            // keep index name under MySQL identifier length limit
            $table->unique(
                ['meal_box_subscription_id', 'skip_date'],
                'mbss_subscription_date_unique'
            ); // prevent duplicates
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_box_subscription_skips');
    }
};
