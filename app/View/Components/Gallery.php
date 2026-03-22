<?php

namespace App\View\Components;

use App\Models\Gallery as GalleryModel;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Gallery extends Component
{
    public string $title;
    public ?string $description;
    public array $items;
    public ?string $slug;

    public function __construct(?string $title = null, ?string $description = null, array $items = [], ?string $slug = null)
    {
        $gallery = null;

        if ($slug !== null && $slug !== '') {
            $gallery = GalleryModel::query()
                ->where('slug', $slug)
                ->where('is_active', true)
                ->with('media')
                ->first();
        }

        $resolvedItems = !empty($items)
            ? $items
            : $this->mapGalleryItems($gallery);

        $resolvedItems = $this->normalizeItems($resolvedItems, $title ?: ($gallery?->display_title ?? 'Our Gallery'));

        $this->slug = $slug;
        $this->title = $title ?: ($gallery?->display_title ?? 'Our Gallery');
        $this->description = $description ?: $gallery?->description;
        $this->items = $resolvedItems;
    }

    public function render(): View|Closure|string
    {
        return view('components.gallery');
    }

    private function mapGalleryItems(?GalleryModel $gallery): array
    {
        if (!$gallery) {
            return [];
        }

        return $gallery
            ->getMedia('images')
            ->map(fn ($media, $index) => [
                'src' => $media->getUrl(),
                'alt' => $media->getCustomProperty('alt')
                    ?: $media->name
                    ?: sprintf('%s image %d', $gallery->display_title, $index + 1),
            ])
            ->filter(fn (array $item) => ($item['src'] ?? '') !== '')
            ->values()
            ->all();
    }

    private function normalizeItems(array $items, string $fallbackTitle): array
    {
        return collect($items)
            ->map(function ($item, int $index) use ($fallbackTitle) {
                if (is_string($item)) {
                    return [
                        'src' => $item,
                        'alt' => sprintf('%s image %d', $fallbackTitle, $index + 1),
                    ];
                }

                if (is_array($item)) {
                    $src = $item['src'] ?? $item['url'] ?? null;
                    if (!$src && isset($item[0]) && is_string($item[0])) {
                        $src = $item[0];
                    }

                    if (!$src) {
                        return null;
                    }

                    return [
                        'src' => $src,
                        'alt' => $item['alt']
                            ?? $item['title']
                            ?? sprintf('%s image %d', $fallbackTitle, $index + 1),
                    ];
                }

                return null;
            })
            ->filter(fn ($item) => is_array($item) && ($item['src'] ?? '') !== '')
            ->values()
            ->all();
    }
}
