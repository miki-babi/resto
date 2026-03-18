@props([
    'title' => 'Appetizers',
    'subtitle' => null,
    'items' => [],
])

<section x-data="{
    isOpen: false,
    item: { title: '', price: '', description: '', images: [], variants: [], addons: [] },
    activeImage: 0,
    next() { this.activeImage = (this.activeImage + 1) % this.item.images.length },
    prev() { this.activeImage = (this.activeImage - 1 + this.item.images.length) % this.item.images.length }
}" @open-menu-modal="item = $event.detail; isOpen = true; activeImage = 0"
    @keydown.escape.window="isOpen = false" class="max-w-7xl mx-auto px-4 md:px-8 py-12">
    
    <div class="flex flex-col mb-10 text-left">
        <h2 class="text-4xl md:text-5xl font-black font-serif text-slate-900 dark:text-white mb-2 tracking-tight flex items-center justify-start">
            {{ $title }}
        </h2>
        @if($subtitle)
            <p class="text-slate-500 dark:text-slate-400 text-lg font-medium italic">
                {{ $subtitle }}
            </p>
        @endif
    </div>

    <div class="grid grid-cols-2 gap-2 md:grid-cols-3 md:gap-4 lg:grid-cols-4">
        @forelse($items as $menuItem)
            <x-menu-item :title="$menuItem['title'] ?? 'Menu item'" :price="$menuItem['price'] ?? ''" :description="$menuItem['description'] ?? ''" :images="$menuItem['images'] ?? []" :variants="$menuItem['variants'] ?? []"
                :addons="$menuItem['addons'] ?? []" />
        @empty
            <div class="col-span-full text-center py-12 text-slate-500 dark:text-slate-400">
                No items available in this category.
            </div>
        @endforelse
    </div>

    {{-- Lightbox Overlay --}}
    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[200] flex flex-col items-center justify-center bg-black/95 p-4 md:p-8" 
         x-cloak>
        
        <div class="relative w-full max-w-4xl flex flex-col items-center overflow-y-auto max-h-screen scrollbar-hide">
            
            {{-- Image Section with Floating Controls --}}
            <div class="relative w-full aspect-square md:aspect-auto md:h-[70vh] flex items-center justify-center bg-transparent group">
                <template x-if="item.images && item.images.length">
                    <div class="relative h-full w-full flex items-center justify-center">
                        <img :src="item.images[activeImage]" 
                             class="max-h-full max-w-full object-contain transition-opacity duration-300"
                             :key="activeImage">
                        
                        {{-- Floating Close Button (Matches Screenshot) --}}
                        <button @click="isOpen = false" 
                                class="absolute top-4 right-4 z-50 flex h-8 w-8 items-center justify-center rounded-full bg-white text-black shadow-lg hover:bg-slate-200 transition-colors">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        {{-- Floating Navigation Arrows (Matches Screenshot) --}}
                        <template x-if="item.images.length > 1">
                            <div class="absolute inset-0 flex items-center justify-between px-2">
                                <button @click="prev()" class="bg-white text-black rounded-full h-8 w-8 flex items-center justify-center shadow-lg hover:bg-slate-200 transition-colors">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <button @click="next()" class="bg-white text-black rounded-full h-8 w-8 flex items-center justify-center shadow-lg hover:bg-slate-200 transition-colors">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            {{-- Details Section Below Image --}}
            <div class="w-full max-w-2xl mt-6 px-4 pb-12 text-center text-white">
                <div class="mb-4">
                    <h2 class="text-3xl md:text-4xl font-black mb-1" x-text="item.title"></h2>
                    <div class="text-xl font-bold text-amber-500" x-text="item.price"></div>
                </div>

                <p class="text-slate-300 text-lg leading-relaxed mb-6" x-text="item.description"></p>

                {{-- Variants & Add-ons in a compact grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left">
                    <template x-if="item.variants && item.variants.length">
                        <div class="bg-white/5 p-4 rounded-2xl border border-white/10">
                            <h3 class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-3">Variants</h3>
                            <div class="flex flex-col gap-2">
                                <template x-for="(variant, index) in item.variants" :key="index">
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="font-medium text-slate-200" x-text="variant.name"></span>
                                        <span class="text-amber-500 font-bold" x-text="variant.price"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <template x-if="item.addons && item.addons.length">
                        <div class="bg-white/5 p-4 rounded-2xl border border-white/10">
                            <h3 class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-3">Add-ons</h3>
                            <div class="flex flex-col gap-2">
                                <template x-for="(addon, index) in item.addons" :key="index">
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="font-medium text-slate-200" x-text="addon.name"></span>
                                        <span class="text-slate-400 font-bold" x-text="addon.price"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</section>
