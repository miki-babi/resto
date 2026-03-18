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
        Schema::create('faqs', function (Blueprint $table) {
    $table->id();

    // Core content
    $table->string('question');          
    $table->text('answer');              

    // URL + indexing
    $table->string('slug')->unique();    

    // SEO fields
    $table->string('meta_title')->nullable();      
    $table->text('meta_description')->nullable();  

    // Control
    $table->boolean('is_active')->default(true);
    $table->integer('sort_order')->default(0);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
