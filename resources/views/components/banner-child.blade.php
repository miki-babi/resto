@props([
    'image',
    'title',
    'content',
    'reversed' => false,
    'buttonText' => null,
    'buttonUrl' => '#',
])

<section {{ $attributes->merge(['class' => 'mx-auto max-w-7xl px-4 py-8 md:py-12']) }}>
    <div class="flex flex-col md:flex-row w-full overflow-hidden rounded-[1.5rem] md:rounded-[2.5rem] shadow-2xl bg-white min-h-0 md:min-h-[550px]">
        
        {{-- Image Side --}}
        {{-- On mobile, we use a fixed aspect ratio (square or 4:3) so the image doesn't disappear or get too tall --}}
        <div class="w-full md:w-1/2 relative aspect-square md:aspect-auto min-h-[300px] md:min-h-full {{ $reversed ? 'md:order-last' : '' }}">
            <img src="{{ $image }}"
                alt="{{ $title }}" 
                class="absolute inset-0 h-full w-full object-cover" />
        </div>

        {{-- Content Side --}}
        <div class="w-full md:w-1/2 flex items-center p-6 sm:p-10 md:p-16 lg:p-20">
            <div class="w-full">
                <h2 class="font-sans text-2xl sm:text-3xl md:text-4xl font-bold mb-4 md:mb-8 text-gray-900 tracking-tight leading-tight">
                    {{ $title }}
                </h2>

                <div class="text-gray-600 text-sm sm:text-base md:text-lg mb-6 md:mb-8 leading-relaxed space-y-4">
                    {!! nl2br(e($content)) !!}
                </div>

                @if($buttonText)
                <div class="flex"> {{-- Wrapper to prevent button from stretching full-width on some browsers --}}
                    <a href="{{ $buttonUrl }}"
                        class="inline-flex items-center justify-center bg-metro-dark text-white px-6 md:px-8 py-3 md:py-4 rounded-xl font-bold text-sm transition hover:bg-red-800 group shadow-lg shadow-red-900/20 w-full sm:w-auto">
                        {{ $buttonText }}
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="ml-2 h-4 w-4 transition-transform group-hover:translate-x-1" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>