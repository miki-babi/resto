<x-layouts.app-main>
    @push('meta')
        <meta name="description" content="Order ahead for pickup. Choose a pickup location and time, then place your order." />
    @endpush

    <script>
        window.ORDER_DATA = @json([ 'categories' => $categories, 'pickupLocations' => $pickupLocations,'pickupOptionsByLocation' => $pickupOptionsByLocation]);
    </script>

    <main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-10">
        <div class="mb-10">
            <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter mb-2">Order Online</h1>
            <p class="text-slate-500 max-w-2xl font-medium">Pick your items, choose a pickup location and time, then place your order.</p>
        </div>

        <section x-data="orderApp(window.ORDER_DATA)" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Menu --}}
            <div class="lg:col-span-2">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-extrabold text-slate-900 uppercase tracking-tight">Menu</h2>
                    <a href="{{ route('menu') }}" class="text-sm font-bold text-metro-red hover:underline">Browse menu page</a>
                </div>

                <div class="flex gap-2 overflow-x-auto pb-2 mb-6">
                    <template x-for="category in categories" :key="category.id">
                        <button
                            type="button"
                            @click="activeCategoryId = category.id"
                            class="px-4 py-2 rounded-full border text-sm font-bold whitespace-nowrap transition"
                            :class="activeCategoryId === category.id ? 'bg-metro-red text-white border-metro-red' : 'bg-white text-slate-700 border-slate-200 hover:border-metro-red hover:text-metro-red'"
                            x-text="category.name"
                        ></button>
                    </template>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <template x-for="item in activeCategory.items" :key="item.id">
                        <button
                            type="button"
                            @click="openItem(item)"
                            class="text-left bg-white rounded-2xl border border-slate-200 hover:border-metro-red hover:shadow-md transition p-5"
                        >
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    <h3 class="text-lg font-extrabold text-slate-900 truncate" x-text="item.title"></h3>
                                    <p class="text-sm text-slate-500 mt-1 line-clamp-2" x-text="item.description || '—'"></p>
                                </div>
                                <div class="flex flex-col items-end gap-2 flex-shrink-0">
                                    <span class="text-metro-red font-black text-lg" x-text="formatMoney(item.price)"></span>
                                    <div class="flex gap-1">
                                        <template x-if="item.variants.length">
                                            <span class="text-[11px] font-bold px-2 py-1 rounded-full bg-slate-100 text-slate-700">Variants</span>
                                        </template>
                                        <template x-if="item.addons.length">
                                            <span class="text-[11px] font-bold px-2 py-1 rounded-full bg-slate-100 text-slate-700">Add-ons</span>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <span class="inline-flex items-center gap-2 text-sm font-bold text-slate-800">
                                    <span class="material-symbols-outlined text-base">add_shopping_cart</span>
                                    Add to cart
                                </span>
                            </div>
                        </button>
                    </template>
                </div>
            </div>

            {{-- Cart + Checkout --}}
            <aside class="lg:col-span-1">
                <div class="sticky top-28 space-y-6">
                    <div class="bg-white rounded-2xl border border-slate-200 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-extrabold text-slate-900">Cart</h2>
                            <button type="button" class="text-xs font-bold text-slate-500 hover:text-slate-900" @click="clearCart()" x-show="cart.length">Clear</button>
                        </div>

                        <template x-if="!cart.length">
                            <p class="text-sm text-slate-500">Your cart is empty. Add items from the menu.</p>
                        </template>

                        <div class="space-y-4" x-show="cart.length">
                            <template x-for="line in cart" :key="line.key">
                                <div class="border border-slate-200 rounded-xl p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="font-extrabold text-slate-900 leading-tight" x-text="line.title"></p>
                                            <template x-if="line.variant_name">
                                                <p class="text-xs text-slate-500 mt-1" x-text="`Variant: ${line.variant_name}`"></p>
                                            </template>
                                            <template x-if="line.addons.length">
                                                <p class="text-xs text-slate-500 mt-1">
                                                    <span class="font-bold">Add-ons:</span>
                                                    <span x-text="line.addons.map(a => a.name).join(', ')"></span>
                                                </p>
                                            </template>
                                        </div>

                                        <button type="button" class="text-slate-400 hover:text-slate-900" @click="removeLine(line.key)" aria-label="Remove">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                    </div>

                                    <div class="flex items-center justify-between mt-4">
                                        <div class="flex items-center gap-2">
                                            <button type="button" class="w-9 h-9 rounded-full border border-slate-200 hover:border-metro-red font-black" @click="decQty(line.key)">-</button>
                                            <span class="w-6 text-center font-extrabold" x-text="line.quantity"></span>
                                            <button type="button" class="w-9 h-9 rounded-full border border-slate-200 hover:border-metro-red font-black" @click="incQty(line.key)">+</button>
                                        </div>

                                        <div class="text-right">
                                            <p class="text-sm text-slate-500">Line total</p>
                                            <p class="font-black text-slate-900" x-text="formatMoney(lineTotal(line))"></p>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <div class="pt-4 border-t border-slate-200 flex items-center justify-between">
                                <p class="text-sm font-bold text-slate-500">Total</p>
                                <p class="text-xl font-black text-slate-900" x-text="formatMoney(cartTotal())"></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 p-6">
                        <h2 class="text-xl font-extrabold text-slate-900 mb-4">Pickup</h2>

                        <label class="block text-xs font-bold text-slate-500 mb-2">Pickup location</label>
                        <select
                            class="w-full rounded-xl border border-slate-200 p-3 font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-metro-red"
                            x-model.number="selectedPickupLocationId"
                            @change="resetPickupSelection()"
                        >
                            <template x-for="loc in pickupLocations" :key="loc.id">
                                <option :value="loc.id" x-text="loc.name"></option>
                            </template>
                        </select>

                        <div class="mt-5" x-show="availableDays().length">
                            <label class="block text-xs font-bold text-slate-500 mb-2">Pickup day</label>
                            <div class="flex gap-2 overflow-x-auto pb-2">
                                <template x-for="day in availableDays()" :key="day.date_key">
                                    <button
                                        type="button"
                                        class="px-3 py-2 rounded-full border text-sm font-bold whitespace-nowrap transition"
                                        :class="selectedPickupDateKey === day.date_key ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-700 border-slate-200 hover:border-slate-900'"
                                        @click="selectDay(day.date_key)"
                                        x-text="day.date_label"
                                    ></button>
                                </template>
                            </div>

                            <label class="block text-xs font-bold text-slate-500 mt-5 mb-2">Pickup time</label>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="opt in availableTimesForSelectedDay()" :key="opt.pickup_at">
                                    <button
                                        type="button"
                                        class="px-3 py-2 rounded-xl border text-sm font-extrabold transition"
                                        :class="selectedPickupOption && selectedPickupOption.pickup_at === opt.pickup_at ? 'bg-metro-red text-white border-metro-red' : 'bg-white text-slate-800 border-slate-200 hover:border-metro-red hover:text-metro-red'"
                                        @click="selectedPickupOption = opt"
                                        x-text="opt.time_label"
                                    ></button>
                                </template>
                            </div>
                        </div>

                        <div class="mt-5" x-show="!availableDays().length">
                            <p class="text-sm text-slate-500">No pickup times available for this location in the next 7 days.</p>
                        </div>

                        <div class="mt-6 space-y-3">
                            <template x-if="errorMessage">
                                <div class="rounded-xl bg-red-50 border border-red-200 p-3 text-sm text-red-800" x-text="errorMessage"></div>
                            </template>

                            <button
                                type="button"
                                class="w-full rounded-xl bg-metro-red text-white font-black py-3 hover:bg-red-800 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="isPlacingOrder || !canPlaceOrder()"
                                @click="placeOrder()"
                            >
                                <span x-show="!isPlacingOrder">Place order</span>
                                <span x-show="isPlacingOrder">Placing...</span>
                            </button>

                            <p class="text-xs text-slate-500" x-show="selectedPickupOption">
                                Selected: <span class="font-bold" x-text="selectedPickupOption.label"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </aside>

            {{-- Item Modal --}}
            <div
                x-show="isItemModalOpen"
                x-cloak
                class="fixed inset-0 z-[200] overflow-y-auto"
                role="dialog"
                aria-modal="true"
            >
                <div class="fixed inset-0 bg-black/70 backdrop-blur-sm"></div>

                <div class="flex min-h-screen items-center justify-center p-4" @click.self="closeItemModal()">
                    <div class="relative w-full max-w-2xl overflow-hidden rounded-3xl bg-white shadow-2xl">
                        <button type="button" @click="closeItemModal()" class="absolute right-4 top-4 z-50 rounded-full bg-black/10 p-2 text-slate-900 hover:bg-black/20 transition" aria-label="Close">
                            <span class="material-symbols-outlined">close</span>
                        </button>

                        <div class="p-8">
                            <h3 class="text-2xl font-black text-slate-900" x-text="modalItem?.title"></h3>
                            <p class="text-slate-500 mt-2" x-text="modalItem?.description || '—'"></p>

                            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <template x-if="modalItem?.variants?.length">
                                        <div>
                                            <p class="text-sm font-black text-slate-900 mb-2">Variant</p>
                                            <div class="space-y-2">
                                                <template x-for="variant in modalItem.variants" :key="variant.id">
                                                    <label class="flex items-center justify-between gap-3 border border-slate-200 rounded-xl px-4 py-3 cursor-pointer hover:border-metro-red transition">
                                                        <span class="flex items-center gap-3">
                                                            <input type="radio" name="variant" class="accent-metro-red" :value="variant.id" x-model.number="selectedVariantId">
                                                            <span class="font-extrabold text-slate-900" x-text="variant.name"></span>
                                                        </span>
                                                        <span class="font-black text-slate-900" x-text="formatMoney(variant.price)"></span>
                                                    </label>
                                                </template>
                                            </div>
                                        </div>
                                    </template>

                                    <template x-if="!modalItem?.variants?.length">
                                        <div>
                                            <p class="text-sm font-black text-slate-900 mb-2">Price</p>
                                            <p class="text-2xl font-black text-metro-red" x-text="formatMoney(modalItem?.price || 0)"></p>
                                        </div>
                                    </template>
                                </div>

                                <div>
                                    <template x-if="modalItem?.addons?.length">
                                        <div>
                                            <p class="text-sm font-black text-slate-900 mb-2">Add-ons</p>
                                            <div class="space-y-2 max-h-56 overflow-auto pr-1">
                                                <template x-for="addon in modalItem.addons" :key="addon.id">
                                                    <label class="flex items-center justify-between gap-3 border border-slate-200 rounded-xl px-4 py-3 cursor-pointer hover:border-metro-red transition">
                                                        <span class="flex items-center gap-3">
                                                            <input type="checkbox" class="accent-metro-red" :value="addon.id" x-model="selectedAddonIds">
                                                            <span class="font-extrabold text-slate-900" x-text="addon.name"></span>
                                                        </span>
                                                        <span class="font-black text-slate-900" x-text="addon.price ? `+${formatMoney(addon.price)}` : formatMoney(0)"></span>
                                                    </label>
                                                </template>
                                            </div>
                                        </div>
                                    </template>

                                    <template x-if="!modalItem?.addons?.length">
                                        <p class="text-sm text-slate-500">No add-ons available.</p>
                                    </template>
                                </div>
                            </div>

                            <div class="mt-8 flex items-center justify-between gap-4">
                                <div class="flex items-center gap-2">
                                    <button type="button" class="w-10 h-10 rounded-full border border-slate-200 hover:border-metro-red font-black" @click="modalQty = Math.max(1, modalQty - 1)">-</button>
                                    <span class="w-8 text-center font-black text-slate-900" x-text="modalQty"></span>
                                    <button type="button" class="w-10 h-10 rounded-full border border-slate-200 hover:border-metro-red font-black" @click="modalQty = Math.min(50, modalQty + 1)">+</button>
                                </div>

                                <button type="button" class="rounded-xl bg-slate-900 text-white font-black px-6 py-3 hover:bg-black transition" @click="addModalToCart()">
                                    <span x-text="`Add • ${formatMoney(modalTotal())}`"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @push('scripts')
        <script>
            function orderApp(data) {
                return {
                    categories: data.categories || [],
                    pickupLocations: data.pickupLocations || [],
                    pickupOptionsByLocation: data.pickupOptionsByLocation || {},

                    activeCategoryId: (data.categories && data.categories[0] ? data.categories[0].id : null),

                    cart: [],
                    errorMessage: '',
                    isPlacingOrder: false,

                    selectedPickupLocationId: (data.pickupLocations && data.pickupLocations[0] ? data.pickupLocations[0].id : null),
                    selectedPickupDateKey: null,
                    selectedPickupOption: null,

                    isItemModalOpen: false,
                    modalItem: null,
                    selectedVariantId: null,
                    selectedAddonIds: [],
                    modalQty: 1,

                    get activeCategory() {
                        return this.categories.find(c => c.id === this.activeCategoryId) || { items: [] };
                    },

                    formatMoney(amount) {
                        const num = Number(amount || 0);
                        return `Br ${num.toFixed(2)}`;
                    },

                    openItem(item) {
                        this.modalItem = item;
                        this.selectedAddonIds = [];
                        this.modalQty = 1;

                        if (item.variants && item.variants.length) {
                            this.selectedVariantId = item.variants[0].id;
                        } else {
                            this.selectedVariantId = null;
                        }

                        this.isItemModalOpen = true;
                    },

                    closeItemModal() {
                        this.isItemModalOpen = false;
                        this.modalItem = null;
                        this.errorMessage = '';
                    },

                    selectedVariant() {
                        if (!this.modalItem || !this.modalItem.variants || !this.modalItem.variants.length) return null;
                        return this.modalItem.variants.find(v => v.id === Number(this.selectedVariantId)) || null;
                    },

                    selectedAddons() {
                        if (!this.modalItem || !this.modalItem.addons) return [];
                        const ids = (this.selectedAddonIds || []).map(Number);
                        return this.modalItem.addons.filter(a => ids.includes(a.id));
                    },

                    modalTotal() {
                        if (!this.modalItem) return 0;
                        const variant = this.selectedVariant();
                        const base = variant ? Number(variant.price) : Number(this.modalItem.price || 0);
                        const addonsTotal = this.selectedAddons().reduce((sum, a) => sum + Number(a.price || 0), 0);
                        return (base + addonsTotal) * Number(this.modalQty || 1);
                    },

                    addModalToCart() {
                        if (!this.modalItem) return;

                        const item = this.modalItem;
                        const variant = this.selectedVariant();
                        const addons = this.selectedAddons();
                        const addonIds = addons.map(a => a.id).sort((a, b) => a - b);

                        if (item.variants && item.variants.length && !variant) {
                            this.errorMessage = 'Please select a variant.';
                            return;
                        }

                        const key = `${item.id}:${variant ? variant.id : ''}:${addonIds.join(',')}`;
                        const existing = this.cart.find(l => l.key === key);

                        const unitPrice = variant ? Number(variant.price) : Number(item.price || 0);
                        const qty = Number(this.modalQty || 1);

                        const linePayload = {
                            key,
                            menu_item_id: item.id,
                            title: item.title,
                            menu_item_variant_id: variant ? variant.id : null,
                            variant_name: variant ? variant.name : null,
                            unit_price: unitPrice,
                            addon_ids: addonIds,
                            addons: addons.map(a => ({ id: a.id, name: a.name, price: Number(a.price || 0) })),
                            quantity: qty,
                        };

                        if (existing) {
                            existing.quantity = Math.min(50, Number(existing.quantity) + qty);
                        } else {
                            this.cart.push(linePayload);
                        }

                        this.closeItemModal();
                        this.resetPickupSelection(false);
                    },

                    lineTotal(line) {
                        const base = Number(line.unit_price || 0);
                        const addons = (line.addons || []).reduce((sum, a) => sum + Number(a.price || 0), 0);
                        return (base + addons) * Number(line.quantity || 1);
                    },

                    cartTotal() {
                        return this.cart.reduce((sum, l) => sum + this.lineTotal(l), 0);
                    },

                    incQty(key) {
                        const line = this.cart.find(l => l.key === key);
                        if (line) line.quantity = Math.min(50, Number(line.quantity) + 1);
                    },

                    decQty(key) {
                        const line = this.cart.find(l => l.key === key);
                        if (!line) return;
                        line.quantity = Math.max(1, Number(line.quantity) - 1);
                    },

                    removeLine(key) {
                        this.cart = this.cart.filter(l => l.key !== key);
                    },

                    clearCart() {
                        this.cart = [];
                        this.selectedPickupOption = null;
                        this.errorMessage = '';
                    },

                    availableDays() {
                        const locId = Number(this.selectedPickupLocationId);
                        return this.pickupOptionsByLocation[locId] || [];
                    },

                    selectDay(dateKey) {
                        this.selectedPickupDateKey = dateKey;
                        this.selectedPickupOption = null;
                    },

                    availableTimesForSelectedDay() {
                        const day = this.availableDays().find(d => d.date_key === this.selectedPickupDateKey);
                        return day ? (day.options || []) : [];
                    },

                    resetPickupSelection(force = true) {
                        if (!force && this.selectedPickupDateKey) return;

                        const days = this.availableDays();
                        this.selectedPickupDateKey = days.length ? days[0].date_key : null;
                        this.selectedPickupOption = null;
                    },

                    canPlaceOrder() {
                        return this.cart.length > 0
                            && this.selectedPickupLocationId
                            && this.selectedPickupOption;
                    },

                    async placeOrder() {
                        this.errorMessage = '';

                        if (!this.canPlaceOrder()) {
                            this.errorMessage = 'Please add items and choose a pickup time.';
                            return;
                        }

                        this.isPlacingOrder = true;

                        try {
                            const payload = {
                                pickup_location_id: Number(this.selectedPickupLocationId),
                                pickup_day_of_week: Number(this.selectedPickupOption.day_of_week),
                                pickup_hour_slot: Number(this.selectedPickupOption.hour_slot),
                                pickup_period: String(this.selectedPickupOption.period),
                                items: this.cart.map(l => ({
                                    menu_item_id: Number(l.menu_item_id),
                                    menu_item_variant_id: l.menu_item_variant_id ? Number(l.menu_item_variant_id) : null,
                                    quantity: Number(l.quantity),
                                    addon_ids: (l.addon_ids || []).map(Number),
                                })),
                            };

                            const res = await fetch('{{ route('order.store') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                },
                                body: JSON.stringify(payload),
                            });

                            if (!res.ok) {
                                const data = await res.json().catch(() => ({}));
                                this.errorMessage = data.message || 'Could not place order. Please try again.';
                                return;
                            }

                            const data = await res.json();
                            if (data.redirect) {
                                window.location.href = data.redirect;
                                return;
                            }

                            this.errorMessage = 'Order placed, but no redirect was returned.';
                        } catch (e) {
                            this.errorMessage = 'Network error. Please try again.';
                        } finally {
                            this.isPlacingOrder = false;
                        }
                    },

                    init() {
                        this.resetPickupSelection(true);
                    },
                }
            }
        </script>
    @endpush
</x-layouts.app-main>

