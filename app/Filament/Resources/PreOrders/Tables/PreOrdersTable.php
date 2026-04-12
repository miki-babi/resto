<?php

namespace App\Filament\Resources\PreOrders\Tables;

use App\Models\Loyality;
use App\Models\PreOrder;
use App\Services\LoyaltyService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Validation\ValidationException;

class PreOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('Order #')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('source_type')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'menu' => 'Menu',
                        'cake' => 'Cake',
                        default => 'Unknown',
                    })->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable(),
                TextColumn::make('items_summary')
                    ->label('Items')
                    ->html()
                    ->formatStateUsing(function ($state): string {
                        if (is_string($state)) {
                            $decoded = json_decode($state, true);
                            $state = is_array($decoded) ? $decoded : [];
                        }

                        if (! is_array($state)) {
                            $state = [];
                        }

                        // Handle both formats:
                        // 1) array of item objects (new format)
                        // 2) single item object (legacy format)
                        if (
                            array_key_exists('name', $state)
                            || array_key_exists('quantity', $state)
                            || array_key_exists('item_id', $state)
                        ) {
                            $state = [$state];
                        }

                        return collect($state)
                            ->map(function ($item): string {
                                if (! is_array($item)) {
                                    return '';
                                }

                                $name = trim((string) ($item['name'] ?? $item['title'] ?? 'Item'));
                                $quantity = (int) ($item['quantity'] ?? $item['qty'] ?? 0);
                                $imageUrl = trim((string) ($item['image_url'] ?? ''));

                                if ($name === '') {
                                    $name = 'Item';
                                }

                                $imageHtml = '';
                                if ($imageUrl !== '') {
                                    $imageHtml = '<img src="'.e($imageUrl).'" alt="'.e($name).'" style="width:36px;height:36px;border-radius:8px;object-fit:cover;flex-shrink:0;" />';
                                } else {
                                    $imageHtml = '<span style="display:inline-flex;width:36px;height:36px;border-radius:8px;background:#f1f5f9;color:#64748b;align-items:center;justify-content:center;font-size:11px;flex-shrink:0;">N/A</span>';
                                }

                                return '<div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;min-width:220px;">'
                                    .$imageHtml
                                    .'<span style="line-height:1.2;">'.e(trim($quantity.'x '.$name)).'</span>'
                                    .'</div>';
                            })
                            ->filter()
                            ->implode('');
                    })
                    ->toggleable(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('pickupLocation.name')
                    ->label('Pickup Location')
                    ->searchable(),
                TextColumn::make('pickup_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('pickup_time')
                    ->time('H:i')
                    ->sortable(),
                TextColumn::make('total_price')
                    ->money()
                    ->sortable(),
                TextColumn::make('loyalty_points_earned')
                    ->label('Points Earned')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('loyalty_points_applied')
                    ->label('Points Applied')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Yes' : 'No')
                    ->color(fn (bool $state): string => $state ? 'success' : 'gray'),
                TextColumn::make('customer.loyalty_points_balance')
                    ->label('Customer Points')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('payment_status')
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                // Action::make('redeem_reward')
                //     ->label('Redeem Reward')
                //     ->icon('heroicon-m-gift')
                //     ->color('success')
                //     ->visible(fn (PreOrder $record): bool => (int) ($record->customer_id ?? 0) > 0)
                //     ->schema([
                //         Select::make('loyality_id')
                //             ->label('Reward')
                //             ->options(function (): array {
                //                 return Loyality::query()
                //                     ->where('is_active', true)
                //                     ->orderBy('sort_order')
                //                     ->orderBy('name')
                //                     ->get()
                //                     ->mapWithKeys(fn (Loyality $loyality): array => [
                //                         $loyality->id => "{$loyality->name} ({$loyality->points_required} pts)",
                //                     ])
                //                     ->all();
                //             })
                //             ->required()
                //             ->searchable()
                //             ->preload(),
                //         Textarea::make('notes')
                //             ->rows(3)
                //             ->default(null),
                //     ])
                //     ->action(function (PreOrder $record, array $data, LoyaltyService $loyaltyService): void {
                //         $customer = $record->customer;

                //         if (! $customer) {
                //             throw ValidationException::withMessages([
                //                 'loyality_id' => 'This preorder has no customer.',
                //             ]);
                //         }

                //         $loyality = Loyality::query()->find((int) ($data['loyality_id'] ?? 0));

                //         if (! $loyality) {
                //             throw ValidationException::withMessages([
                //                 'loyality_id' => 'Selected reward could not be found.',
                //             ]);
                //         }

                //         $loyaltyService->redeemReward(
                //             customer: $customer,
                //             loyality: $loyality,
                //             preOrder: $record,
                //             notes: $data['notes'] ?? null,
                //         );
                //     })
                //     ->successNotificationTitle('Reward redeemed successfully.'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
