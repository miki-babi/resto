<?php

namespace App\Filament\Resources\PastryPackages\Pages;

use App\Filament\Resources\PastryPackages\PastryPackageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPastryPackage extends EditRecord
{
    protected static string $resource = PastryPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
