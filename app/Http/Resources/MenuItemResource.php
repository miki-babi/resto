<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'price' => $this->price,
            'image_url' => $this->getFirstMediaUrl('menu_images') ?: '',
            'variants' => $this->variants->map(fn ($v) => ['id' => $v->id, 'name' => $v->name, 'price' => $v->price]),
            'addons' => $this->addons->map(fn ($a) => ['id' => $a->id, 'name' => $a->name, 'price' => $a->price]),
        ];
    }
}
