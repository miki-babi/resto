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
                -ms-overflow-style: none; /* IE and Edge */
                scrollbar-width: none; /* Firefox */
            }

            .category-pill-active {
                @apply bg-orange-600 text-white shadow-lg shadow-orange-600/20 scale-105;
            }

            .category-pill-inactive {
                @apply bg-white text-gray-500 hover:text-orange-600 border border-gray-100;
            }
        </style>
    @endpush

    {{-- Modern Menu Page --}}
    <main class="flex-1 bg-white" x-data="{
        activeCategory: 'all',
        init() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.activeCategory = entry.target.id;
                    }
                });
            }, { threshold: 0.2, rootMargin: '-10% 0px -70% 0px' });
    
            document.querySelectorAll('.menu-section').forEach(section => {
                observer.observe(section);
            });
        }
    }">

        <!-- Precision Category Selector & Search -->
        <div class="sticky top-0 z-40 bg-white/90 backdrop-blur-xl py-4">
            <div class="container mx-auto px-4 max-w-4xl">
                {{-- Category Tabs --}}
                <div id="category-scroll-container" class="flex items-center gap-3 overflow-x-auto pb-2 scrollbar-hide">
                    <a href="#"
                        @click.prevent="window.scrollTo({ top: 0, behavior: 'smooth' }); activeCategory = 'all'"
                        :class="activeCategory === 'all' ? 'bg-gray-900 text-white shadow-lg' : 'bg-white text-gray-400 border border-gray-100'"
                        class="flex-shrink-0 px-6 py-2.5 rounded-2xl text-sm font-bold transition-all duration-300 transform active:scale-95">
                        All
                    </a>
                    @foreach ($menuCategories ?? [] as $category)
                        @php
                            $slug = Str::slug($category['name']);
                        @endphp
                        <a href="#{{ $slug }}"
                            @click.prevent="document.getElementById('{{ $slug }}').scrollIntoView({ behavior: 'smooth' }); activeCategory = '{{ $slug }}'"
                            :class="activeCategory === '{{ $slug }}' ? 'bg-gray-900 text-white shadow-lg' : 'bg-white text-gray-400 border border-gray-100'"
                            class="flex-shrink-0 px-6 py-2.5 rounded-2xl text-sm font-bold transition-all duration-300 transform active:scale-95">
                            {{ $category['name'] }}
                        </a>
                    @endforeach
                </div>

                {{-- Search Bar --}}
                <div class="relative mt-6">
                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" placeholder="Search menu" class="w-full bg-white border border-gray-100 rounded-2xl py-4 pl-6 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-gray-100 transition-all font-medium shadow-sm">
                </div>
            </div>
        </div>

        <div class="container mx-auto px-4 max-w-4xl pt-8">
            <!-- Popular Items Section -->
            <section class="mb-12">
                <x-featured-menu title="" :items="$featuredItems ?? []" />
            </section>

            <!-- Menu Sections -->
            <div class="space-y-16 mb-24">
                @forelse(($menuCategories ?? []) as $category)
                    <div id="{{ Str::slug($category['name']) }}" class="scroll-mt-32 menu-section">
                        @if(!$loop->first || count($featuredItems) > 0)
                            <h3 class="text-2xl font-bold text-gray-900 mb-6 px-2">
                                {{ $category['name'] }}
                            </h3>
                        @endif

                        <x-menu :title="''" :items="$category['items']" />
                    </div>
                @empty
                    <div class="text-center py-32 bg-white/50 backdrop-blur-xl rounded-[3rem] border-2 border-dashed border-orange-100">
                        <div class="inline-flex h-20 w-20 items-center justify-center rounded-3xl bg-orange-50 text-orange-600 mb-6">
                            <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <p class="text-gray-400 font-bold text-xl tracking-tight">No menu categories available yet.</p>
                        <p class="text-gray-400 mt-2">Come back soon for our latest offerings.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </main>

    <x-locations />

</x-layouts.app-main>

