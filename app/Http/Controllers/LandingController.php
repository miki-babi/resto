<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Faq;
use App\Models\MenuCategory;
use App\Models\MenuItem;

class LandingController extends Controller
{
    public function home()
    {
        $requestId = uniqid('home_', true);

        Log::debug('LandingController@home start', [
            'request_id' => $requestId,
            'path' => request()->path(),
        ]);

        try {
            $featuredItems = $this->getFeaturedItems();

            Log::info('LandingController@home fetched items', [
                'request_id' => $requestId,
                'featured_items_count' => count($featuredItems),
                'sample_titles' => array_slice(array_map(fn ($i) => $i['title'] ?? null, $featuredItems), 0, 5),
            ]);

            return view('pages.landing.main', [
                'featuredItems' => $featuredItems,
                'faqs' => $this->getFaqItems(),
            ]);
        } catch (\Throwable $e) {
            Log::error('LandingController@home failed', [
                'request_id' => $requestId,
                'error' => $e->getMessage(),
                'exception' => get_class($e),
            ]);

            throw $e;
        }
    }

    public function menu()
    {
        $requestId = uniqid('menu_', true);

        Log::debug('LandingController@menu start', [
            'request_id' => $requestId,
            'path' => request()->path(),
        ]);

        try {
            $featuredItems = $this->getFeaturedItems();
            $menuCategories = $this->getMenuCategories();

            $totalMenuItems = array_sum(array_map(fn ($c) => is_array($c['items'] ?? null) ? count($c['items']) : 0, $menuCategories));

            Log::info('LandingController@menu fetched data', [
                'request_id' => $requestId,
                'featured_items_count' => count($featuredItems),
                'categories_count' => count($menuCategories),
                'category_items_total' => $totalMenuItems,
                'sample_categories' => array_slice(array_map(fn ($c) => $c['name'] ?? null, $menuCategories), 0, 5),
            ]);

            return view('pages.landing.menu', [
                'featuredItems' => $featuredItems,
                'menuCategories' => $menuCategories,
            ]);
        } catch (\Throwable $e) {
            Log::error('LandingController@menu failed', [
                'request_id' => $requestId,
                'error' => $e->getMessage(),
                'exception' => get_class($e),
            ]);

            throw $e;
        }
    }

    public function seomenupage($slug)
    {
        
        $requestId = uniqid('home_', true);

        Log::debug('LandingController@home start', [
            'request_id' => $requestId,
            'path' => request()->path(),
        ]);

        try {
            $featuredItems = $this->getFeaturedItems();

            Log::info('LandingController@home fetched items', [
                'request_id' => $requestId,
                'featured_items_count' => count($featuredItems),
                'sample_titles' => array_slice(array_map(fn ($i) => $i['title'] ?? null, $featuredItems), 0, 5),
            ]);

            return view('pages.landing.main', [
                'featuredItems' => $featuredItems,
                'faqs' => $this->getFaqItems(),
            ]);
        } catch (\Throwable $e) {
            Log::error('LandingController@home failed', [
                'request_id' => $requestId,
                'error' => $e->getMessage(),
                'exception' => get_class($e),
            ]);

            throw $e;
        }
    }



    private function getFeaturedItems(): array
    {
        Log::debug('LandingController@getFeaturedItems query start');

        $items = MenuItem::query()
            ->where('is_available', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->orderBy('title')
            ->with([
                'media',
                'variants' => fn ($query) => $query->orderBy('sort_order')->orderBy('name'),
                'addons' => fn ($query) => $query->where('is_active', true)->orderBy('sort_order')->orderBy('name'),
            ])
            ->limit(20)
            ->get();

        Log::debug('LandingController@getFeaturedItems featured query result', [
            'count' => $items->count(),
        ]);

        if ($items->isEmpty()) {
            $items = MenuItem::query()
                ->where('is_available', true)
                ->orderBy('sort_order')
                ->orderBy('title')
                ->with([
                    'media',
                    'variants' => fn ($query) => $query->orderBy('sort_order')->orderBy('name'),
                    'addons' => fn ($query) => $query->where('is_active', true)->orderBy('sort_order')->orderBy('name'),
                ])
                ->limit(20)
                ->get();

            Log::debug('LandingController@getFeaturedItems fallback query result', [
                'count' => $items->count(),
            ]);
        }

        return $items
            ->map(fn (MenuItem $item) => $this->presentMenuItem($item))
            ->all();
    }

    private function getMenuCategories(): array
    {
        Log::debug('LandingController@getMenuCategories query start');

        return MenuCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->with([
                'items' => fn ($query) => $query
                    ->where('is_available', true)
                    ->orderBy('sort_order')
                    ->orderBy('title'),
                'items.media',
                'items.variants' => fn ($query) => $query->orderBy('sort_order')->orderBy('name'),
                'items.addons' => fn ($query) => $query->where('is_active', true)->orderBy('sort_order')->orderBy('name'),
            ])
            ->get()
            ->map(function (MenuCategory $category) {
                return [
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'items' => $category->items
                        ->map(fn (MenuItem $item) => $this->presentMenuItem($item))
                        ->all(),
                ];
            })
            ->tap(function ($categories) {
                $totalItems = $categories->sum(fn ($c) => is_array($c['items'] ?? null) ? count($c['items']) : 0);

                Log::debug('LandingController@getMenuCategories query result', [
                    'categories_count' => $categories->count(),
                    'items_total' => (int) $totalItems,
                ]);
            })
            ->all();
    }

    private function getFaqItems()
    {
        Log::debug('LandingController@getFaqItems query start');

        return Faq::query()
            ->active()
            ->orderBy('sort_order')
            ->orderBy('question')
            ->get();
    }

    private function presentMenuItem(MenuItem $item): array
    {
        $images = $item
            ->getMedia('menu_images')
            ->map(fn ($media) => $media->getUrl())
            ->filter(fn ($url) => is_string($url) && $url !== '')
            ->values()
            ->all();

        if (empty($images)) {
            $images = ['https://placehold.co/1200x800?text=Menu+Item'];
        }

        $variants = $item->relationLoaded('variants')
            ? $item->variants
            : $item->variants()->orderBy('sort_order')->orderBy('name')->get();

        $addons = $item->relationLoaded('addons')
            ? $item->addons
            : $item->addons()->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();

        return [
            'title' => (string) $item->title,
            'price' => $this->formatPrice($item->price),
            'description' => (string) ($item->description ?? ''),
            'images' => $images,
            'variants' => $variants
                ->map(fn ($variant) => [
                    'name' => (string) $variant->name,
                    'price' => $this->formatPrice($variant->price),
                ])
                ->values()
                ->all(),
            'addons' => $addons
                ->where('is_active', true)
                ->map(fn ($addon) => [
                    'name' => (string) $addon->name,
                    'price' => $this->formatPrice($addon->price),
                ])
                ->values()
                ->all(),
        ];
    }

    private function formatPrice(mixed $price): string
    {
        return '$' . number_format((float) $price, 2);
    }
}
