<x-layouts.app-main>
    {{-- @blaze --}}
    @php
        $menuUrl = \App\Filament\Resources\Pages\PageResource::menuUrl();
        $aboutUrl = \App\Filament\Resources\Pages\PageResource::pageUrl('about');
    @endphp

    {{-- 1. Push SEO tags specifically for Home --}}
    @push('meta')
        <meta name="description"
            content="Experience the heart of Bisrate Gebriel at Mera Coffee. A cozy coffee shop perfect for relaxing, working, and enjoying fine brews.">
        <meta property="og:title" content="Mera Coffee | Cozy Coffee Shop in Bisrate Gebriel">
        <meta property="og:image" content="https://images.pexels.com/photos/302902/pexels-photo-302902.jpeg">
        @php
            $faqSchema = collect($faqs ?? [])->map(function ($faq) {
                return [
                    '@type' => 'Question',
                    'name' => data_get($faq, 'question'),
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => trim(strip_tags((string) data_get($faq, 'answer', ''))),
                    ],
                ];
            })->filter(fn ($faq) => filled($faq['name']) && filled($faq['acceptedAnswer']['text']))->values();
        @endphp
        @if ($faqSchema->isNotEmpty())
            <script type="application/ld+json">{!! json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => $faqSchema,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
        @endif
    @endpush

    {{-- 2. Modern Hero Section --}}
    <section class="relative min-h-[85vh] flex items-center overflow-hidden bg-black">
        {{-- Background Media --}}
        <div class="absolute inset-0 z-0">
            @if(asset('asset/backgroundvideo3.mp4'))
                <video autoplay muted loop playsinline preload="auto" class="absolute inset-0 w-full h-full object-cover">
                    <source src="{{ asset('asset/backgroundvideo3.mp4') }}" type="video/mp4">
                </video>
            @else
                <img src="https://images.pexels.com/photos/302902/pexels-photo-302902.jpeg" 
                     class="absolute inset-0 w-full h-full object-cover" 
                     alt="Mera Coffee Background">
            @endif
            <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px]"></div>
        </div>

        <div class="container mx-auto px-6 relative z-10 max-w-7xl">
            <div class="max-w-4xl space-y-8">
                <div class="space-y-4">
                    <p class="text-amber-400 text-sm font-black uppercase tracking-[0.3em]">
                        Best Coffee Shop around Kanchis
                    </p>
                    <h1 class="text-white text-5xl md:text-8xl font-black leading-[1.1] tracking-tight">
                        Wee Catering & <br/>
                        <span class="text-amber-500">Cozy Coffee</span> Shop
                    </h1>
                </div>
                
                <div class="flex flex-wrap gap-4">
                    <a href="https://maps.google.com" 
                       class="px-10 py-5 bg-white text-black text-sm font-black rounded-2xl shadow-premium hover:shadow-hover hover:-translate-y-1 transition-all">
                        Get Directions
                    </a>
                    <a href="{{ $menuUrl }}" 
                       class="px-10 py-5 bg-white/10 backdrop-blur-md text-white text-sm font-black rounded-2xl border border-white/20 hover:bg-white/20 transition-all">
                        View Menu
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. Top Picks / Popular Orders --}}
    <section class="py-24 bg-white overflow-hidden">
        <div class="container mx-auto px-6 max-w-7xl">
            <div class="flex flex-col md:flex-row justify-between items-end gap-8 mb-16 px-2">
                <div class="space-y-2">
                    <p class="text-amber-600 text-[10px] font-black uppercase tracking-widest">Customer Favorites</p>
                    <h2 class="text-4xl md:text-6xl font-black text-gray-900 tracking-tight">Popular Orders</h2>
                </div>
                <a href="{{ $menuUrl }}" class="text-gray-900 font-black text-sm uppercase tracking-widest border-b-2 border-amber-500 pb-1 hover:text-amber-600 transition">
                    Explore Full Menu
                </a>
            </div>
            
            <x-featured-menu title="" :items="$featuredItems ?? []" />
        </div>
    </section>

    {{-- 4. Our Story Section --}}
    <section class="py-24 bg-gray-50 overflow-hidden">
        <div class="container mx-auto px-6 max-w-7xl">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="relative group">
                    <div class="absolute -inset-4 bg-amber-500/10 rounded-[40px] rotate-2 transition group-hover:rotate-0"></div>
                    <div class="relative rounded-[40px] overflow-hidden shadow-premium aspect-[4/5] lg:aspect-auto lg:h-[600px]">
                        <img src="https://images.pexels.com/photos/302899/pexels-photo-302899.jpeg" 
                             class="w-full h-full object-cover" 
                             alt="The Mera Coffee Story">
                    </div>
                </div>
                
                <div class="space-y-8">
                    <div class="space-y-4">
                        <p class="text-amber-600 text-[10px] font-black uppercase tracking-widest">Our Heritage</p>
                        <h2 class="text-4xl md:text-6xl font-black text-gray-900 tracking-tight leading-tight">
                            The Mera Coffee <span class="text-amber-500 underline decoration-gray-200 underline-offset-8">Story</span>
                        </h2>
                    </div>
                    
                    <p class="text-lg text-gray-600 leading-relaxed font-medium">
                        What started as a small dream in Bisrate Gebriel is now a community hub. We believe in slow-roasting, sustainable sourcing, and providing a workspace that feels like your own living room. No shortcuts. No gimmicks. Just real ingredients and unforgettable flavors.
                    </p>

                    <div class="pt-4">
                        <a href="{{ $aboutUrl }}" 
                           class="inline-flex items-center gap-4 bg-black text-white px-10 py-5 rounded-2xl text-sm font-black shadow-premium hover:shadow-hover hover:-translate-y-1 transition-all">
                            Read Our Full Story
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 5. Gallery --}}
    <section class="py-24 bg-white overflow-hidden">
        <x-gallery title="Captured Moments" slug="best-foods-around-bole" />
    </section>

    {{-- 6. Social & FAQ --}}
    <x-testimonials />
    
    <section class="py-24 bg-gray-50 overflow-hidden">
        <div class="container mx-auto px-6 max-w-5xl">
            <div class="text-center space-y-4 mb-20">
                <p class="text-amber-600 text-[10px] font-black uppercase tracking-widest">Have Questions?</p>
                <h2 class="text-4xl md:text-6xl font-black text-gray-900 tracking-tight">Frequently Asked</h2>
            </div>
            <x-faq :items="$faqs ?? []" />
        </div>
    </section>

    <x-locations />

</x-layouts.app-main>
