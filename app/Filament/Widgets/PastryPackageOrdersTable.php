<?php

namespace App\Filament\Widgets;

use App\Models\PastryPackageOrder;
use Filament\Actions\BulkActionGroup;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

// use Illuminate\Database\Eloquent\Builder;

class PastryPackageOrdersTable extends TableWidget
{
    protected static ?int $sort = 3; // Stats firstprotected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        // The widget will only render if there is at least one record in the database
        // return \App\Models\CateringRequest::exists();
        return false;
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Requests'),
            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending')),
            'confirmed' => Tab::make('Confirmed')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'confirmed')),
            'completed' => Tab::make('Completed')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'completed')),
        ];
    }

    public function table(Table $table): Table
    {

        return $table
            ->query(fn (): Builder => PastryPackageOrder::query())
            ->columns([
                TextColumn::make('pastry_customer_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('package.name')
                    ->label('Package')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_price')
                    ->money()
                    ->sortable(),
                TextColumn::make('order_type')
                    ->badge(),
                TextColumn::make('pickup_location_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('pickup_day_of_week')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('pickup_hour_slot')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('pickup_period')
                    ->badge(),
                TextColumn::make('delivery_phone')
                    ->searchable(),
                TextColumn::make('delivery_address')
                    ->searchable(),
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
            ->filters([

            ])

            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
