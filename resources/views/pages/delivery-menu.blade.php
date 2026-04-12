<x-layouts.app-main>
    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
        
        .shadow-premium {
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.08), 0 4px 10px -5px rgba(0,0,0,0.03);
        }
        .shadow-modal {
            box-shadow: 0 30px 60px -12px rgba(50, 50, 93, 0.25), 0 18px 36px -18px rgba(0, 0, 0, 0.3);
        }
    </style>
    @push('meta')
        <meta name="description" content="Same-day express delivery ordering menu">
        <meta property="og:title" content="Express Delivery Menu">
    @endpush

    @php
        $isCakePreorder = ($preorderSource ?? 'menu') === 'cake';
        $submitRouteName = $preorderSubmitRoute ?? 'preorder.menu.submit';
        $stepOneLabel = 'Menu';
        $pageTitle = 'Express Delivery Menu';
        $searchPlaceholder = 'Search for items...';
        $pastryItems = collect($pastryItems ?? [])->values();

        $popularItems = collect($menuCategories ?? [])
            ->flatMap(fn ($category) => $category->items)
            ->take(10)
            ->values();

        $oldQuantities = old('quantities', []);
        if (!is_array($oldQuantities)) {
            $oldQuantities = [];
        }

        $oldVariantIds = old('variant_ids', []);
        if (!is_array($oldVariantIds)) {
            $oldVariantIds = [];
        }

        $oldAddonIds = old('addon_ids', []);
        if (!is_array($oldAddonIds)) {
            $oldAddonIds = [];
        }

        $hasDetailsErrors = $errors->hasAny([
            'phone',
            'delivery_address',
        ]);
    @endphp

    <section class="min-h-screen bg-white py-6 pb-28 lg:pb-6">
        <div class="mx-auto max-w-[1500px] px-4 lg:px-6">
            @if (session('success'))
                <div class="mb-4 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                method="POST"
                action="{{ route($submitRouteName) }}"
                data-menu-items='@json($menuItemsCatalog ?? [])'
                data-pickup-details-storage-key="resto.preorder.pickup-details"
                data-initial-order-type="delivery"
                data-force-order-type="delivery"
                data-old-phone="{{ old('phone') }}"
                data-old-quantities='@json($oldQuantities)'
                data-old-variant-ids='@json($oldVariantIds)'
                data-old-addon-ids='@json($oldAddonIds)'
                data-initial-step="{{ $hasDetailsErrors ? 2 : 1 }}"
                    x-data="{
                        ...pickupSelectorFromEl($el),
                        modalOpen: false,
                        selectedItem: null,
                        activeCategory: '{{ $isCakePreorder ? 'pastries' : 'popular' }}',
                        openModal(id) {
                            this.selectedItem = this.menuItemsMap[String(id)];
                            this.modalOpen = true;
                        },
                        closeModal() {
                            this.modalOpen = false;
                            this.selectedItem = null;
                        },
                        goToReview() {
                            if (!this.detailsComplete()) {
                                this.detailsError = 'Please provide your phone and delivery address.';
                                return;
                            }
                            this.detailsError = '';
                            this.step = 3;
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        },
                        backToDetails() {
                            this.step = 2;
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        }
                    }"
                x-init="init()"
            >
                @csrf

                <input type="hidden" name="phone" :value="phone">
                <input type="hidden" name="order_type" :value="orderType">
                <input type="hidden" name="delivery_address" :value="address">
                <template x-for="item in menuItems" :key="`qty-${item.id}`">
                    <input type="hidden" :name="`quantities[${item.id}]`" :value="quantityFor(item.id)">
                </template>
                <template x-for="item in menuItems" :key="`variant-${item.id}`">
                    <input type="hidden" :name="`variant_ids[${item.id}]`" :value="selectedVariants[String(item.id)] || ''">
                </template>
                <template x-for="item in menuItems" :key="`addon-${item.id}`">
                    <template x-for="addonId in ensureAddonSelection(item.id)" :key="`addon-${item.id}-${addonId}`">
                        <input type="hidden" :name="`addon_ids[${item.id}][]`" :value="addonId">
                    </template>
                </template>

                <div class="grid gap-6 lg:grid-cols-[200px_minmax(0,1fr)_340px]">
                    <aside class="hidden lg:block lg:sticky lg:top-24 h-fit border-r border-gray-100 pr-6">
                        <div class="mb-4 px-3">
                            <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">Categories</h3>
                        </div>
                        <nav class="space-y-1">
                            @if ($isCakePreorder)
                                <button type="button"
                                        @click="activeCategory = 'pastries'; document.getElementById('pastries')?.scrollIntoView({ behavior: 'smooth', block: 'start' })"
                                        class="w-full text-left px-3 py-2.5 text-sm font-bold rounded-xl transition-all"
                                        :class="activeCategory === 'pastries' ? 'bg-black text-white shadow-premium scale-[1.02]' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'">
                                    Pastries
                                </button>
                            @else
                                <button type="button" 
                                        @click="activeCategory = 'popular'; document.getElementById('popular')?.scrollIntoView({ behavior: 'smooth', block: 'start' })"
                                        class="w-full text-left px-3 py-2.5 text-sm font-bold rounded-xl transition-all"
                                        :class="activeCategory === 'popular' ? 'bg-black text-white shadow-premium scale-[1.02]' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'">
                                    🔥 Popular
                                </button>
                                @foreach ($menuCategories as $category)
                                    @php $slug = \Illuminate\Support\Str::slug($category->name); @endphp
                                    <button type="button"
                                            @click="activeCategory = '{{ $slug }}'; document.getElementById('category-{{ $slug }}')?.scrollIntoView({ behavior: 'smooth', block: 'start' })"
                                            class="w-full text-left px-3 py-2.5 text-sm font-bold rounded-xl transition-all"
                                            :class="activeCategory === '{{ $slug }}' ? 'bg-black text-white shadow-premium scale-[1.02]' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900'">
                                        {{ $category->name }}
                                    </button>
                                @endforeach
                            @endif
                        </nav>
                    </aside>

                    <main class="min-w-0">
                        <header class="mb-8">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div>
                                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 tracking-tight">{{ $pageTitle }}</h1>
                                    <div class="mt-2 flex items-center gap-4">
                                        <div class="flex items-center gap-2">
                                            <span class="flex h-6 w-6 items-center justify-center rounded-full text-[10px] font-bold transition-colors"
                                                :class="step >= 1 ? 'bg-black text-white' : 'bg-gray-200 text-gray-500'">1</span>
                                            <span class="text-xs font-semibold uppercase tracking-wider" :class="step >= 1 ? 'text-black' : 'text-gray-400'">{{ $stepOneLabel }}</span>
                                        </div>
                                        <div class="h-px w-8" :class="step >= 2 ? 'bg-black' : 'bg-gray-200'"></div>
                                        <div class="flex items-center gap-2">
                                            <span class="flex h-6 w-6 items-center justify-center rounded-full text-[10px] font-bold transition-colors"
                                                :class="step >= 2 ? 'bg-black text-white' : 'bg-gray-200 text-gray-500'">2</span>
                                            <span class="text-xs font-semibold uppercase tracking-wider" :class="step >= 2 ? 'text-black' : 'text-gray-400'">Details</span>
                                        </div>
                                        <div class="h-px w-8" :class="step >= 3 ? 'bg-black' : 'bg-gray-200'"></div>
                                        <div class="flex items-center gap-2">
                                            <span class="flex h-6 w-6 items-center justify-center rounded-full text-[10px] font-bold transition-colors"
                                                :class="step >= 3 ? 'bg-black text-white' : 'bg-gray-200 text-gray-500'">3</span>
                                            <span class="text-xs font-semibold uppercase tracking-wider" :class="step >= 3 ? 'text-black' : 'text-gray-400'">Order</span>
                                        </div>
                                    </div>
                                </div>

                                 <div class="inline-flex rounded-xl bg-gray-100 p-1 w-42 ">
                                    <a  href="{{ route('preorder.menu') }}" class="rounded-lg px-4 py-1.5 text-xs font-bold text-gray-400 " >
                                        Pickup
                                    </a>
                                    <a href="{{ route('delivery.menu') }}"   class="rounded-lg bg-white px-4 py-1.5 text-xs font-bold text-gray-900 shadow-sm transition-all">
                                        Delivery
                                    </a>
                                </div>
                            </div>

                            <div class="mt-6 relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input
                                    type="text"
                                    x-model="search"
                                    placeholder="{{ $searchPlaceholder }}"
                                    class="block w-full rounded-2xl border-none bg-gray-100 py-3 pl-10 pr-3 text-sm focus:ring-2 focus:ring-black placeholder:text-gray-500 transition-all font-medium"
                                />
                            </div>
                        </header>

                        <div x-show="step === 1" x-cloak>
                            @if ($isCakePreorder)
                                <section id="pastries" class="pt-2 mb-12">
                                    <h2 class="text-xl font-bold text-gray-900 mb-6">Pastry Items</h2>
                                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
                                        @forelse ($pastryItems as $item)
                                            @php
                                                $image = $item->getFirstMediaUrl('cover_image');
                                                if (!$image) {
                                                    $image = $item->getFirstMediaUrl('gallery');
                                                }
                                                if (!$image) {
                                                    $image = 'https://placehold.co/600x600?text=' . urlencode($item->name);
                                                }
                                                $plainDescription = trim(strip_tags((string) ($item->description ?? '')));
                                            @endphp

                                            <article
                                                data-title="{{ $item->name }}"
                                                data-description="{{ $plainDescription }}"
                                                x-show="match($el.dataset.title, $el.dataset.description)"
                                                class="group flex flex-col h-full bg-white transition-all"
                                            >
                                                <div class="relative overflow-hidden rounded-2xl aspect-square bg-gray-50 border border-gray-100">
                                                    <img
                                                        src="{{ $image }}"
                                                        alt="{{ $item->name }}"
                                                        class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                                                        loading="lazy"
                                                    >
                                                    <button
                                                        type="button"
                                                        @click="increaseQuantity({{ $item->id }})"
                                                        class="absolute bottom-2 right-2 h-9 w-9 flex items-center justify-center rounded-xl bg-white shadow-lg text-gray-900 transition-transform hover:scale-110 active:scale-95"
                                                        :class="quantityFor({{ $item->id }}) > 0 ? 'bg-black text-white' : ''"
                                                        title="Add {{ $item->name }}"
                                                    >
                                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                                        </svg>
                                                    </button>
                                                    <div
                                                        x-show="quantityFor({{ $item->id }}) > 0"
                                                        x-transition.scale
                                                        class="absolute top-2 right-2 h-6 min-w-[24px] px-1.5 flex items-center justify-center rounded-full bg-black text-[10px] font-bold text-white shadow-lg"
                                                        x-text="quantityFor({{ $item->id }})"
                                                    ></div>
                                                </div>

                                                <div class="mt-3 flex flex-col flex-grow">
                                                    <h3 class="text-sm font-bold text-gray-900 leading-tight line-clamp-2 group-hover:text-black transition-colors">
                                                        {{ $item->name }}
                                                    </h3>
                                                    <p class="mt-1 text-xs font-semibold text-gray-500">${{ number_format((float) $item->price, 2) }}</p>
                                                </div>
                                            </article>
                                        @empty
                                            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800 md:col-span-2">
                                                No pastry items are currently available for preorder.
                                            </div>
                                        @endforelse
                                    </div>
                                </section>
                            @else
                                {{-- Popular Section --}}
                                <section id="popular" class="pt-2 mb-12">
                                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                                        <svg class="h-5 w-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        Popular Items
                                    </h2>
                                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
                                        @foreach ($popularItems as $item)
                                            @include('components.menu-item-card', ['item' => $item])
                                        @endforeach
                                    </div>
                                </section>

                                {{-- Categories Sections --}}
                                @foreach ($menuCategories as $category)
                                    @php $slug = \Illuminate\Support\Str::slug($category->name); @endphp
                                    <section id="category-{{ $slug }}" class="pt-4 mb-12">
                                        <h2 class="text-xl font-bold text-gray-900 mb-6 capitalize">{{ $category->name }}</h2>
                                        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
                                            @foreach ($category->items as $item)
                                                @include('components.menu-item-card', ['item' => $item])
                                            @endforeach
                                        </div>
                                    </section>
                                @endforeach
                            @endif
                        </div>

                        {{-- Step 2: Pickup Details --}}
                        <div x-show="step === 2" x-cloak class="pb-20" x-init="orderType = 'delivery'">
                            <div class="mb-8">
                                <h2 class="text-3xl font-black text-gray-900 tracking-tight">Delivery Details</h2>
                                <p class="mt-2 text-gray-500 font-medium">Where should we bring your meal?</p>
                            </div>

                            <div class="space-y-8 p-10 rounded-[32px] bg-white border border-gray-100 shadow-premium">
                                <div>
                                    <label class="text-xs font-black uppercase tracking-widest text-gray-900 mb-4 block">Phone Number</label>
                                    <input type="text" x-model="phone" placeholder="+251 912 345 678" 
                                           class="w-full rounded-2xl border-gray-100 bg-gray-50 py-5 px-6 text-lg focus:ring-2 focus:ring-black focus:border-black font-bold transition-all placeholder:text-gray-300">
                                    <p x-show="!phone" class="mt-3 text-xs font-bold text-amber-600 flex items-center gap-1.5">
                                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
                                        Required for order updates & notification
                                    </p>
                                </div>

                                <div class="h-px bg-gray-50"></div>

                                <div>
                                    <label class="text-xs font-black uppercase tracking-widest text-gray-900 mb-4 block">Delivery Address</label>
                                    
                                    <template x-if="pastAddresses.length > 0">
                                        <div class="mb-4 flex flex-wrap gap-2">
                                            <template x-for="pastAddress in pastAddresses" :key="pastAddress">
                                                <button type="button" 
                                                        @click="address = pastAddress"
                                                        class="px-3 py-1.5 rounded-xl border border-gray-100 bg-white text-xs font-bold text-gray-600 hover:border-black hover:text-black transition-all"
                                                        x-text="pastAddress">
                                                </button>
                                            </template>
                                        </div>
                                    </template>

                                    <textarea x-model="address" placeholder="Enter your full delivery address..." rows="3"
                                              class="w-full rounded-2xl border-gray-100 bg-gray-50 py-5 px-6 text-lg focus:ring-2 focus:ring-black focus:border-black font-bold transition-all placeholder:text-gray-300"></textarea>
                                    <p class="mt-3 text-xs font-bold text-gray-400">Same-day express delivery is prepared immediately.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Step 3: Final Review --}}
                        <div x-show="step === 3" x-cloak class="pb-20">
                            <div class="mb-8 flex items-center justify-between">
                                <div>
                                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">Review Order</h2>
                                    <p class="mt-2 text-gray-500 font-medium">Final check before we prepare your order.</p>
                                </div>
                                <button type="button" @click="backToDetails()" class="text-sm font-bold text-gray-900 underline underline-offset-4 hover:text-black">Edit Details</button>
                            </div>

                            <div class="space-y-4" x-show="cartItemCount() > 0">
                                <template x-for="item in cartItems()" :key="item.id">
                                    <div class="group flex items-center justify-between py-6 border-b border-gray-100 last:border-0">
                                        <div class="flex items-center gap-5">
                                            <div class="h-20 w-20 shrink-0 overflow-hidden rounded-2xl bg-gray-50 border border-gray-100">
                                                <img :src="item.imageUrl" :alt="item.title" class="h-full w-full object-cover">
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-xs font-black bg-gray-100 text-gray-900 px-2 py-0.5 rounded-lg" x-text="`${item.quantity}x` "></span>
                                                    <h3 class="text-lg font-bold text-gray-900" x-text="item.title"></h3>
                                                </div>
                                                <div class="mt-1 flex flex-wrap gap-x-3 gap-y-1">
                                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest" x-show="item.variantName" x-text="item.variantName"></p>
                                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest" x-show="item.addonNames.length" x-text="item.addonNames.join(', ')"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-black text-gray-900" x-text="formatMoney(item.lineTotal)"></p>
                                        </div>
                                    </div>
                                </template>

                                <div class="mt-12 p-10 rounded-3xl bg-gray-50 flex items-center justify-between border border-gray-100">
                                    <div>
                                        <p class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Total Amount</p>
                                        <p class="text-4xl font-black text-gray-900 tracking-tighter" x-text="formatMoney(cartSubtotal())"></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-1 text-right">Deliver to</p>
                                        <p class="text-sm font-bold text-gray-900" x-text="address"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </main>

                    <aside :class="step >= 2 ? 'block' : 'hidden lg:block'" class="lg:sticky lg:top-24 h-fit lg:border-l lg:border-gray-100 lg:pl-6">
                         <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-bold text-gray-900">Your Order</h2>
                            <span class="text-xs font-bold text-gray-400 bg-gray-100 px-2 py-1 rounded-md" x-text="`${cartItemCount()} items`"></span>
                        </div>

                        <p x-show="cartError" class="mb-4 rounded-xl border border-red-100 bg-red-50 px-3 py-2 text-xs font-medium text-red-700" x-text="cartError"></p>
                        <p x-show="detailsError" class="mb-4 rounded-xl border border-red-100 bg-red-50 px-3 py-2 text-xs font-medium text-red-700" x-text="detailsError"></p>

                        <div class="space-y-4 max-h-[50vh] overflow-y-auto pr-2 custom-scrollbar">
                            <template x-for="item in cartItems()" :key="item.id">
                                <div class="group relative flex items-start gap-3 rounded-2xl border border-gray-100 p-3 hover:border-gray-200 transition-colors">
                                    <div class="h-12 w-12 shrink-0 overflow-hidden rounded-lg bg-gray-50">
                                        <img :src="item.imageUrl" :alt="item.title" class="h-full w-full object-cover">
                                    </div>
                                    <div class="flex-grow min-w-0">
                                        <div class="flex justify-between items-start">
                                            <p class="text-sm font-bold text-gray-900 truncate" x-text="item.title"></p>
                                            <p class="text-sm font-bold text-gray-900" x-text="formatMoney(item.lineTotal)"></p>
                                        </div>
                                        <div class="flex flex-wrap gap-1 mt-1">
                                            <span x-show="item.variantName" class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter" x-text="item.variantName"></span>
                                            <span x-show="item.addonNames.length" class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter" x-text="item.addonNames.join(', ')"></span>
                                        </div>
                                        <div class="mt-2 flex items-center gap-3">
                                            <button type="button" @click="decreaseQuantity(item.id)" class="text-gray-400 hover:text-black transition-colors">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg>
                                            </button>
                                            <span class="text-xs font-bold text-gray-900 w-4 text-center" x-text="item.quantity"></span>
                                            <button type="button" @click="increaseQuantity(item.id)" class="text-gray-400 hover:text-black transition-colors">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <div x-show="cartItemCount() === 0" class="py-12 flex flex-col items-center justify-center text-center opacity-40">
                                <svg class="h-12 w-12 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <p class="text-sm font-medium">Your cart is empty</p>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-100">
                            {{-- Aside content for all steps --}}
                            <div class="space-y-6 pt-6">
                                {{-- Quick Summary --}}
                                <div class="space-y-4 p-6 rounded-3xl bg-gray-50 border border-gray-100 shadow-premium" x-show="cartItemCount() > 0">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest">Order Summary</h3>
                                        <span class="text-[10px] font-bold text-gray-400 bg-gray-200 px-2 py-0.5 rounded" x-text="`${cartItemCount()} Items`"></span>
                                    </div>
                                    <div class="text-2xl font-black text-gray-900 tracking-tighter" x-text="formatMoney(cartSubtotal())"></div>
                                </div>

                                {{-- Step 1 Actions --}}
                                <div x-show="step === 1" x-cloak>
                                    <button type="button" @click="goToDetails()" class="w-full rounded-2xl bg-black py-5 text-sm font-black text-white shadow-premium transition-all hover:scale-[1.02] active:scale-[0.98]">
                                        Add Details
                                    </button>
                                </div>

                                {{-- Step 2 Actions --}}
                                <div x-show="step === 2" x-cloak class="space-y-3">
                                    <button type="button" @click="goToReview()" :disabled="!detailsComplete()" 
                                            class="w-full rounded-2xl bg-black py-5 text-sm font-black text-white shadow-premium transition-all hover:scale-[1.02] active:scale-[0.98] disabled:bg-gray-200 disabled:text-gray-400 disabled:shadow-none disabled:scale-100">
                                        Review Order
                                    </button>
                                    <button type="button" @click="backToMenu()" class="w-full rounded-2xl border border-gray-100 py-4 text-sm font-bold text-gray-500 hover:bg-gray-50">Back to {{ $stepOneLabel }}</button>
                                </div>

                                {{-- Step 3 Actions --}}
                                <div x-show="step === 3" x-cloak class="space-y-3">
                                    <button type="button" @click="placePreorder()" 
                                            class="w-full rounded-2xl bg-black py-5 text-sm font-black text-white shadow-premium transition-all hover:scale-[1.02] active:scale-[0.98]">
                                        Confirm & Place Order
                                    </button>
                                    <button type="button" @click="backToDetails()" class="w-full rounded-2xl border border-gray-100 py-4 text-sm font-bold text-gray-500 hover:bg-gray-50">Back to Details</button>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>

                {{-- Customization Modal --}}
                <div x-show="modalOpen" 
                     x-cloak 
                     class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center p-4 overflow-hidden"
                     role="dialog" 
                     aria-modal="true"
                >
                    <div x-show="modalOpen" 
                         x-transition:enter="ease-out duration-300" 
                         x-transition:enter-start="opacity-0" 
                         x-transition:enter-end="opacity-100" 
                         x-transition:leave="ease-in duration-200" 
                         x-transition:leave-start="opacity-100" 
                         x-transition:leave-end="opacity-0" 
                         class="fixed inset-0 bg-black/60 backdrop-blur-sm" 
                         @click="closeModal()"
                    ></div>

                    <div x-show="modalOpen" 
                         x-transition:enter="transition ease-out duration-300 transform" 
                         x-transition:enter-start="translate-y-full sm:translate-y-0 sm:scale-95 sm:opacity-0" 
                         x-transition:enter-end="translate-y-0 sm:scale-100 sm:opacity-100" 
                         x-transition:leave="transition ease-in duration-200 transform" 
                         x-transition:leave-start="translate-y-0 sm:scale-100 sm:opacity-100" 
                         x-transition:leave-end="translate-y-full sm:translate-y-0 sm:scale-95 sm:opacity-0" 
                         class="relative w-full max-w-lg bg-white rounded-t-3xl sm:rounded-3xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col"
                    >
                        <template x-if="selectedItem">
                            <div class="flex flex-col h-full overflow-y-auto">
                                <div class="relative h-64 sm:h-72 shrink-0">
                                    <img :src="selectedItem.image_url" :alt="selectedItem.title" class="w-full h-full object-cover">
                                    <button @click="closeModal()" class="absolute top-4 right-4 h-10 w-10 flex items-center justify-center rounded-full bg-black/50 text-white backdrop-blur-md hover:bg-black/70 transition-colors">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                </div>
                                
                                <div class="p-6 pb-24">
                                    <h2 class="text-2xl font-black text-gray-900 tracking-tight" x-text="selectedItem.title"></h2>
                                    <p class="mt-1 text-lg font-bold text-gray-400" x-text="formatMoney(selectedItem.price)"></p>

                                    {{-- Variants --}}
                                    <template x-if="selectedItem.variants.length > 0">
                                        <div class="mt-8">
                                            <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4">Choose Variant</h3>
                                            <div class="space-y-2">
                                                <label class="flex items-center justify-between p-4 rounded-2xl border transition-all cursor-pointer"
                                                       :class="!selectedVariants[selectedItem.id] ? 'border-black bg-black/5' : 'border-gray-100 hover:border-gray-200'">
                                                    <div class="flex items-center gap-3">
                                                        <input type="radio" :name="`variant_${selectedItem.id}`" value="" x-model="selectedVariants[selectedItem.id]" class="text-black focus:ring-black">
                                                        <span class="text-sm font-bold" :class="!selectedVariants[selectedItem.id] ? 'text-black' : 'text-gray-600'">Base Version</span>
                                                    </div>
                                                    <span class="text-sm font-bold text-gray-400" x-text="formatMoney(selectedItem.price)"></span>
                                                </label>
                                                <template x-for="variant in selectedItem.variants" :key="variant.id">
                                                    <label class="flex items-center justify-between p-4 rounded-2xl border transition-all cursor-pointer"
                                                           :class="selectedVariants[selectedItem.id] == variant.id ? 'border-black bg-black/5' : 'border-gray-100 hover:border-gray-200'">
                                                        <div class="flex items-center gap-3">
                                                            <input type="radio" :name="`variant_${selectedItem.id}`" :value="variant.id" x-model="selectedVariants[selectedItem.id]" class="text-black focus:ring-black">
                                                            <span class="text-sm font-bold" :class="selectedVariants[selectedItem.id] == variant.id ? 'text-black' : 'text-gray-600'" x-text="variant.name"></span>
                                                        </div>
                                                        <span class="text-sm font-bold text-gray-400" x-text="formatMoney(variant.price)"></span>
                                                    </label>
                                                </template>
                                            </div>
                                        </div>
                                    </template>

                                    {{-- Addons --}}
                                    <template x-if="selectedItem.addons.length > 0">
                                        <div class="mt-8">
                                            <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4">Add Extra Toppings</h3>
                                            <div class="space-y-2">
                                                <template x-for="addon in selectedItem.addons" :key="addon.id">
                                                    <label class="flex items-center justify-between p-4 rounded-2xl border transition-all cursor-pointer"
                                                           :class="ensureAddonSelection(selectedItem.id).includes(String(addon.id)) ? 'border-black bg-black/5' : 'border-gray-100 hover:border-gray-200'">
                                                        <div class="flex items-center gap-3">
                                                            <input type="checkbox" :value="String(addon.id)" x-model="selectedAddons[selectedItem.id]" class="rounded text-black focus:ring-black">
                                                            <span class="text-sm font-bold" :class="ensureAddonSelection(selectedItem.id).includes(String(addon.id)) ? 'text-black' : 'text-gray-600'" x-text="addon.name"></span>
                                                        </div>
                                                        <span class="text-sm font-bold text-gray-400" x-text="`+${formatMoney(addon.price)}`"></span>
                                                    </label>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>

                        {{-- Footer Action --}}
                        <div class="absolute bottom-0 inset-x-0 p-6 bg-white border-t border-gray-100 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-4 bg-gray-100 rounded-2xl p-1 shrink-0">
                                <button type="button" @click="decreaseQuantity(selectedItem.id)" class="h-10 w-10 flex items-center justify-center rounded-xl hover:bg-white transition-all">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg>
                                </button>
                                <span class="text-sm font-black w-4 text-center" x-text="quantityFor(selectedItem.id)"></span>
                                <button type="button" @click="increaseQuantity(selectedItem.id)" class="h-10 w-10 flex items-center justify-center rounded-xl hover:bg-white transition-all">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                </button>
                            </div>
                            <button type="button" @click="closeModal()" class="flex-grow rounded-2xl bg-black py-4 text-sm font-bold text-white shadow-[0_10px_20px_-10px_rgba(0,0,0,0.5)] transition-all hover:scale-[1.02] active:scale-[0.98]">
                                Add to Order – <span x-text="formatMoney(lineTotalFor(selectedItem?.id))"></span>
                            </button>
                        </div>
                    </div>
                </div>

                <div x-show="step === 1" 
                     x-cloak
                     class="fixed inset-x-0 bottom-0 z-40 lg:hidden px-4 pb-6 pt-2 bg-gradient-to-t from-white via-white to-transparent">
                    <button type="button" 
                            @click="goToDetails()"
                            class="w-full flex items-center justify-between bg-black text-white p-4 rounded-2xl shadow-2xl transition-transform active:scale-95"
                    >
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-lg bg-white/20 flex items-center justify-center text-xs font-black" x-text="cartItemCount()"></div>
                            <div class="text-left">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-white/50">Your Cart</p>
                                <p class="text-sm font-black" x-text="formatMoney(cartSubtotal())"></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 font-bold text-sm">
                            <span>Review Order</span>
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </section>
</x-layouts.app-main>
