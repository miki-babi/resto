@props([
    'title' => 'Fried Mozzarella',
    'price' => '$9.99',
    'description' => 'Hand-breaded whole milk mozzarella sticks served with our famous slow-cooked marinara sauce for dipping.',
    'excerpt' => null,
    'image' => null,
    'images' => null,
    'variants' => [],
    'addons' => [],
    'imageAlt' => null,
])

@php
    $fallbackImages = [
        'https://lh3.googleusercontent.com/aida-public/AB6AXuAkigsqqdPv0UI2IjZeI7MHLMYpkPU_He0T6-AUNzJBUpViTOJuinxuysdm1mKzkIB8UZyTxQH_F-seAwYYuSdtXxE1lbpU8ICpYyq1Erg-qHaXbgADe0Oap1nMTsEC5dyC1kRq0F-jV917zo8pNAbxIi5r4C9JN1qN7znenZdOlWBSBj98VvPoCrXuCu-dYf53WQ9xcq16YHgt_pg3xWiD7TyxlAZMQbkPkBD-EMbBHXu3Hy7V76JnTK02OthbPSyN4tQ3XKuxwXQ',
        'https://placehold.co/600x400?text=Side+View',
        'https://placehold.co/600x400?text=Cheese+Pull',
    ];

    $resolvedImages = $images;
    if ($resolvedImages instanceof \Illuminate\Support\Collection) {
        $resolvedImages = $resolvedImages->all();
    } elseif (is_string($resolvedImages) && $resolvedImages !== '') {
        $resolvedImages = [$resolvedImages];
    } elseif (! is_array($resolvedImages)) {
        $resolvedImages = [];
    }

    $resolvedImages = array_values(array_filter($resolvedImages, fn ($url) => is_string($url) && $url !== ''));

    if (empty($resolvedImages)) {
        $resolvedImages = $image ? [$image] : $fallbackImages;
    } elseif ($image) {
        $resolvedImages = array_values(array_unique([$image, ...$resolvedImages]));
    }

    $thumbnail = $resolvedImages[0] ?? $fallbackImages[0];
    $alt = $imageAlt ?: $title;
    $cardExcerpt = $excerpt ?: $description;
@endphp

<button
    type="button"
    x-data
    @click="$dispatch('open-menu-modal', {{ json_encode(['title' => $title, 'price' => $price, 'description' => $description, 'images' => $resolvedImages, 'variants' => $variants, 'addons' => $addons]) }})"
    class="relative flex aspect-square overflow-visible cursor-pointer focus:outline-none group"
>
    <div class="absolute inset-0 overflow-hidden rounded-3xl bg-slate-100 dark:bg-slate-800 transition-transform duration-300 group-hover:scale-[1.01] md:group-hover:scale-[1.02] shadow-sm group-hover:shadow-md">
        <img 
            src="{{ $thumbnail }}" 
            alt="{{ $alt }}" 
            class="w-full h-full object-cover" 
            loading="lazy" 
        />
        {{-- Overlay for text on hover or subtle always-on info --}}
        <div class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            <h3 class="text-white font-bold text-sm truncate">{{ $title }}</h3>
            <p class="text-white/80 text-xs font-semibold">{{ $price }}</p>
        </div>
    </div>
</button>
