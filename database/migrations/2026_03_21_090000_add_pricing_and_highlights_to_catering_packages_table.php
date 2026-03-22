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
        Schema::table('catering_packages', function (Blueprint $table) {
            $table->decimal('price_per_person', 10, 2)->nullable()->after('min_guests');
            $table->decimal('price_total', 12, 2)->nullable()->after('price_per_person');
            $table->string('badge_text')->nullable()->after('price_total');
            $table->string('badge_variant')->nullable()->after('badge_text');
            $table->json('highlights')->nullable()->after('badge_variant');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catering_packages', function (Blueprint $table) {
            $table->dropColumn([
                'price_per_person',
                'price_total',
                'badge_text',
                'badge_variant',
                'highlights',
            ]);
        });
    }
};
