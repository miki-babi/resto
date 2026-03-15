@props([
    'title' => 'Fried Mozzarella',
    'price' => '$9.99',
    'description' => 'Hand-breaded whole milk mozzarella sticks served with our famous slow-cooked marinara sauce for dipping.',
    'excerpt' => null,
    'image' => null,
    'images' => null,
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

<div
    x-data
    @click="$dispatch('open-menu-modal', {{ json_encode(['title' => $title, 'price' => $price, 'description' => $description, 'images' => $resolvedImages]) }})"
    {{ $attributes->merge(['class' => 'cursor-pointer bg-white dark:bg-slate-800 rounded-2xl flex shadow-sm border border-slate-100 dark:border-slate-700/50 hover:shadow-md transition-shadow overflow-hidden']) }}
>
    <div class="flex-1 p-6">
        <div class="flex justify-between items-start mb-2">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ $title }}</h3>
        </div>
        <span class="text-metro-red font-black text-lg">{{ $price }}</span>
        <p class="text-slate-500 dark:text-slate-400 text-sm line-clamp-2 mt-2">
            {{ $cardExcerpt }}
        </p>
    </div>

    <div class="relative w-28 h-full md:h-full md:w-48  flex-shrink-0">
        <img class="w-full h-full object-cover" src="{{ $thumbnail }}" alt="{{ $alt }}" loading="lazy" />
    </div>
</div>
