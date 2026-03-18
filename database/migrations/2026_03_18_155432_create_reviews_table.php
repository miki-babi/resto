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
       Schema::create('reviews', function (Blueprint $table) {
    $table->id();

    $table->string('reviewer_name');         // name of the reviewer
    $table->text('content');                 // review content
    $table->tinyInteger('stars')->default(5); // 1 to 5 stars
    $table->boolean('is_featured')->default(false); // featured review
    $table->integer('sort_order')->default(0);      // custom display order

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
