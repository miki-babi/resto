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
        if (! Schema::hasTable('loyalities')) {
            return;
        }

        Schema::table('loyalities', function (Blueprint $table): void {
            if (! Schema::hasColumn('loyalities', 'name')) {
                $table->string('name')
                    ->default('Reward');
            }

            if (! Schema::hasColumn('loyalities', 'description')) {
                $table->text('description')
                    ->nullable();
            }

            if (! Schema::hasColumn('loyalities', 'points_required')) {
                $table->unsignedInteger('points_required')
                    ->default(1);
            }

            if (! Schema::hasColumn('loyalities', 'is_active')) {
                $table->boolean('is_active')
                    ->default(true);
            }

            if (! Schema::hasColumn('loyalities', 'sort_order')) {
                $table->integer('sort_order')
                    ->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('loyalities')) {
            return;
        }

        Schema::table('loyalities', function (Blueprint $table): void {
            foreach (['sort_order', 'is_active', 'points_required', 'description', 'name'] as $column) {
                if (Schema::hasColumn('loyalities', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
