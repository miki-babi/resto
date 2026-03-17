<section x-data="{
    isOpen: false,
    item: {},
    activeImage: 0,
    next() { this.activeImage = (this.activeImage + 1) % this.item.images.length },
    prev() { this.activeImage = (this.activeImage - 1 + this.item.images.length) % this.item.images.length }
}" @open-menu-modal="item = $event.detail; isOpen = true; activeImage = 0"
    @keydown.escape.window="isOpen = false" class="mb-16">
    <div class="flex items-center mb-8 border-l-4 border-metroDark pl-4">
        <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white uppercase tracking-tight">
            Appetizers</h2>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-menu-item />
        <x-menu-item />
        <x-menu-item />
        <x-menu-item />
    </div>

    <div x-show="isOpen" x-cloak class="fixed inset-0 z-[150] overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">

        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity"></div>

        <div class="flex min-h-screen items-center justify-center p-4" @click.self="isOpen = false">
            <div
                class="relative w-full max-w-2xl overflow-hidden rounded-3xl bg-white dark:bg-slate-900 shadow-2xl transition-all">

                {{-- Close Button --}}
                <button @click="isOpen = false"
                    class="absolute right-4 top-4 z-50 rounded-full bg-black/20 p-2 text-white hover:bg-black/40 transition">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                {{-- Carousel Section --}}
                <div class="relative h-64 md:h-80 bg-slate-200">
                    <template x-if="item.images">
                        <div class="h-full w-full">
                            <img :src="item.images[activeImage]"
                                class="h-full w-full object-cover transition-all duration-500">

                            {{-- Arrows --}}
                            <template x-if="item.images.length > 1">
                                <div class="absolute inset-0 flex items-center justify-between px-4">
                                    <button @click="prev()"
                                        class="rounded-full bg-white/20 p-2 text-white hover:bg-white/40">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </button>
                                    <button @click="next()"
                                        class="rounded-full bg-white/20 p-2 text-white hover:bg-white/40">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </div>
                            </template>

                            {{-- Dots indicator --}}
                            <div class="absolute bottom-4 left-0 right-0 flex justify-center space-x-2">
                                <template x-for="(img, index) in item.images" :key="index">
                                    <div :class="activeImage === index ? 'bg-white w-4' : 'bg-white/50 w-2'"
                                        class="h-2 rounded-full transition-all"></div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Content Section --}}
                <div class="p-8">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-3xl font-bold text-slate-900 dark:text-white" x-text="item.title"></h2>
                    </div>
                    <span class="text-2xl font-black text-metro-red" x-text="item.price"></span>
                    <p class="text-slate-600 dark:text-slate-400 text-lg leading-relaxed mb-6"
                        x-text="item.description"></p>


                </div>
            </div>
        </div>
    </div>
</section>
