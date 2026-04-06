<?php

namespace App\Filament\Resources\Customers\Tables;

use App\Models\Customer;
use App\Models\Loyality;
use App\Services\LoyaltyService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Validation\ValidationException;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('telegram_user_id')
                    ->label('Telegram User ID')
                    ->searchable(),
                TextColumn::make('telegram_username')
                    ->label('Telegram Username')
                    ->searchable(),
                TextColumn::make('loyalty_points_balance')
                    ->label('Loyalty Points')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tags.visit_behavior')
                    ->label('Visit Behavior Tags')
                    ->badge()
                    ->color('info')
                    ->limitList(2)
                    ->expandableLimitedList(),
                TextColumn::make('tags.order_behavior')
                    ->label('Order Behavior Tags')
                    ->badge()
                    ->color('success')
                    ->limitList(2)
                    ->expandableLimitedList(),
                IconColumn::make('is_blocked')
                    ->boolean()
                    // ->label('Blocked')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('redeem_reward')
                    ->label('Redeem Reward')
                    ->icon('heroicon-m-gift')
                    ->color('success')
                    ->schema([
                        Select::make('loyality_id')
                            ->label('Reward')
                            ->options(function (): array {
                                return Loyality::query()
                                    ->where('is_active', true)
                                    ->orderBy('sort_order')
                                    ->orderBy('name')
                                    ->get()
                                    ->mapWithKeys(fn (Loyality $loyality): array => [
                                        $loyality->id => "{$loyality->name} ({$loyality->points_required} pts)",
                                    ])
                                    ->all();
                            })
                            ->required()
                            ->searchable()
                            ->preload(),
                        Textarea::make('notes')
                            ->rows(3)
                            ->default(null),
                    ])
                    ->action(function (Customer $record, array $data, LoyaltyService $loyaltyService): void {
                        $loyality = Loyality::query()->find((int) ($data['loyality_id'] ?? 0));

                        if (! $loyality) {
                            throw ValidationException::withMessages([
                                'loyality_id' => 'Selected reward could not be found.',
                            ]);
                        }

                        $loyaltyService->redeemReward(
                            customer: $record,
                            loyality: $loyality,
                            preOrder: null,
                            notes: $data['notes'] ?? null,
                        );
                    })
                    ->successNotificationTitle('Reward redeemed successfully.'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
