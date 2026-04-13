<?php

namespace App\Filament\Resources\SmsPromos\Pages;

use App\Filament\Resources\SmsPromos\SmsPromoResource;
use App\Jobs\SendSmsJob;
use App\Models\Customer;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Log;

class ViewSmsPromo extends ViewRecord
{
    protected static string $resource = SmsPromoResource::class;

    protected function getHeaderActions(): array
    {
        return [
           Action::make('customAction')
    ->label('Send Promo')
    ->icon('heroicon-m-megaphone')
    ->color('primary')
    ->requiresConfirmation() // Good practice for mass actions
    ->modalHeading('Send Promotion')
    ->modalDescription('Are you sure you want to send this promo content to all customers?')
    ->action(function () {
        // Use chunk to avoid memory issues with large customer bases
        Customer::query()->chunk(100, function ($customers) {
            foreach ($customers as $customer) {
                // Use the phone() method/accessor you defined
                $phoneNumber = $customer->phone; // Assuming you have a phoneNumber accessor in Customer model
                
                Log::info("Dispatching SMS to {$phoneNumber}");
                
                SendSmsJob::dispatch($phoneNumber, $this->record->content);
            }
        });

        // Notify the user
        \Filament\Notifications\Notification::make()
            ->title('Promo campaign dispatched!')
            ->success()
            ->send();
    }),
            DeleteAction::make(),
            EditAction::make(),
        ];
    }
   
}
