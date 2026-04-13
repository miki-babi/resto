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
        <meta
            name="description"
            content="{{ ($isDeliveryFlow ?? false) ? 'Same-day express delivery ordering menu' : (($preorderSource ?? 'menu') === 'cake' ? 'Place a preorder for cakes and pastries.' : 'Place a preorder for menu items.') }}"
        >
        <meta
            property="og:title"
            content="{{ ($isDeliveryFlow ?? false) ? 'Express Delivery Menu' : (($preorderSource ?? 'menu') === 'cake' ? 'Cake & Pastry Preorder' : 'Menu Preorder') }}"
        >
    @endpush

    @php
        $isDeliveryFlow = $isDeliveryFlow ?? false;
        $isCakePreorder = ! $isDeliveryFlow && (($preorderSource ?? 'menu') === 'cake');
        $submitRouteName = $preorderSubmitRoute ?? ($isDeliveryFlow ? 'delivery.submit' : 'preorder.menu.submit');
        $stepOneLabel = $isDeliveryFlow ? 'Menu' : ($isCakePreorder ? 'Pastries' : 'Menu');
        $pageTitle = $isDeliveryFlow ? 'Express Delivery Menu' : ($isCakePreorder ? 'Cake & Pastry Preorder' : 'Menu Preorder');
        $searchPlaceholder = $isCakePreorder ? 'Search for pastries...' : 'Search for items...';
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

        $hasDetailsErrors = $errors->hasAny(
            $isDeliveryFlow
                ? ['phone', 'delivery_address']
                : ['phone', 'pickup_location_id', 'pickup_date', 'pickup_time']
        );
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
                @unless ($isDeliveryFlow)
                    data-pickup-availability='@json($pickupAvailability ?? [])'
                @endunless
                data-menu-items='@json($menuItemsCatalog ?? [])'
                data-pickup-details-storage-key="resto.preorder.pickup-details"
                @if ($isDeliveryFlow)
                    data-initial-order-type="delivery"
                    data-force-order-type="delivery"
                @else
                    data-old-location="{{ old('pickup_location_id') }}"
                    data-old-date="{{ old('pickup_date') }}"
                    data-old-time="{{ old('pickup_time') }}"
                @endif
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
                        confirmSelectedItem() {
                            if (!this.selectedItem?.id) {
                                return;
                            }

                            if (this.quantityFor(this.selectedItem.id) < 1) {
                                this.increaseQuantity(this.selectedItem.id);
                            }

                            this.closeModal();
                        },
                        goToReview() {
                            if (!this.detailsComplete()) {
                                this.detailsError = '{{ $isDeliveryFlow ? 'Please provide your phone and delivery address.' : 'Please complete all pickup details first.' }}';
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
                @if ($isDeliveryFlow)
                    <input type="hidden" name="order_type" :value="orderType">
                    <input type="hidden" name="delivery_address" :value="address">
                @else
                    <input type="hidden" name="pickup_location_id" :value="selectedLocation">
                    <input type="hidden" name="pickup_date" :value="selectedDate">
                    <input type="hidden" name="pickup_time" :value="selectedTime">
                @endif
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
                    <aside class="hidden lg:block lg:sticky lg:top-24 h-fit border-r border-gray-100 pr-8">
                        <div class="mb-6 px-4">
                            <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-400">Categories</h3>
                        </div>
                        <nav class="space-y-1.5">
                            @if ($isCakePreorder)
                                <button type="button"
                                        @click="activeCategory = 'pastries'; document.getElementById('pastries')?.scrollIntoView({ behavior: 'smooth', block: 'start' })"
                                        class="w-full text-left px-4 py-3 text-xs font-black uppercase tracking-widest rounded-2xl transition-all"
                                        :class="activeCategory === 'pastries' ? 'bg-black text-white shadow-xl scale-[1.02]' : 'text-gray-400 hover:bg-gray-50 hover:text-black'">
                                    Pastries
                                </button>
                            @else
                                <button type="button" 
                                        @click="activeCategory = 'popular'; document.getElementById('popular')?.scrollIntoView({ behavior: 'smooth', block: 'start' })"
                                        class="w-full text-left px-4 py-3 text-xs font-black uppercase tracking-widest rounded-2xl transition-all"
                                        :class="activeCategory === 'popular' ? 'bg-black text-white shadow-xl scale-[1.02]' : 'text-gray-400 hover:bg-gray-50 hover:text-black'">
                                    Popular
                                </button>
                                @foreach ($menuCategories as $category)
                                    @php $slug = \Illuminate\Support\Str::slug($category->name); @endphp
                                    <button type="button"
                                            @click="activeCategory = '{{ $slug }}'; document.getElementById('category-{{ $slug }}')?.scrollIntoView({ behavior: 'smooth', block: 'start' })"
                                            class="w-full text-left px-4 py-3 text-xs font-black uppercase tracking-widest rounded-2xl transition-all"
                                            :class="activeCategory === '{{ $slug }}' ? 'bg-black text-white shadow-xl scale-[1.02]' : 'text-gray-400 hover:bg-gray-50 hover:text-black'">
                                        {{ $category->name }}
                                    </button>
                                @endforeach
                            @endif
                        </nav>
                    </aside>

                    <main class="min-w-0">
                        <header class="mb-10">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                                <div>
                                    <h1 class="text-3xl md:text-4xl font-black text-gray-900 tracking-tight mb-4">{{ $pageTitle }}</h1>
                                    <div class="flex items-center gap-2">
                                        <div class="flex items-center gap-2.5">
                                            <span class="flex h-7 w-7 items-center justify-center rounded-full text-[11px] font-black transition-all"
                                                :class="step >= 1 ? 'bg-black text-white' : 'bg-gray-100 text-gray-400'">1</span>
                                            <span class="text-[10px] font-black uppercase tracking-widest" :class="step >= 1 ? 'text-black' : 'text-gray-400'">{{ $stepOneLabel }}</span>
                                        </div>
                                        <div class="h-[2px] w-6" :class="step >= 2 ? 'bg-black' : 'bg-gray-100'"></div>
                                        <div class="flex items-center gap-2.5">
                                            <span class="flex h-7 w-7 items-center justify-center rounded-full text-[11px] font-black transition-all"
                                                :class="step >= 2 ? 'bg-black text-white' : 'bg-gray-100 text-gray-400'">2</span>
                                            <span class="text-[10px] font-black uppercase tracking-widest" :class="step >= 2 ? 'text-black' : 'text-gray-400'">Details</span>
                                        </div>
                                        <div class="h-[2px] w-6" :class="step >= 3 ? 'bg-black' : 'bg-gray-100'"></div>
                                        <div class="flex items-center gap-2.5">
                                            <span class="flex h-7 w-7 items-center justify-center rounded-full text-[11px] font-black transition-all"
                                                :class="step >= 3 ? 'bg-black text-white' : 'bg-gray-100 text-gray-400'">3</span>
                                            <span class="text-[10px] font-black uppercase tracking-widest" :class="step >= 3 ? 'text-black' : 'text-gray-400'">Review</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="inline-flex rounded-2xl bg-gray-50 p-1 border border-gray-100/50">
                                    <a href="{{ route('preorder.menu') }}" class="{{ $isDeliveryFlow ? 'px-6 py-2.5 text-[11px] font-black uppercase tracking-widest text-gray-400' : 'bg-white px-6 py-2.5 text-[11px] font-black uppercase tracking-widest text-gray-900 shadow-sm rounded-xl transition-all' }}">
                                        Pickup
                                    </a>
                                    <a href="{{ route('delivery.menu') }}" class="{{ $isDeliveryFlow ? 'bg-white px-6 py-2.5 text-[11px] font-black uppercase tracking-widest text-gray-900 shadow-sm rounded-xl transition-all' : 'px-6 py-2.5 text-[11px] font-black uppercase tracking-widest text-gray-400' }}">
                                        Delivery
                                    </a>
                                </div>
                            </div>

                            <div class="relative group">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-black transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input
                                    type="text"
                                    x-model="search"
                                    placeholder="{{ $searchPlaceholder }}"
                                    class="block w-full rounded-2xl border-none bg-gray-50 py-4 pl-12 pr-4 text-sm focus:ring-0 focus:bg-gray-100 placeholder:text-gray-400 transition-all font-bold text-gray-900"
                                />
                            </div>
                        </header>

                        <div x-show="step === 1" x-cloak>
                            @if ($isCakePreorder)
                                <section id="pastries" class="pt-2 mb-12">
                                    <h2 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-6">Pastry Items</h2>
                                    <div class="flex flex-col">
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
                                                class="group relative flex items-center justify-between py-6 border-b border-gray-100 last:border-0 hover:bg-gray-50/50 transition-all cursor-pointer px-2"
                                                @click="increaseQuantity({{ $item->id }})"
                                            >
                                                {{-- Left: Text Content --}}
                                                <div class="flex-grow pr-6">
                                                    <h3 class="text-base md:text-lg font-bold text-gray-900 mb-1 group-hover:text-black transition-colors">
                                                        {{ $item->name }}
                                                    </h3>
                                                    @if ($plainDescription)
                                                        <p class="text-xs text-gray-400 line-clamp-2 mb-2 leading-relaxed">
                                                            {{ $plainDescription }}
                                                        </p>
                                                    @endif
                                                    <p class="text-sm font-bold text-gray-900">ETB {{ number_format((float) $item->price, 0) }}</p>
                                                </div>

                                                {{-- Right: Image --}}
                                                <div class="relative h-24 w-24 md:h-28 md:w-28 flex-shrink-0">
                                                    <div class="h-full w-full overflow-hidden rounded-2xl bg-gray-50 border border-gray-100">
                                                        <img
                                                            src="{{ $image }}"
                                                            alt="{{ $item->name }}"
                                                            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                                                            loading="lazy"
                                                        >
                                                    </div>
                                                    <div class="absolute -bottom-1 -right-1 h-8 w-8 rounded-full bg-white shadow-lg border border-gray-100 flex items-center justify-center text-gray-900 transition-all hover:scale-110 active:scale-90 group-active:scale-90"
                                                         :class="quantityFor({{ $item->id }}) > 0 ? 'bg-black text-white' : ''">
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                                                        </svg>
                                                    </div>
                                                    <div x-show="quantityFor({{ $item->id }}) > 0"
                                                         x-transition.scale
                                                         class="absolute -top-1 -right-1 h-5 min-w-[20px] px-1 flex items-center justify-center rounded-full bg-black text-[9px] font-black text-white shadow-md border border-white"
                                                         x-text="quantityFor({{ $item->id }})">
                                                    </div>
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
                                    <h2 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-6 flex items-center gap-2">
                                        <svg class="h-3 w-3 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        Popular Choices
                                    </h2>
                                    <div class="flex flex-col">
                                        @foreach ($popularItems as $item)
                                            @include('components.menu-item-card', ['item' => $item])
                                        @endforeach
                                    </div>
                                </section>

                                {{-- Categories Sections --}}
                                @foreach ($menuCategories as $category)
                                    @php $slug = \Illuminate\Support\Str::slug($category->name); @endphp
                                    <section id="category-{{ $slug }}" class="pt-4 mb-12">
                                        <h2 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-6">{{ $category->name }}</h2>
                                        <div class="flex flex-col">
                                            @foreach ($category->items as $item)
                                                @include('components.menu-item-card', ['item' => $item])
                                            @endforeach
                                        </div>
                                    </section>
                                @endforeach
                            @endif
                        </div>

                        {{-- Step 2: Details --}}
                        <div x-show="step === 2" x-cloak class="pb-20" @if ($isDeliveryFlow) x-init="orderType = 'delivery'" @endif>
                            <div class="mb-8">
                                @if ($isDeliveryFlow)
                                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">Delivery Details</h2>
                                    <p class="mt-2 text-gray-500 font-medium">Where should we bring your meal?</p>
                                @else
                                    <h2 class="text-3xl font-black text-gray-900 tracking-tight" x-text="orderType === 'delivery' ? 'Delivery Details' : 'Pickup Details'"></h2>
                                    <p class="mt-2 text-gray-500 font-medium" x-text="orderType === 'delivery' ? 'Where should we bring your meal?' : 'When and where would you like to collect your order?'"></p>
                                @endif
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

                                <div @if (! $isDeliveryFlow) x-show="orderType === 'delivery'" @endif>
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

                                @unless ($isDeliveryFlow)
                                    <div x-show="orderType === 'pickup'" class="space-y-8">
                                        <div>
                                            <label class="text-xs font-black uppercase tracking-widest text-gray-900 mb-4 block">Pickup Location</label>
                                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                                @foreach ($pickupLocations as $location)
                                                    <label class="group relative flex cursor-pointer flex-col rounded-2xl border-2 p-5 transition-all"
                                                           :class="selectedLocation == '{{ $location->id }}' ? 'border-black bg-black/5 shadow-premium' : 'border-gray-50 hover:border-gray-200'">
                                                        <input type="radio" value="{{ $location->id }}" x-model="selectedLocation" @change="onLocationChange()" class="sr-only">
                                                        <span class="text-sm font-bold text-gray-900">{{ $location->name }}</span>
                                                        <span class="mt-1 line-clamp-1 text-xs font-medium text-gray-500 transition-colors group-hover:text-gray-700">Available for pickup today</span>
                                                        <div class="absolute top-4 right-4" x-show="selectedLocation == '{{ $location->id }}'">
                                                            <div class="h-2 w-2 rounded-full bg-black"></div>
                                                        </div>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="h-px bg-gray-50"></div>

                                        <div class="grid grid-cols-2 gap-8">
                                            <div>
                                                <label class="text-xs font-black uppercase tracking-widest text-gray-900 mb-4 block">Pickup Date</label>
                                                <div class="relative">
                                                    <select x-model="selectedDate" @change="onDateChange()" 
                                                            class="w-full appearance-none rounded-2xl border-gray-100 bg-gray-50 py-5 px-6 text-sm font-bold transition-all focus:border-black focus:ring-2 focus:ring-black">
                                                        <template x-for="option in dateOptions()" :key="option.value">
                                                            <option :value="option.value" x-text="option.label"></option>
                                                        </template>
                                                    </select>
                                                    <div class="pointer-events-none absolute top-1/2 right-5 -translate-y-1/2 text-gray-400">
                                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="text-xs font-black uppercase tracking-widest text-gray-900 mb-4 block">Pickup Time</label>
                                                <div class="relative">
                                                    <select x-model="selectedTime" 
                                                            class="w-full appearance-none rounded-2xl border-gray-100 bg-gray-50 py-5 px-6 text-sm font-bold transition-all focus:border-black focus:ring-2 focus:ring-black">
                                                        <template x-for="option in timeOptions()" :key="option.value">
                                                            <option :value="option.value" x-text="option.label"></option>
                                                        </template>
                                                    </select>
                                                    <div class="pointer-events-none absolute top-1/2 right-5 -translate-y-1/2 text-gray-400">
                                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endunless
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
                                        <p class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-1 text-right">{{ $isDeliveryFlow ? 'Deliver to' : 'Pickup at' }}</p>
                                        <p class="text-sm font-bold text-gray-900" x-text="{{ $isDeliveryFlow ? 'address' : '`' . '${selectedDate} at ${selectedTime}' . '`' }}"></p>
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

                {{-- High-Fidelity Customization Modal --}}
                <div x-show="modalOpen" 
                     x-cloak 
                     class="fixed inset-0 z-[100] flex items-center justify-center p-0 md:p-8 overflow-hidden"
                     role="dialog" 
                     aria-modal="true"
                >
                    {{-- Backdrop --}}
                    <div x-show="modalOpen" 
                         x-transition:enter="ease-out duration-300" 
                         x-transition:enter-start="opacity-0 backdrop-blur-0" 
                         x-transition:enter-end="opacity-100 backdrop-blur-xl" 
                         x-transition:leave="ease-in duration-200" 
                         x-transition:leave-start="opacity-100 backdrop-blur-xl" 
                         x-transition:leave-end="opacity-0 backdrop-blur-0" 
                         class="fixed inset-0 bg-black/90" 
                         @click="closeModal()"
                    ></div>

                    <div x-show="modalOpen" 
                         x-transition:enter="transition ease-out duration-300 transform" 
                         x-transition:enter-start="translate-y-full md:translate-y-0 md:scale-95 md:opacity-0" 
                         x-transition:enter-end="translate-y-0 md:scale-100 md:opacity-100" 
                         x-transition:leave="transition ease-in duration-200 transform" 
                         x-transition:leave-start="translate-y-0 md:scale-100 md:opacity-100" 
                         x-transition:leave-end="translate-y-full md:translate-y-0 md:scale-95 md:opacity-0" 
                         class="relative w-full h-full max-w-4xl bg-white md:rounded-[2.5rem] overflow-y-auto scrollbar-hide flex flex-col"
                    >
                        <template x-if="selectedItem">
                            <div class="flex flex-col h-full">
                                {{-- Hero Image Section --}}
                                <div class="relative w-full aspect-square md:aspect-[4/3] flex-shrink-0 bg-gray-100 group">
                                    <img :src="selectedItem.image_url" :alt="selectedItem.title" class="h-full w-full object-cover transition-transform duration-700">
                                    
                                    {{-- Close Button (Top Right) --}}
                                    <button @click="closeModal()" 
                                            class="absolute top-4 right-4 z-[110] flex h-10 w-10 items-center justify-center rounded-full bg-white shadow-xl text-gray-900 transition-all active:scale-90">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                
                                {{-- Details Section --}}
                                <div class="p-8 md:p-12 pb-32">
                                    <div class="flex flex-col md:flex-row md:items-baseline justify-between gap-4 mb-6">
                                        <h2 class="text-3xl md:text-4xl font-black text-gray-900 tracking-tight" x-text="selectedItem.title"></h2>
                                        <div class="text-2xl font-bold text-gray-900" x-text="formatMoney(selectedItem.price)"></div>
                                    </div>

                                    <p class="text-gray-500 text-lg font-medium leading-relaxed mb-10" x-text="selectedItem.description"></p>

                                    {{-- Variants & Add-ons --}}
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                        {{-- Variants --}}
                                        <template x-if="selectedItem.variants.length > 0">
                                            <div class="bg-gray-50 p-6 rounded-3xl border border-gray-100">
                                                <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-4">Choose Variant</h3>
                                                <div class="space-y-4">
                                                    <label class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0 hover:text-amber-700 transition-colors cursor-pointer group">
                                                        <div class="flex items-center gap-3">
                                                            <input type="radio" :name="`variant_${selectedItem.id}`" value="" x-model="selectedVariants[selectedItem.id]" class="text-black focus:ring-black">
                                                            <span class="font-bold text-gray-700 text-base" :class="!selectedVariants[selectedItem.id] ? 'text-black' : ''">Standard</span>
                                                        </div>
                                                        <span class="font-bold text-gray-400" x-text="formatMoney(selectedItem.price)"></span>
                                                    </label>
                                                    <template x-for="variant in selectedItem.variants" :key="variant.id">
                                                        <label class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0 hover:text-amber-700 transition-colors cursor-pointer group">
                                                            <div class="flex items-center gap-3">
                                                                <input type="radio" :name="`variant_${selectedItem.id}`" :value="variant.id" x-model="selectedVariants[selectedItem.id]" class="text-black focus:ring-black">
                                                                <span class="font-bold text-gray-700 text-base" :class="selectedVariants[selectedItem.id] == variant.id ? 'text-black' : ''" x-text="variant.name"></span>
                                                            </div>
                                                            <span class="font-bold text-gray-400" x-text="formatMoney(variant.price)"></span>
                                                        </label>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>

                                        {{-- Addons --}}
                                        <template x-if="selectedItem.addons.length > 0">
                                            <div class="bg-gray-50 p-6 rounded-3xl border border-gray-100">
                                                <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-4">Add Extras</h3>
                                                <div class="space-y-4">
                                                    <template x-for="addon in selectedItem.addons" :key="addon.id">
                                                        <label class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0 hover:text-amber-700 transition-colors cursor-pointer group">
                                                            <div class="flex items-center gap-3">
                                                                <input type="checkbox" :value="String(addon.id)" x-model="selectedAddons[selectedItem.id]" class="rounded text-black focus:ring-black">
                                                                <span class="font-bold text-gray-700 text-base" :class="ensureAddonSelection(selectedItem.id).includes(String(addon.id)) ? 'text-black' : ''" x-text="addon.name"></span>
                                                            </div>
                                                            <span class="font-bold text-gray-400" x-text="`+${formatMoney(addon.price)}`"></span>
                                                        </label>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                
                                {{-- Footer Action --}}
                                <div class="fixed md:absolute bottom-0 inset-x-0 p-6 bg-white/80 backdrop-blur-xl border-t border-gray-100 flex items-center justify-between gap-4 z-[120]">
                                    <div class="flex items-center gap-4 bg-gray-100 rounded-2xl p-1 shrink-0">
                                        <button type="button" @click="decreaseQuantity(selectedItem.id)" class="h-10 w-10 flex items-center justify-center rounded-xl hover:bg-white transition-all">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg>
                                        </button>
                                        <span class="text-sm font-black w-4 text-center" x-text="quantityFor(selectedItem.id)"></span>
                                        <button type="button" @click="increaseQuantity(selectedItem.id)" class="h-10 w-10 flex items-center justify-center rounded-xl hover:bg-white transition-all">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                        </button>
                                    </div>
                                    <button type="button" @click="confirmSelectedItem()" class="flex-grow rounded-2xl bg-black py-4 text-sm font-bold text-white shadow-xl transition-all hover:scale-[1.02] active:scale-[0.98]">
                                        Add to Order – <span x-text="formatMoney(lineTotalFor(selectedItem?.id))"></span>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Premium Persistent Mobile Cart Bar --}}
                <div x-show="cartItemCount() > 0 && !modalOpen" 
                     x-cloak
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="translate-y-full"
                     x-transition:enter-end="translate-y-0"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="translate-y-0"
                     x-transition:leave-end="translate-y-full"
                     class="fixed inset-x-0 bottom-0 z-50 bg-gradient-to-t from-white via-white/95 to-transparent px-4 pt-12 pb-[calc(1rem+env(safe-area-inset-bottom))] pointer-events-none lg:hidden"
                >
                    <button type="button" 
                            @click="step === 1 ? goToDetails() : (step === 2 ? goToReview() : placePreorder())"
                            class="pointer-events-auto w-full flex items-center justify-between bg-black text-white p-3 pr-8 rounded-lg shadow-[0_20px_50px_-10px_rgba(0,0,0,0.5)] transition-all active:scale-[0.98]"
                    >
                        <div class="flex items-center gap-4">
                            {{-- Count Badge --}}
                            <div class="h-14 w-14 rounded-full bg-zinc-800 flex items-center justify-center text-sm font-black" x-text="cartItemCount()"></div>
                            
                            <div class="text-left">
                                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-white/40 mb-0.5">Your Cart</p>
                                <p class="text-lg font-black tracking-tight" x-text="formatMoney(cartSubtotal())"></p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="text-base font-bold" x-text="step === 3 ? 'Place Order' : 'Review Order'"></span>
                            <svg class="h-5 w-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </section>
</x-layouts.app-main>
