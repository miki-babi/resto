







<x-layouts.app-main>
    {{-- 1. Push SEO tags specifically for Home --}}
    @push('meta')
        <meta name="description"
            content="Experience the heart of Bisrate Gebriel at Mera Coffee. A cozy coffee shop perfect for relaxing, working, and enjoying fine brews.">
        <meta property="og:title" content="Mera Coffee | Cozy Coffee Shop in Bisrate Gebriel">
        <meta property="og:image" content="https://images.pexels.com/photos/302902/pexels-photo-302902.jpeg">
        {{-- ... add other meta tags here ... --}}
    @endpush

    <main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-10">
        <!-- Page Title -->
        <div class="mb-12">
            <h1 class="text-5xl md:text-6xl font-black text-slate-900 dark:text-white tracking-tighter mb-4">Our
                Menu</h1>
            <p class="text-lg text-slate-500 dark:text-slate-400 max-w-2xl font-medium">Freshly made pizza and
                appetizers since 1980. Every dish is crafted with locally sourced ingredients and a whole lot of
                love.</p>
        </div> 
        <!-- Popular Items Section -->
        <section class="mb-16">
            <div class="flex items-center justify-between mb-8 border-l-4 border-metroDark pl-4">
                <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white uppercase tracking-tight">Top Picks
                </h2>
                <div class="flex gap-2">
                    <button
                        class="p-2 rounded-full bg-white dark:bg-slate-800 shadow-sm border border-slate-200 dark:border-slate-700 items-center justify-center">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                    <button
                        class="p-2 rounded-full bg-white dark:bg-slate-800 shadow-sm border border-slate-200 dark:border-slate-700">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </button>
                </div>
            </div>
            <x-featured-menu />
        </section>
        <!-- Appetizers Section -->
        <x-menu />
        <x-menu />
        <x-menu />
        <x-menu />
        <x-menu />
    </main>
    <!-- END: AwardsSection -->


    <x-locations />

  

</x-layouts.app-main>
