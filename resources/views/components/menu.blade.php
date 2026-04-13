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
}" @open-menu-modal="item = $event.detail; isOpen = true; activeImage = 0">
    <div class="flex flex-col gap-3 md:gap-4 max-w-3xl mx-auto">
        @forelse($items as $menuItem)
            <x-menu-item 
                :title="$menuItem['title'] ?? 'Menu item'" 
                :price="$menuItem['price'] ?? ''" 
                :description="$menuItem['description'] ?? ''" 
                :images="$menuItem['images'] ?? []" 
                :variants="$menuItem['variants'] ?? []"
                :addons="$menuItem['addons'] ?? []" 
            />
        @empty
            <div class="col-span-full text-center py-20 bg-orange-50/50 rounded-[3rem] border-2 border-dashed border-orange-100">
                <p class="text-orange-950/40 font-bold text-lg">No items available in this category.</p>
            </div>
        @endforelse
    </div>

    {{-- Lightbox Overlay --}}
    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 backdrop-blur-0"
         x-transition:enter-end="opacity-100 backdrop-blur-xl"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 backdrop-blur-xl"
         x-transition:leave-end="opacity-0 backdrop-blur-0"
         class="fixed inset-0 z-[200] flex items-center justify-center bg-black/90 p-0 md:p-8" 
         x-cloak>
        
        <div class="relative w-full h-full max-w-4xl bg-white md:rounded-[2.5rem] overflow-y-auto scrollbar-hide flex flex-col"
             @click.away="isOpen = false">
            
            {{-- Close Button (Top Right) --}}
            <button @click="isOpen = false" 
                    class="absolute top-4 right-4 z-[210] flex h-10 w-10 items-center justify-center rounded-full bg-white shadow-xl text-gray-900 transition-all active:scale-90">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            {{-- Image Hero Section --}}
            <div class="relative w-full aspect-square md:aspect-[4/3] flex-shrink-0 bg-gray-100 group">
                <template x-if="item.images && item.images.length">
                    <div class="relative h-full w-full">
                        <img :src="item.images[activeImage]" 
                             class="h-full w-full object-cover transition-all duration-700"
                             :key="activeImage">
                        
                        {{-- Navigation Arrows --}}
                        <template x-if="item.images.length > 1">
                            <div class="absolute inset-0 flex items-center justify-between px-4">
                                <button @click.stop="prev()" class="bg-white text-gray-900 rounded-full h-10 w-10 flex items-center justify-center shadow-lg transition-all active:scale-90">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <button @click.stop="next()" class="bg-white text-gray-900 rounded-full h-10 w-10 flex items-center justify-center shadow-lg transition-all active:scale-90">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            {{-- Details Section (Below Image) --}}
            <div class="p-8 md:p-12">
                <div class="flex flex-col md:flex-row md:items-baseline justify-between gap-4 mb-6">
                    <h2 class="text-3xl md:text-4xl font-black text-gray-900 tracking-tight" x-text="item.title"></h2>
                    <div class="text-2xl font-bold text-gray-900" x-text="item.price.includes('ETB') ? item.price : 'ETB ' + item.price"></div>
                </div>

                <p class="text-gray-500 text-lg font-medium leading-relaxed mb-10" x-text="item.description"></p>

                {{-- Variants & Add-ons --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <template x-if="item.variants && item.variants.length">
                        <div class="bg-gray-50 p-6 rounded-3xl border border-gray-100">
                            <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-4">Select Option</h3>
                            <div class="space-y-4">
                                <template x-for="(variant, index) in item.variants" :key="index">
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0 hover:text-amber-700 transition-colors cursor-pointer group">
                                        <span class="font-bold text-gray-700 text-base" x-text="variant.name"></span>
                                        <span class="font-bold text-gray-900" x-text="variant.price.includes('ETB') ? variant.price : 'ETB ' + variant.price"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <template x-if="item.addons && item.addons.length">
                        <div class="bg-gray-50 p-6 rounded-3xl border border-gray-100">
                            <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-4">Add Extras</h3>
                            <div class="space-y-4">
                                <template x-for="(addon, index) in item.addons" :key="index">
                                    <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0 hover:text-amber-700 transition-colors cursor-pointer group">
                                        <span class="font-bold text-gray-700 text-base" x-text="addon.name"></span>
                                        <span class="font-bold text-gray-900" x-text="addon.price.includes('ETB') ? addon.price : 'ETB ' + addon.price"></span>
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

