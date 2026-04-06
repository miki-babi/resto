<x-layouts.app-main>
    {{-- 1. Push SEO tags specifically for Home --}}
    @push('meta')
        <meta name="description"
            content="Experience the heart of Bisrate Gebriel at Mera Coffee. A cozy coffee shop perfect for relaxing, working, and enjoying fine brews.">
        <meta property="og:title" content="Mera Coffee | Cozy Coffee Shop in Bisrate Gebriel">
        <meta property="og:image" content="https://images.pexels.com/photos/302902/pexels-photo-302902.jpeg">

        <style>
            .scrollbar-hide::-webkit-scrollbar {
                display: none;
            }

            .scrollbar-hide {
                -ms-overflow-style: none;
                /* IE and Edge */
                scrollbar-width: none;
                /* Firefox */
            }
        </style>
    @endpush

    {{-- Modern Menu Page --}}
    <main class="flex-1 bg-white" x-data="{
        activeCategory: '',
        init() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.activeCategory = entry.target.id;
                        const pill = document.querySelector(`a[href='#${entry.target.id}']`);
                        if (pill) {
                            const container = document.getElementById('category-scroll-container');
                            if (container) {
                                const pillOffset = pill.offsetLeft;
                                const containerWidth = container.offsetWidth;
                                const pillWidth = pill.offsetWidth;
                                container.scrollTo({
                                    left: pillOffset - (containerWidth / 2) + (pillWidth / 2),
                                    behavior: 'smooth'
                                });
                            }
                        }
                    }
                });
            }, { threshold: 0.2, rootMargin: '-10% 0px -70% 0px' });
    
            document.querySelectorAll('.menu-section').forEach(section => {
                observer.observe(section);
            });
        }
    }">

        <!-- Premium Floating Category Selector -->
        <div class="sticky top-[72px] z-40 bg-white/60 backdrop-blur-xl border-b border-gray-100 py-4 shadow-sm">
            <div class="container mx-auto px-6 max-w-7xl">
                <div id="category-scroll-container" class="flex items-center gap-3 overflow-x-auto pb-1 scrollbar-hide">
                    @foreach ($menuCategories ?? [] as $category)
                        @php
                            $slug = Str::slug($category['name']);
                        @endphp
                        <a href="#{{ $slug }}"
                            @click.prevent="document.getElementById('{{ $slug }}').scrollIntoView({ behavior: 'smooth' }); activeCategory = '{{ $slug }}'"
                            :class="activeCategory === '{{ $slug }}' ?
                                'bg-black text-white shadow-premium scale-105' :
                                'bg-gray-50 text-gray-400 hover:text-gray-900 border border-transparent'"
                            class="flex-shrink-0 px-6 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all duration-300">
                            {{ $category['name'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="container mx-auto px-6 max-w-7xl pt-16">
            <!-- Popular Items Section -->
            <section class="mb-24">
                <div class="mb-12 px-2">
                    <p class="text-amber-600 text-[10px] font-black uppercase tracking-widest">customer top picks</p>
                    <h2 class="text-4xl md:text-6xl font-black text-gray-900 tracking-tight leading-tight">Popular
                        Orders</h2>
                </div>
                <x-featured-menu title="" :items="$featuredItems ?? []" />
            </section>

            <!-- Menu Sections -->
            <div class="space-y-32 mb-32">
                @forelse(($menuCategories ?? []) as $category)
                    <div id="{{ Str::slug($category['name']) }}" class="scroll-mt-40 menu-section">
                        {{-- Custom Section Header for Menu --}}
                        <div class="flex items-end justify-between gap-8 mb-12 px-2 border-b border-gray-100 pb-8">
                            <div class="space-y-2">
                                <h3 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tight">
                                    {{ $category['name'] }}</h3>
                            </div>

                        </div>

                        <x-menu :title="''" :items="$category['items']" />
                    </div>
                @empty
                    <div class="text-center py-24 bg-gray-50 rounded-[40px] border border-dashed border-gray-200">
                        <p class="text-gray-400 font-bold">No menu categories available yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </main>

    <x-locations />



</x-layouts.app-main>
