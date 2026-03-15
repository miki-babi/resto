<?php

namespace App\Filament\Resources\PastryItems\Pages;

use App\Filament\Resources\PastryItems\PastryItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePastryItem extends CreateRecord
{
    protected static string $resource = PastryItemResource::class;
}
