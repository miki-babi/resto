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
        if (! Schema::hasTable('menu_item_order_items')) {
            return;
        }

        Schema::table('menu_item_order_items', function (Blueprint $table) {
            if (! Schema::hasColumn('menu_item_order_items', 'quantity')) {
                $table->integer('quantity')->default(1);
            }

            if (! Schema::hasColumn('menu_item_order_items', 'price')) {
                $table->decimal('price', 10, 2)->default(0);
            }

            if (! Schema::hasColumn('menu_item_order_items', 'selected_variant_id')) {
                $table->unsignedBigInteger('selected_variant_id')->nullable();
            }

            if (! Schema::hasColumn('menu_item_order_items', 'selected_variant_name')) {
                $table->string('selected_variant_name')->nullable();
            }

            if (! Schema::hasColumn('menu_item_order_items', 'selected_variant_price')) {
                $table->decimal('selected_variant_price', 10, 2)->nullable();
            }

            if (! Schema::hasColumn('menu_item_order_items', 'addons_unit_price')) {
                $table->decimal('addons_unit_price', 10, 2)->default(0);
            }

            if (! Schema::hasColumn('menu_item_order_items', 'selected_addons')) {
                $table->json('selected_addons')->nullable();
            }

            if (! Schema::hasColumn('menu_item_order_items', 'line_total_price')) {
                $table->decimal('line_total_price', 10, 2)->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('menu_item_order_items')) {
            return;
        }

        Schema::table('menu_item_order_items', function (Blueprint $table) {
            if (Schema::hasColumn('menu_item_order_items', 'line_total_price')) {
                $table->dropColumn('line_total_price');
            }

            if (Schema::hasColumn('menu_item_order_items', 'selected_addons')) {
                $table->dropColumn('selected_addons');
            }

            if (Schema::hasColumn('menu_item_order_items', 'addons_unit_price')) {
                $table->dropColumn('addons_unit_price');
            }

            if (Schema::hasColumn('menu_item_order_items', 'selected_variant_price')) {
                $table->dropColumn('selected_variant_price');
            }

            if (Schema::hasColumn('menu_item_order_items', 'selected_variant_name')) {
                $table->dropColumn('selected_variant_name');
            }

            if (Schema::hasColumn('menu_item_order_items', 'selected_variant_id')) {
                $table->dropColumn('selected_variant_id');
            }
        });
    }
};
