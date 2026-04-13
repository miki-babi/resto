<?php

namespace App\Filament\Resources\SmsPromos\Pages;

use App\Filament\Resources\SmsPromos\SmsPromoResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;


class EditSmsPromo extends EditRecord
{
    protected static string $resource = SmsPromoResource::class;

    protected function getHeaderActions(): array
    {
        return [
           
            DeleteAction::make(),
        ];
    }
   
}
