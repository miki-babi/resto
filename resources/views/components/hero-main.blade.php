@blaze(compile: true)

@props([
    'image',
    'subtitle',
    'title',
    'primaryButtonText' => 'Get Directions',
    'primaryButtonUrl' => '#',
    'secondaryButtonText' => 'View Menu',
    'secondaryButtonUrl' => '/menu1.html'
])

<section {{ $attributes->merge(['class' => 'relative h-[550px] md:h-[700px] flex items-end text-white overflow-hidden']) }}
    style="background-image: url('{{ $image }}'); background-size: cover; background-position: center;" loading="lazy">

    {{-- Dark Gradient Overlay --}}
    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>

    <div class="container mx-auto px-4 pb-12 relative z-10 max-w-7xl">
        <div class="max-w-3xl">
            @if($subtitle)
                <h2 class="text-sm font-semibold mb-2 drop-shadow-md uppercase tracking-wider">
                    {{ $subtitle }}
                </h2>
            @endif

            <h1 class="font-serif text-3xl md:text-6xl lg:text-7xl leading-tight mb-8 drop-shadow-lg">
                {{ $title }}
            </h1>

            <div class="flex flex-wrap gap-4">
                {{-- Primary Button --}}
                <a href="{{ $primaryButtonUrl }}" 
                   class="inline-block bg-metro-dark font-serif text-white px-8 py-3 rounded-lg text-sm font-bold hover:bg-opacity-90 transition shadow-lg">
                    {{ $primaryButtonText }}
                </a>

                {{-- Secondary Button --}}
                @if($secondaryButtonText)
                    <a href="{{ $secondaryButtonUrl }}" 
                       class="inline-block bg-metro-dark font-serif text-white px-8 py-3 rounded-lg text-sm font-bold hover:bg-opacity-90 transition shadow-lg">
                        {{ $secondaryButtonText }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>