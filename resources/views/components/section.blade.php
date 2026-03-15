@blaze(compile: true)

{{-- @blaze(fold: true) --}}
@props([
    'image' => null,
    'videoUrl' => null, {{-- New Prop --}}
    'subtitle' => null,
    'title',
    'description',
    'reversed' => false,
    'buttonText' => null,
    'buttonUrl' => '#'
])

<section {{ $attributes->merge(['class' => 'py-20']) }}>
    <div class="container mx-auto px-4 flex flex-col {{ $reversed ? 'md:flex-row-reverse' : 'md:flex-row' }} items-center gap-16 max-w-7xl">
        
        {{-- Media Column (Image or Video) --}}
        <div class="md:w-1/2 w-full">
            @if($videoUrl)
                <div class="relative overflow-hidden rounded-2xl shadow-xl aspect-video">
                    <iframe 
                    loading="lazy"
                        class="absolute inset-0 w-full h-full"
                        src="{{ $videoUrl }}" 
                        title="YouTube video player" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                        referrerpolicy="strict-origin-when-cross-origin" 
                        allowfullscreen>
                    </iframe>
                </div>
            @else
                <img alt="{{ $title }}" class="rounded-2xl shadow-xl w-full object-cover aspect-[4/3]"
                    src="{{ $image }}" loading="lazy" />
            @endif
        </div>

        {{-- Text Column --}}
        <div class="md:w-1/2">
            @if($subtitle)
                <span class="text-metro-red font-bold uppercase tracking-widest text-sm mb-4 block">
                    {{ $subtitle }}
                </span>
            @endif
            
            <h2 class="font-serif text-4xl mb-6 text-gray-900 leading-tight content-visibility-auto">{{ $title }}</h2>
            
            <p class="text-gray-600 leading-relaxed mb-8 text-lg content-visibility-auto ">
                {{ $description }}
            </p>

            @if($buttonText)
                <a href="{{ $buttonUrl }}" 
                   class="inline-flex items-center bg-metro-dark font-serif text-white px-8 py-3 rounded-lg text-sm font-bold hover:bg-red-800 transition shadow-lg group">
                    {{ $buttonText }}
                    @if(str_contains(strtolower($buttonText), 'youtube'))
                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="9 5l7 7-7 7" />
                        </svg>
                    @endif
                </a>
            @endif
        </div>
        
    </div>
</section>