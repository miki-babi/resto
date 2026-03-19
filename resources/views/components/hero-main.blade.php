@props([
    'image',
    'subtitle',
    'title',
    'primaryButtonText' => 'Get Directions',
    'primaryButtonUrl' => '#',
    'secondaryButtonText' => 'View Menu',
    'secondaryButtonUrl' => '/menu1.html',
    'backgroundVideo' => null,
])

<section 
    x-data="{ 
        videoLoaded: false,
        init() {
            // Force check if video is already playing/loaded on mount
            if (this.$refs.bgVideo && this.$refs.bgVideo.readyState >= 3) {
                this.videoLoaded = true;
            }
        }
    }"
    {{ $attributes->merge(['class' => 'relative h-[600px] md:h-[700px] flex items-end text-white overflow-hidden bg-black']) }}
>
    {{-- 1. Placeholder Image --}}
    <div 
        class="absolute inset-0 z-0 transition-opacity duration-1000"
        :class="videoLoaded ? 'opacity-0' : 'opacity-100'"
        style="background-image: url('{{ $image }}'); background-size: cover; background-position: center;"
    ></div>

    {{-- 2. Background Video --}}
    @if($backgroundVideo)
        <video 
            x-ref="bgVideo"
            autoplay 
            muted 
            loop 
            playsinline
            preload="auto"
            @playing="videoLoaded = true"
            @canplaythrough="videoLoaded = true"
            class="absolute inset-0 w-full h-full object-cover z-0 transition-opacity duration-1000"
            :class="videoLoaded ? 'opacity-100' : 'opacity-0'"
        >
            <source src="{{ $backgroundVideo }}" type="video/webm">
            {{-- Pro-tip: Keep an MP4 fallback for Safari/Older browsers if possible --}}
        </video>
    @endif

    {{-- 3. Dark Gradient Overlay --}}
    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent z-[1]"></div>

    {{-- Content --}}
    <div class="container mx-auto px-4 pb-12 relative z-10 max-w-7xl">
        <div class="max-w-3xl">
            @if($subtitle)
                <p class="text-sm font-semibold mb-2 drop-shadow-md uppercase tracking-wider">
                    {{ $subtitle }}
                </p>
            @endif

            <h1 class="font-serif text-3xl md:text-6xl lg:text-7xl leading-tight mb-8 drop-shadow-lg">
                {{ $title }}
            </h1>

            <div class="flex flex-wrap gap-4">
                <a href="{{ $primaryButtonUrl }}" 
                   class="inline-block bg-metro-dark hover:bg-metro-hover font-serif text-white px-8 py-3 rounded-lg text-sm font-bold transition shadow-lg">
                    {{ $primaryButtonText }}
                </a>

                @if($secondaryButtonText)
                    <a href="{{ $secondaryButtonUrl }}" 
                   class="inline-block bg-metro-dark hover:bg-metro-hover font-serif text-white px-8 py-3 rounded-lg text-sm font-bold transition shadow-lg">
                        {{ $secondaryButtonText }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>