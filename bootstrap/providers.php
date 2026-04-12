<?php

use App\Providers\AppServiceProvider;
use App\Providers\Filament\OwnerPanelProvider;
use App\Providers\TelescopeServiceProvider;

return [
    AppServiceProvider::class,
    OwnerPanelProvider::class,
    TelescopeServiceProvider::class,
];
