@props([
    'title' => 'Our Gallery',
    'description' => null,
    'items' => []
])

<div x-data="{ 
    open: false, 
    currentIndex: 0, 
    images: {{ json_encode($items) }},
    next() { this.currentIndex = (this.currentIndex + 1) % this.images.length },
    prev() { this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length }
}" 
@keydown.escape.window="open = false"
@keydown.right.window="if(open) next()"
@keydown.left.window="if(open) prev()">

    <section {{ $attributes->merge(['class' => 'py-16 px-4 bg-white ']) }}>
        <div class="container mx-auto max-w-7xl">
            {{-- Header --}}
            <div class="mb-10 px-2">
                <h2 class="text-2xl font-bold text-gray-900 mb-2 font-serif">{{ $title }}</h2>
                @if($description)<p class="text-gray-600 text-lg max-w-3xl">{{ $description }}</p>@endif
            </div>

            {{-- Responsive Grid: 2 cols on small, 3 on md+ --}}
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">
                @foreach($items as $index => $item)
                    <div @click="open = true; currentIndex = {{ $index }}" 
                         class="overflow-hidden shadow-md aspect-square cursor-pointer group md:rounded-[2rem] rounded-xl ">
                        <img src="{{ $item['src'] }}" alt="{{ $item['alt'] ?? $title }}" loading="lazy"
                             class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Fullscreen Modal --}}
    <template x-teleport="body">
        <div x-show="open" 
             x-transition.opacity.duration.300ms
             @click.self="open = false"
             class="fixed inset-0 z-[999] flex items-center justify-center bg-black/95 p-4"
             style="display: none;">
            
            {{-- Image & Controls Container --}}
            <div class="relative flex items-center justify-center max-w-full max-h-screen">
                
                {{-- Close Button --}}
                <button @click="open = false" 
                        class="absolute -top-12 right-0 md:top-4 md:right-4 z-[1010] bg-white text-black p-2 rounded-full shadow-xl hover:bg-gray-200 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                {{-- Prev Button --}}
                <button @click.stop="prev()" 
                        class="absolute left-2 md:left-4 z-[1010] bg-white text-black p-2 md:p-3 rounded-full shadow-lg hover:scale-110 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 md:w-6 md:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                {{-- Next Button --}}
                <button @click.stop="next()" 
                        class="absolute right-2 md:right-4 z-[1010] bg-white text-black p-2 md:p-3 rounded-full shadow-lg hover:scale-110 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 md:w-6 md:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                {{-- The Image --}}
                <img :src="images[currentIndex].src" 
                     :alt="images[currentIndex].alt"
                     x-touch:swipe.left="next()"
                     x-touch:swipe.right="prev()"
                     class="max-h-[85vh] md:max-h-[90vh] max-w-[95vw] md:max-w-5xl object-contain rounded-lg shadow-2xl">
            </div>
        </div>
    </template>
</div>