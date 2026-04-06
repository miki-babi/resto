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
    class="group cursor-pointer"
>
    {{-- Image Container --}}
    <div class="relative aspect-square overflow-hidden rounded-[32px] bg-gray-50 border border-gray-100 shadow-sm transition-all duration-500 group-hover:shadow-xl group-hover:-translate-y-1">
        <img 
            src="{{ $thumbnail }}" 
            alt="{{ $alt }}" 
            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" 
            loading="lazy" 
        />
        
        {{-- Floating Details Indicator --}}
        <div class="absolute bottom-3 right-3 h-10 w-10 flex items-center justify-center rounded-2xl bg-white/90 backdrop-blur shadow-lg text-gray-900 opacity-0 transform translate-y-2 transition-all duration-300 group-hover:opacity-100 group-hover:translate-y-0">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
        </div>
    </div>

    {{-- Content --}}
    <div class="mt-4 px-2 space-y-1">
        <div class="flex justify-between items-start gap-4">
            <h3 class="text-[13px] font-black text-gray-900 leading-tight tracking-tight uppercase group-hover:text-amber-600 transition-colors duration-300">{{ $title }}</h3>
        </div>
        <p class="text-[11px] font-bold text-gray-400 font-mono">{{ $price }}</p>
    </div>
</div>
