<?php

namespace App\View\Components;

use App\Models\Location as LocationModel;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Locations extends Component
{
    public array $locations;

    public function __construct(array $locations = [])
    {
        if (!empty($locations)) {
            $this->locations = $locations;
            return;
        }

        $this->locations = LocationModel::query()
            ->orderBy('name')
            ->get()
            ->map(function (LocationModel $location) {
                $address = (string) ($location->address ?? '');
                $directionUrl = $address !== ''
                    ? 'https://www.google.com/maps/search/?api=1&query=' . urlencode($address)
                    : null;

                $phones = $location->contact_phone;
                if (is_string($phones)) {
                    $phones = [$phones];
                } elseif (!is_array($phones)) {
                    $phones = [];
                }

                $phones = array_values(array_filter($phones, fn ($phone) => is_string($phone) && $phone !== ''));

                return [
                    'name' => (string) $location->name,
                    'embed_url' => (string) ($location->google_maps_embed_url ?? ''),
                    'address' => $address,
                    'phones' => $phones,
                    'directions_url' => $directionUrl,
                ];
            })
            ->all();
    }

    public function render(): View|Closure|string
    {
        return view('components.locations');
    }
}
