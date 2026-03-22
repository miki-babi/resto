<x-layouts.app-main>
    @push('meta')
        <meta name="description" content="Catering packages for meetings, events, and celebrations from Mera Coffee.">
        <meta property="og:title" content="Mera Coffee Catering">
        <meta property="og:image" content="https://images.pexels.com/photos/302902/pexels-photo-302902.jpeg">
    @endpush

    <x-hero-main
        image="https://images.pexels.com/photos/3184192/pexels-photo-3184192.jpeg"
        subtitle="Catering for every gathering"
        title="Catering Packages Built for Your Event"
        primary-button-text="Request Catering"
        primary-button-url="/catering/request"
        secondary-button-text="View Packages"
        secondary-button-url="#packages"
    />

    <x-section
        subtitle="How it works"
        title="Simple, flexible catering from start to finish"
        description="Choose a package, tell us your date and headcount, and we will handle the rest. Our team customizes the menu, coordinates delivery, and makes sure every guest is taken care of."
        image="https://images.pexels.com/photos/302901/pexels-photo-302901.jpeg"
    />

    <section class="section py-24 relative overflow-hidden" id="packages" x-data="{
        isOpen: false,
        showSuccess: {{ session('success') ? 'true' : 'false' }},
        pkg: { id: null, name: '', description: '', min_guests: 0, images: [], cover: '' },
        activeImage: 0,
        openPackage(pkg) {
            this.pkg = pkg;
            this.activeImage = 0;
            this.isOpen = true;
        },
        next() {
            if (!this.pkg.images.length) return;
            this.activeImage = (this.activeImage + 1) % this.pkg.images.length;
        },
        prev() {
            if (!this.pkg.images.length) return;
            this.activeImage = (this.activeImage - 1 + this.pkg.images.length) % this.pkg.images.length;
        }
    }" @keydown.escape.window="isOpen = false; showSuccess = false">
        <div class="container mx-auto px-4 max-w-[--max-width]">
            <div class="menu-topbar flex flex-col md:flex-row md:items-end justify-between gap-6 mb-11 reveal">
                <div class="section-header max-w-[620px]">
                    <span
                        class="eyebrow block text-accent text-[0.88rem] font-semibold tracking-[0.24em] uppercase">
                        <span
                            class="script font-script text-gold font-normal tracking-normal lowercase text-[1.6em]">Signature</span>
                        {{ __('Menu Collection') }}
                    </span>
                    <p class="text-text-soft dark:text-text-soft-dark mt-2">
                        {{ __('Switch between per-person pricing and full package estimates to match your event planning style.') }}
                    </p>
                </div>

                <div class="pricing-toggle flex gap-2 p-1.5 bg-card dark:bg-card-dark border border-line dark:border-white/10 rounded-full shadow-soft"
                    role="group">
                    <button onclick="setPrice('person')" id="ppBtn"
                        class="px-6 py-2 rounded-full text-sm font-bold transition-all">{{ __('Per Person') }}</button>
                    <button onclick="setPrice('total')" id="totalBtn"
                        class="px-6 py-2 rounded-full text-sm font-bold transition-all">{{ __('Full Package') }}</button>
                </div>
            </div>

            <div class="packages grid gap-8">
                @forelse ($cateringPackages as $package)
                    @php
                        $isEven = $loop->even;
                        $initialPrice = $package['price_per_person'] ?? $package['price_total'];
                        $initialLabel = $package['price_per_person'] !== null ? __('person') : ($package['price_total'] !== null ? __('Package') : '');
                    @endphp
                    <article
                        class="package-card grid grid-cols-1 {{ $isEven ? 'lg:grid-cols-[minmax(0,1.05fr)_minmax(300px,0.95fr)]' : 'lg:grid-cols-[minmax(300px,0.95fr)_minmax(0,1.05fr)]' }} gap-8 p-8 rounded-[40px] bg-gradient-to-b from-card to-surface-strong dark:from-card-dark dark:to-surface-strong-dark border border-line dark:border-white/10 shadow-soft hover:-translate-y-1 transition-all duration-300 reveal">
                        <div class="package-visual relative min-h-[340px] grid place-items-center {{ $isEven ? 'lg:order-2' : '' }}">
                            <div class="image-stack relative w-full h-full">
                                <img class="image-main absolute inset-0 w-[80%] h-full object-cover rounded-[32px] shadow-shadow transition-transform duration-500"
                                    src="{{ $package['cover'] }}"
                                    alt="{{ $package['name'] }} main" />
                                <img class="image-accent absolute bottom-8 {{ $isEven ? 'left-0 -rotate-3 group-hover:-rotate-6' : 'right-0 rotate-3 group-hover:rotate-6' }} w-[50%] h-[55%] object-cover rounded-[28px] border-8 border-surface-strong dark:border-surface-strong-dark shadow-shadow transition-transform duration-500"
                                    src="{{ $package['accent'] }}"
                                    alt="{{ $package['name'] }} accent" />
                            </div>
                        </div>
                        <div class="package-content grid gap-5 {{ $isEven ? 'lg:order-1' : '' }}">
                            @if (!empty($package['badge_text']))
                                <div class="package-meta flex items-center gap-3">
                                    <span
                                        class="badge px-4 py-1.5 rounded-full {{ $package['badge_class'] }} text-xs font-bold uppercase tracking-wider">{{ $package['badge_text'] }}</span>
                                </div>
                            @endif
                            <div class="grid gap-2">
                                <h3 class="font-serif text-3xl font-bold text-text dark:text-text-dark">
                                    {{ $package['name'] }}</h3>
                                <p class="text-text-soft dark:text-text-soft-dark leading-relaxed">
                                    {{ $package['description'] ?: __('A curated package designed to keep your guests happy and fueled.') }}
                                </p>
                            </div>
                            <div class="price-row flex items-baseline gap-2">
                                <span class="price-value font-serif text-5xl font-bold text-gold"
                                    data-pp="{{ $package['price_per_person'] ?? '' }}"
                                    data-total="{{ $package['price_total'] ?? '' }}">
                                    @if ($initialPrice !== null)
                                        {{ __('ETB') }} {{ number_format($initialPrice) }}
                                    @else
                                        {{ __('Contact us') }}
                                    @endif
                                </span>
                                <span class="price-label text-text-soft dark:text-text-soft-dark text-sm">
                                    @if ($initialLabel)
                                        / {{ $initialLabel }}
                                    @endif
                                </span>
                            </div>
                            @if (!empty($package['highlights']))
                                <ul class="package-list grid gap-3">
                                    @foreach ($package['highlights'] as $highlight)
                                        <li class="flex items-start gap-3 transition-colors hover:text-accent-hover group">
                                            <span
                                                class="w-2 h-2 mt-2 rounded-full bg-gold shadow-[0_0_8px_var(--gold)]"></span>
                                            <span>{{ $highlight }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                            <div class="mt-4">
                                <button type="button"
                                    @click="openPackage(@js($package))"
                                    class="inline-block px-8 py-3 rounded-full font-bold bg-surface dark:bg-surface-dark border border-line dark:border-white/10 hover:border-gold transition-colors text-sm">
                                    {{ __('Inquire Now') }}
                                </button>
                            </div>
                        </div>
                    </article>
                @empty
                    <div
                        class="rounded-3xl border border-line dark:border-white/10 bg-card dark:bg-card-dark p-8 text-center text-text-soft dark:text-text-soft-dark">
                        {{ __('No catering packages are available right now. Please check back soon.') }}
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Package Modal --}}
        <div x-show="isOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[300] flex items-center justify-center bg-black/80 p-4"
             x-cloak
             @click.self="isOpen = false">
            <div class="relative w-full max-w-4xl rounded-3xl bg-white shadow-2xl overflow-hidden">
                <button @click="isOpen = false"
                        class="absolute top-4 right-4 z-10 flex h-9 w-9 items-center justify-center rounded-full bg-white text-black shadow-lg hover:bg-gray-100 transition">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div class="grid lg:grid-cols-[1.1fr_1fr]">
                    <div class="relative bg-black">
                        <div class="relative aspect-[4/3] lg:aspect-auto lg:h-full">
                            <img :src="pkg.images[activeImage] || pkg.cover"
                                 :alt="pkg.name"
                                 class="h-full w-full object-cover">
                        </div>
                        <template x-if="pkg.images && pkg.images.length > 1">
                            <div class="absolute inset-0 flex items-center justify-between px-3">
                                <button @click.stop="prev()"
                                        class="bg-white/90 text-black rounded-full h-8 w-8 flex items-center justify-center shadow-lg hover:bg-white transition">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <button @click.stop="next()"
                                        class="bg-white/90 text-black rounded-full h-8 w-8 flex items-center justify-center shadow-lg hover:bg-white transition">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    <div class="p-6 lg:p-8">
                        <p class="text-xs font-semibold uppercase tracking-wide text-metro-red mb-2">
                            Min <span x-text="pkg.min_guests"></span> guests
                        </p>
                        <h3 class="text-2xl font-bold text-gray-900 font-serif" x-text="pkg.name"></h3>
                        <p class="mt-3 text-gray-600 text-sm leading-relaxed" x-text="pkg.description || 'A curated package designed to keep your guests happy and fueled.'"></p>

                        <form method="POST" action="{{ route('catering.request') }}" class="mt-6 space-y-4">
                            @csrf
                            <input type="hidden" name="catering_package_id" :value="pkg.id">

                            <div>
                                <label class="text-sm font-semibold text-gray-700">Full name</label>
                                <input
                                    type="text"
                                    name="name"
                                    class="mt-2 w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-metro-red focus:outline-none"
                                    placeholder="Your name"
                                    required
                                />
                            </div>

                            <div>
                                <label class="text-sm font-semibold text-gray-700">Phone number (Ethiopia)</label>
                                <input
                                    type="tel"
                                    name="contact"
                                    inputmode="tel"
                                    {{-- pattern="^(\\+251|0)?9\\d{8}$" --}}
                                    class="mt-2 w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-metro-red focus:outline-none"
                                    placeholder="+2519XXXXXXXX or 09XXXXXXXX"
                                    required
                                />
                                <p class="mt-2 text-xs text-gray-500">Format: +2519XXXXXXXX or 09XXXXXXXX</p>
                            </div>

                            <div>
                                <label class="text-sm font-semibold text-gray-700">Notes (optional)</label>
                                <textarea
                                    name="note"
                                    rows="3"
                                    class="mt-2 w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-metro-red focus:outline-none"
                                    placeholder="Event date, guest count, dietary needs, etc."
                                ></textarea>
                            </div>

                            <div class="flex flex-wrap gap-3">
                                <button
                                    type="submit"
                                    class="inline-flex items-center rounded-lg bg-metro-dark px-5 py-2 text-sm font-semibold text-white transition hover:bg-metro-hover"
                                >
                                    Submit Request
                                </button>
                                <button
                                    type="button"
                                    @click="isOpen = false"
                                    class="inline-flex items-center rounded-lg border border-gray-200 px-5 py-2 text-sm font-semibold text-gray-700 hover:border-gray-300"
                                >
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Success Confirmation Modal --}}
        <div x-show="showSuccess"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[320] flex items-center justify-center bg-black/70 p-4"
             x-cloak
             @click.self="showSuccess = false">
            <div class="relative w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl text-center">
                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-green-100 text-green-700">
                    <svg class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.4 7.4a1 1 0 01-1.414 0l-3.6-3.6a1 1 0 011.414-1.414l2.893 2.893 6.693-6.693a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 font-serif">Request Submitted</h3>
                <p class="mt-2 text-sm text-gray-600">
                    {{ session('success') ?? 'Thanks! We received your catering request and will reach out shortly.' }}
                </p>
                <button
                    type="button"
                    @click="showSuccess = false"
                    class="mt-6 inline-flex items-center justify-center rounded-xl bg-metro-dark px-5 py-2 text-sm font-semibold text-white transition hover:bg-metro-hover"
                >
                    Close
                </button>
            </div>
        </div>
    </section>

    <x-gallery title="Catering Moments" description="A quick look at past events, set-ups, and curated spreads." slug="catering" />

    <section class="py-12">
        <div class="container mx-auto max-w-7xl px-4">
            <div class="rounded-3xl border border-gray-100 bg-white p-8 shadow-sm flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 font-serif">Ready to request catering?</h2>
                    <p class="mt-2 text-gray-600 text-lg max-w-2xl">
                        Share your event details and preferred package. We will confirm availability and next steps.
                    </p>
                </div>
                <a
                    href="{{ route('catering.request.page') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-metro-dark px-6 py-3 text-sm font-semibold text-white transition hover:bg-metro-hover"
                >
                    Go to Request Form
                </a>
            </div>
        </div>
    </section>

    <x-locations />

    <script>
        function setPrice(mode) {
            const ppBtn = document.getElementById('ppBtn');
            const totalBtn = document.getElementById('totalBtn');
            const prices = document.querySelectorAll('.price-value');
            const labels = document.querySelectorAll('.price-label');
            const currency = @json(__('ETB'));
            const labelPerson = @json(__('person'));
            const labelPackage = @json(__('Package'));
            const contactLabel = @json(__('Contact us'));

            if (!ppBtn || !totalBtn) return;

            if (mode === 'person') {
                ppBtn.classList.add('bg-accent', 'text-white', 'shadow-sm');
                totalBtn.classList.remove('bg-accent', 'text-white', 'shadow-sm');
            } else {
                totalBtn.classList.add('bg-accent', 'text-white', 'shadow-sm');
                ppBtn.classList.remove('bg-accent', 'text-white', 'shadow-sm');
            }

            prices.forEach((el, i) => {
                const pp = el.dataset.pp || '';
                const total = el.dataset.total || '';
                const primary = mode === 'total' ? total : pp;
                const fallback = mode === 'total' ? pp : total;
                const value = primary || fallback;

                if (!value) {
                    el.textContent = contactLabel;
                    labels[i].textContent = '';
                    return;
                }

                const useTotal = value === total && total !== '';
                el.textContent = `${currency} ${Number(value).toLocaleString()}`;
                labels[i].textContent = useTotal ? `/ ${labelPackage}` : `/ ${labelPerson}`;
            });
        };
        document.addEventListener('DOMContentLoaded', () => {
            const prices = document.querySelectorAll('.price-value');
            const toggle = document.querySelector('.pricing-toggle');
            const hasSwitch = Array.from(prices).some((el) => el.dataset.pp && el.dataset.total);

            if (!hasSwitch && toggle) {
                toggle.classList.add('hidden');
            }

            setPrice(hasSwitch ? 'person' : 'person');
        });
    </script>
</x-layouts.app-main>
