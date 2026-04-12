<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryItem extends Model
{
    protected $fillable = [
        'delivery_id',
        'menu_item_id',
        'menu_item_title',
        'selected_variant_id',
        'selected_variant_name',
        'selected_variant_price',
        'quantity',
        'price',
        'addons_unit_price',
        'selected_addons',
        'line_total_price',
    ];

    protected $casts = [
        'selected_addons' => 'array',
        'selected_variant_price' => 'decimal:2',
        'price' => 'decimal:2',
        'addons_unit_price' => 'decimal:2',
        'line_total_price' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $deliveryItem): void {
            $deliveryItem->syncMenuItemSnapshot();
            $deliveryItem->syncLineTotalPrice();
        });
    }

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(Delivery::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'menu_item_id');
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(MenuItemVariant::class, 'selected_variant_id');
    }

    private function syncMenuItemSnapshot(): void
    {
        if (! $this->isDirty('menu_item_id') && trim((string) $this->menu_item_title) !== '') {
            return;
        }

        $menuItemId = $this->menu_item_id;

        if (! $menuItemId) {
            return;
        }

        $menuItemTitle = $this->relationLoaded('item')
            ? $this->item?->title
            : MenuItem::query()->whereKey($menuItemId)->value('title');

        if ($menuItemTitle === null) {
            return;
        }

        $this->menu_item_title = $menuItemTitle;
    }

    private function syncLineTotalPrice(): void
    {
        if (
            ! $this->isDirty('quantity')
            && ! $this->isDirty('price')
            && ! $this->isDirty('addons_unit_price')
            && $this->line_total_price !== null
        ) {
            return;
        }

        $quantity = (int) ($this->quantity ?? 0);
        $unitPrice = (float) ($this->price ?? 0);
        $addonsUnitPrice = (float) ($this->addons_unit_price ?? 0);

        $this->line_total_price = round(($unitPrice + $addonsUnitPrice) * $quantity, 2);
    }
}
