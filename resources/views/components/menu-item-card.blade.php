@php
    $image = $item->getFirstMediaUrl('menu_images');
    if (!$image) {
        $image = 'https://placehold.co/600x600?text=' . urlencode($item->title);
    }
    $plainDescription = trim(strip_tags((string) ($item->description ?? '')));
    $hasOptions = $item->variants->isNotEmpty() || $item->addons->isNotEmpty();
@endphp

<article
    data-title="{{ $item->title }}"
    data-description="{{ $plainDescription }}"
    x-show="match($el.dataset.title, $el.dataset.description)"
    class="group relative flex items-center justify-between py-5 border-b border-gray-100 last:border-0 hover:bg-gray-50/50 transition-all cursor-pointer px-2"
    @click="{{ $hasOptions ? "openModal($item->id)" : "increaseQuantity($item->id)" }}"
>
    {{-- Left: Text Content --}}
    <div class="flex-grow pr-6">
        <h3 class="text-base md:text-lg font-bold text-gray-900 mb-1 group-hover:text-black transition-colors">{{ $item->title }}</h3>
        
        @if ($plainDescription)
            <p class="text-xs text-gray-400 line-clamp-2 mb-2 leading-relaxed">
                {{ $plainDescription }}
            </p>
        @endif

        <div class="flex items-center gap-3">
            <span class="text-sm md:text-base font-bold text-gray-900">
                ETB {{ number_format((float) $item->price, 0) }}
            </span>
        </div>
    </div>

    {{-- Right: Image & Action --}}
    <div class="relative h-24 w-24 md:h-28 md:w-28 flex-shrink-0">
        <div class="h-full w-full overflow-hidden rounded-2xl bg-gray-50 border border-gray-100">
            <img 
                src="{{ $image }}" 
                alt="{{ $item->title }}" 
                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                loading="lazy"
            >
        </div>
        
        {{-- Floating Add Button --}}
        <div class="absolute -bottom-1 -right-1 h-8 w-8 rounded-full bg-white shadow-lg border border-gray-100 flex items-center justify-center text-gray-900 transition-all hover:scale-110 active:scale-90 group-active:scale-90"
             :class="quantityFor({{ $item->id }}) > 0 ? 'bg-black text-white' : ''">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
            </svg>
        </div>

        {{-- Quantity Badge --}}
        <div x-show="quantityFor({{ $item->id }}) > 0"
             x-transition.scale
             class="absolute -top-1 -right-1 h-5 min-w-[20px] px-1 flex items-center justify-center rounded-full bg-black text-[9px] font-black text-white shadow-md border border-white"
             x-text="quantityFor({{ $item->id }})">
        </div>
    </div>
</article>
