<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Faq;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\CateringPackage;
use App\Models\CateringRequest;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\Page;
// use App\Models\MenuCategory;

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

    public function catering(Request $request)
    {
        $requestId = uniqid('catering_', true);

        Log::debug('LandingController@catering start', [
            'request_id' => $requestId,
            'path' => $request->path(),
        ]);

        try {
            $packages = $this->getCateringPackages();
            $selectedPackageId = $request->integer('package');

            Log::info('LandingController@catering fetched packages', [
                'request_id' => $requestId,
                'package_count' => count($packages),
                'selected_package_id' => $selectedPackageId,
            ]);

            return view('pages.landing.catering', [
                'cateringPackages' => $packages,
                'selectedPackageId' => $selectedPackageId,
            ]);
        } catch (\Throwable $e) {
            Log::error('LandingController@catering failed', [
                'request_id' => $requestId,
                'error' => $e->getMessage(),
                'exception' => get_class($e),
            ]);

            throw $e;
        }
    }

    public function cateringRequest(Request $request)
    {
        $requestId = uniqid('catering_request_page_', true);

        Log::debug('LandingController@cateringRequest start', [
            'request_id' => $requestId,
            'path' => $request->path(),
        ]);

        try {
            $packages = $this->getCateringPackages();
            $selectedPackageId = $request->integer('package');

            Log::info('LandingController@cateringRequest fetched packages', [
                'request_id' => $requestId,
                'package_count' => count($packages),
                'selected_package_id' => $selectedPackageId,
            ]);

            return view('pages.landing.catering-request', [
                'cateringPackages' => $packages,
                'selectedPackageId' => $selectedPackageId,
            ]);
        } catch (\Throwable $e) {
            Log::error('LandingController@cateringRequest failed', [
                'request_id' => $requestId,
                'error' => $e->getMessage(),
                'exception' => get_class($e),
            ]);

            throw $e;
        }
    }

    public function submitCateringRequest(Request $request)
    {
        $requestId = uniqid('catering_request_', true);

        Log::debug('LandingController@submitCateringRequest start', [
            'request_id' => $requestId,
            'path' => $request->path(),
        ]);

        $data = $request->validate([
            'catering_package_id' => 'required|exists:catering_packages,id',
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'note' => 'nullable|string',
        ]);

        $data['name'] = trim((string) $data['name']);
        $data['contact'] = trim((string) $data['contact']);

        try {
            $cateringRequest = CateringRequest::create($data);

            Log::info('LandingController@submitCateringRequest created', [
                'request_id' => $requestId,
                'catering_request_id' => $cateringRequest->id,
                'package_id' => $cateringRequest->catering_package_id,
            ]);

            return redirect()
                ->back()
                ->with('success', 'Thanks! We received your catering request and will reach out shortly.');
        } catch (\Throwable $e) {
            Log::error('LandingController@submitCateringRequest failed', [
                'request_id' => $requestId,
                'error' => $e->getMessage(),
                'exception' => get_class($e),
            ]);

            throw $e;
        }
    }

    public function page(string $slug)
    {
        $requestId = uniqid('page_', true);

        Log::debug('LandingController@page start', [
            'request_id' => $requestId,
            'slug' => $slug,
        ]);

        $page = Page::query()
            ->where('slug', $slug)
            ->with([
                'gallery.media',
                'menuCategory.items.media',
                'menuCategory.items.variants' => fn ($query) => $query->orderBy('sort_order')->orderBy('name'),
                'menuCategory.items.addons' => fn ($query) => $query->where('is_active', true)->orderBy('sort_order')->orderBy('name'),
            ])
            ->firstOrFail();

        $menuItems = [];
        if ($page->menuCategory) {
            $menuItems = $page->menuCategory
                ->items
                ->map(fn (MenuItem $item) => $this->presentMenuItem($item))
                ->all();
        }

        return view('pages.show', [
            'page' => $page,
            'menuItems' => $menuItems,
        ]);
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

    private function getCateringPackages(): array
    {
        Log::debug('LandingController@getCateringPackages query start');

        $packages = CateringPackage::query()
            ->where('is_active', true)
            ->orderBy('min_guests')
            ->orderBy('name')
            ->with('media')
            ->get();

        Log::debug('LandingController@getCateringPackages query result', [
            'count' => $packages->count(),
        ]);

        return $packages->map(function (CateringPackage $package) {
            $galleryImages = $package
                ->getMedia('gallery')
                ->map(fn (Media $media) => $this->resolveMediaUrl($media))
                ->filter(fn ($url) => is_string($url) && $url !== '')
                ->values()
                ->all();

            $coverMedia = $package->getFirstMedia('cover_image');
            $cover = $this->resolveMediaUrl($coverMedia);

            $images = $galleryImages;

            if (is_string($cover) && $cover !== '') {
                array_unshift($images, $cover);
                $images = array_values(array_unique($images));
            }

            if (empty($images)) {
                $images = ['https://placehold.co/1200x800?text=Catering+Package'];
            }

            $highlights = $package->highlights ?? [];
            if (!is_array($highlights)) {
                $highlights = [];
            }
            $highlights = collect($highlights)
                ->map(fn ($item) => is_array($item) ? ($item['value'] ?? null) : $item)
                ->filter(fn ($item) => is_string($item) && trim($item) !== '')
                ->map(fn ($item) => trim($item))
                ->values()
                ->all();

            $badgeVariant = (string) ($package->badge_variant ?? '');
            $badgeClass = match ($badgeVariant) {
                'emerald' => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400',
                'gold' => 'bg-gold/10 text-gold',
                'neutral' => 'bg-gray-100 text-gray-700',
                default => 'bg-accent/10 text-accent',
            };

            return [
                'id' => $package->id,
                'name' => (string) $package->name,
                'description' => (string) ($package->description ?? ''),
                'min_guests' => (int) $package->min_guests,
                'images' => $images,
                'cover' => $images[0] ?? 'https://placehold.co/1200x800?text=Catering+Package',
                'accent' => $images[1] ?? ($images[0] ?? 'https://placehold.co/1200x800?text=Catering+Package'),
                'price_per_person' => $package->price_per_person !== null ? (float) $package->price_per_person : null,
                'price_total' => $package->price_total !== null ? (float) $package->price_total : null,
                'badge_text' => (string) ($package->badge_text ?? ''),
                'badge_class' => $badgeClass,
                'highlights' => $highlights,
            ];
        })->all();
    }

    private function resolveMediaUrl(?Media $media): ?string
    {
        if (!$media) {
            return null;
        }

        try {
            if ($media->disk === 'public') {
                return $media->getUrl();
            }

            if (method_exists($media, 'getTemporaryUrl')) {
                return $media->getTemporaryUrl(now()->addMinutes(60));
            }
        } catch (\Throwable $e) {
            Log::warning('LandingController@resolveMediaUrl failed', [
                'media_id' => $media->id,
                'disk' => $media->disk,
                'error' => $e->getMessage(),
            ]);
        }

        return $media->getUrl();
    }
}
