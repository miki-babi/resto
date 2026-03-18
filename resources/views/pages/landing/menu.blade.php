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

    <main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-10" x-data="{
        activeCategory: '',
        init() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.activeCategory = entry.target.id;
                        // Scroll the category bar to keep the active pill visible
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

        <!-- Horizontal Category Selector -->
        <div
            class="sticky top-[72px] z-40 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md -mx-4 px-4 sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8 py-4 mb-8 border-b border-slate-100 dark:border-slate-800">
            <div id="category-scroll-container" class="flex items-center gap-3 overflow-x-auto pb-2 scrollbar-hide">
                <!-- Search Icon -->
                {{-- <button class="flex-shrink-0 w-10 h-10 rounded-full border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button> --}}

                @foreach ($menuCategories ?? [] as $category)
                    @php
                        $slug = Str::slug($category['name']);
                        // Map common categories to emojis if they don't have one
$emoji = '';
$name = $category['name'];
if (stripos($name, 'taco') !== false) {
    $emoji = ' 🌮';
} elseif (stripos($name, 'burrito') !== false) {
    $emoji = ' 🌯';
} elseif (stripos($name, 'drink') !== false) {
    $emoji = ' 🥤';
} elseif (stripos($name, 'coffee') !== false) {
    $emoji = ' ☕';
} elseif (stripos($name, 'dessert') !== false) {
    $emoji = ' 🍰';
} elseif (stripos($name, 'burger') !== false) {
    $emoji = ' 🍔';
} elseif (stripos($name, 'pizza') !== false) {
    $emoji = ' 🍕';
} elseif (stripos($name, 'salad') !== false) {
    $emoji = ' 🥗';
                        }
                    @endphp
                    <a href="#{{ $slug }}"
                        @click.prevent="document.getElementById('{{ $slug }}').scrollIntoView({ behavior: 'smooth' }); activeCategory = '{{ $slug }}'"
                        :class="activeCategory === '{{ $slug }}' ?
                            'bg-slate-900 text-white dark:bg-white dark:text-slate-900' :
                            'bg-white text-slate-900 border border-slate-200 hover:bg-slate-50 dark:bg-slate-800 dark:text-white dark:border-slate-700'"
                        class="flex-shrink-0 px-5 py-2 rounded-full text-sm font-bold whitespace-nowrap transition-all duration-200 shadow-sm">
                        {{ $category['name'] }}{{ $emoji }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Popular Items Section -->
        <section class="mb-16">
            <x-featured-menu title="Popular orders" :items="$featuredItems ?? []" />
        </section>

        <!-- Menu Sections -->
        @forelse(($menuCategories ?? []) as $category)
            <div id="{{ Str::slug($category['name']) }}" class="scroll-mt-32 menu-section">
                <x-menu :title="$category['name']" :items="$category['items']" />
            </div>
        @empty
            <div class="text-slate-500 dark:text-slate-400">
                No menu categories available yet.
            </div>
        @endforelse
    </main>
    <!-- END: AwardsSection -->


    <x-locations />



</x-layouts.app-main>
