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
    class="group flex flex-col h-full bg-white transition-all"
>
    <div class="relative overflow-hidden rounded-2xl aspect-square bg-gray-50 border border-gray-100">
        <img 
            src="{{ $image }}" 
            alt="{{ $item->title }}" 
            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
            loading="lazy"
        >
        
        @if ($hasOptions)
            <button
                type="button"
                @click="openModal({{ $item->id }})"
                class="absolute bottom-2 right-2 h-9 w-9 flex items-center justify-center rounded-xl bg-white shadow-lg text-gray-900 transition-transform hover:scale-110 active:scale-95"
                title="Customize {{ $item->title }}"
            >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
            </button>
        @else
            <button
                type="button"
                @click="increaseQuantity({{ $item->id }})"
                class="absolute bottom-2 right-2 h-9 w-9 flex items-center justify-center rounded-xl bg-white shadow-lg text-gray-900 transition-transform hover:scale-110 active:scale-95"
                :class="quantityFor({{ $item->id }}) > 0 ? 'bg-black text-white' : ''"
                title="Add {{ $item->title }}"
            >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
            </button>
        @endif

        <div 
            x-show="quantityFor({{ $item->id }}) > 0"
            x-transition.scale
            class="absolute top-2 right-2 h-6 min-w-[24px] px-1.5 flex items-center justify-center rounded-full bg-black text-[10px] font-bold text-white shadow-lg"
            x-text="quantityFor({{ $item->id }})"
        ></div>
    </div>

    <div class="mt-3 flex flex-col flex-grow">
        <div class="flex justify-between items-start gap-2">
            <h3 class="text-sm font-bold text-gray-900 leading-tight line-clamp-2 group-hover:text-black transition-colors">{{ $item->title }}</h3>
        </div>
        <p class="mt-1 text-xs font-semibold text-gray-500">${{ number_format((float) $item->price, 2) }}</p>
    </div>
</article>
