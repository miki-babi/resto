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

<div
    x-data
    @click="$dispatch('open-menu-modal', {{ json_encode(['title' => $title, 'price' => $price, 'description' => $description, 'images' => $resolvedImages, 'variants' => $variants, 'addons' => $addons]) }})"
    class="group cursor-pointer flex items-center justify-between gap-6 py-6 border-b border-gray-100/60 first:pt-0 last:border-0"
>
    {{-- Content (Left) --}}
    <div class="flex-1 min-w-0 pr-4">
        <h3 class="text-lg font-bold text-gray-900 leading-tight group-hover:text-amber-700 transition-colors">
            {{ $title }}
        </h3>
        
        @if($cardExcerpt)
            <p class="text-sm text-gray-400 font-medium line-clamp-2 mt-1 leading-snug">
                {{ $cardExcerpt }}
            </p>
        @endif

        <p class="text-base font-bold text-gray-900 mt-3">
            {{ str_contains($price, 'ETB') ? $price : "ETB $price" }}
        </p>
    </div>

    {{-- Image Container (Right) --}}
    <div class="relative w-24 h-24 sm:w-28 sm:h-28 flex-shrink-0 overflow-hidden rounded-2xl bg-gray-50 border border-gray-100">
        <img 
            src="{{ $thumbnail }}" 
            alt="{{ $alt }}" 
            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" 
            loading="lazy" 
        />
        
        {{-- Floating (+) Button --}}
        <div class="absolute bottom-2 right-2 h-8 w-8 rounded-full bg-white shadow-lg flex items-center justify-center text-gray-900 transition-transform group-active:scale-90">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
            </svg>
        </div>
    </div>
</div>



