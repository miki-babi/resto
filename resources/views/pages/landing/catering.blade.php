<x-layouts.app-main>
    @push('meta')
        <meta name="description" content="Catering packages for meetings, events, and celebrations from Mera Coffee.">
        <meta property="og:title" content="Mera Coffee Catering">
        <meta property="og:image" content="https://images.pexels.com/photos/302902/pexels-photo-302902.jpeg">
    @endpush

    {{-- Hero Section --}}
    <section class="relative bg-white pt-24 pb-16 lg:pt-32 lg:pb-24 overflow-hidden">
        <div class="max-w-[1200px] mx-auto px-6">
            <div class="flex flex-col lg:flex-row items-center gap-16 lg:gap-24">
                <div class="flex-1 text-center lg:text-left space-y-8">
                    <div class="space-y-4">
                        <span class="inline-block px-4 py-1.5 rounded-full bg-amber-50 text-amber-700 text-[10px] font-black uppercase tracking-[0.2em]">Catering Services</span>
                        <h1 class="text-5xl lg:text-7xl font-black text-gray-900 leading-[1.1] tracking-tight">
                            Elevate Your <span class="text-amber-500">Every Event</span>
                        </h1>
                        <p class="text-lg text-gray-500 font-medium max-w-xl mx-auto lg:mx-0 leading-relaxed">
                            From intimate gatherings to large corporate celebrations, we bring premium coffee and curated food experiences directly to you.
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                        <a href="#packages" class="w-full sm:w-auto px-8 py-5 rounded-2xl bg-black text-white text-sm font-black shadow-premium hover:scale-[1.02] transition-all">
                            View Packages
                        </a>
                        <a href="/catering/request" class="w-full sm:w-auto px-8 py-5 rounded-2xl border border-gray-100 text-gray-900 text-sm font-black hover:bg-gray-50 transition-all">
                            Custom Request
                        </a>
                    </div>
                </div>
                <div class="flex-1 w-full relative">
                    <div class="aspect-[4/5] rounded-[40px] overflow-hidden shadow-2xl relative z-10">
                        <img src="https://images.pexels.com/photos/3184192/pexels-photo-3184192.jpeg" alt="Catering Service" class="w-full h-full object-cover">
                    </div>
                    {{-- Decorative elements --}}
                    <div class="absolute -bottom-10 -left-10 h-64 w-64 bg-amber-100 rounded-full blur-3xl opacity-50 -z-0"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- Services Section --}}
    <section class="py-24 bg-gray-50">
        <div class="max-w-[1200px] mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
                <h2 class="text-3xl font-black text-gray-900">How We Serve You</h2>
                <p class="text-gray-500 font-medium">Versatile catering solutions tailored to your unique requirements.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-10 rounded-[32px] bg-white border border-gray-100 shadow-soft space-y-6 hover:-translate-y-1 transition-all">
                    <div class="h-14 w-14 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-bold text-gray-900">Corporate Events</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">Perfectly timed breakfast and lunch spreads for meetings and conferences.</p>
                    </div>
                </div>
                <div class="p-10 rounded-[32px] bg-white border border-gray-100 shadow-soft space-y-6 hover:-translate-y-1 transition-all">
                    <div class="h-14 w-14 rounded-2xl bg-black flex items-center justify-center text-white">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-bold text-gray-900">Private Parties</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">Birthdays, anniversaries, or casual get-togethers in the comfort of your home.</p>
                    </div>
                </div>
                <div class="p-10 rounded-[32px] bg-white border border-gray-100 shadow-soft space-y-6 hover:-translate-y-1 transition-all">
                    <div class="h-14 w-14 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-xl font-bold text-gray-900">Custom Pop-ups</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">On-site barista stations and artisanal food bars for high-impact brand activations.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Packages Section --}}
    <section class="py-24 bg-white" id="packages" x-data="{
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
        
        <div class="max-w-[1200px] mx-auto px-6">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-16">
                <div class="space-y-4">
                    <h2 class="text-4xl font-black text-gray-900">Catering Packages</h2>
                    <p class="text-gray-500 font-medium">Curated menus designed for consistency and crowd-pleasing variety.</p>
                </div>

                {{-- Pricing Toggle --}}
                <div class="bg-gray-100 rounded-2xl p-1.5 flex gap-1 pricing-toggle">
                    <button type="button" @click="setPrice('person')" id="ppBtn" 
                            class="px-5 py-2.5 rounded-xl text-sm font-black transition-all bg-white text-black shadow-sm">
                        Per Person
                    </button>
                    <button type="button" @click="setPrice('total')" id="totalBtn" 
                            class="px-5 py-2.5 rounded-xl text-sm font-black transition-all text-gray-500 hover:text-gray-900">
                        Full Package
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-12">
                @forelse ($cateringPackages as $package)
                    @php
                        $isEven = $loop->even;
                        $initialPrice = $package['price_per_person'] ?? $package['price_total'];
                        $initialLabel = $package['price_per_person'] !== null ? 'person' : ($package['price_total'] !== null ? 'Package' : '');
                    @endphp
                    <article class="flex flex-col lg:flex-row gap-12 items-center group">
                        <div class="flex-1 w-full {{ $isEven ? 'lg:order-2' : '' }}">
                            <div class="aspect-[16/10] rounded-[40px] overflow-hidden bg-gray-100 relative group-hover:scale-[1.01] transition-transform duration-500">
                                <img src="{{ $package['cover'] }}" alt="{{ $package['name'] }}" class="w-full h-full object-cover">
                                @if(!empty($package['badge_text']))
                                    <div class="absolute top-6 left-6 px-4 py-2 rounded-full {{ $package['badge_class'] }} text-[10px] font-black uppercase tracking-widest shadow-premium">
                                        {{ $package['badge_text'] }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="flex-1 space-y-6 {{ $isEven ? 'lg:order-1' : '' }}">
                            <div class="flex justify-between items-start">
                                <h3 class="text-3xl font-black text-gray-900 leading-tight">{{ $package['name'] }}</h3>
                                <div class="text-right shrink-0">
                                    <p class="text-2xl font-black text-amber-600 price-value"
                                       data-pp="{{ $package['price_per_person'] ?? '' }}"
                                       data-total="{{ $package['price_total'] ?? '' }}">
                                        @if ($initialPrice !== null)
                                            ETB {{ number_format($initialPrice) }}
                                        @else
                                            Contact us
                                        @endif
                                    </p>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 price-label">
                                        @if ($initialLabel) / {{ $initialLabel }} @endif
                                    </p>
                                </div>
                            </div>
                            
                            <div class="prose prose-gray prose-sm max-w-none font-medium leading-relaxed">
                                {!! $package['description'] !!}
                            </div>

                            @if (!empty($package['highlights']))
                                <div class="grid grid-cols-2 gap-4">
                                    @foreach ($package['highlights'] as $highlight)
                                        <div class="flex items-center gap-3">
                                            <div class="h-2 w-2 rounded-full bg-amber-400"></div>
                                            <span class="text-sm font-bold text-gray-700">{{ $highlight }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="pt-4 flex items-center gap-6">
                                <button type="button" @click="openPackage(@js($package))"
                                        class="px-8 py-4 rounded-2xl bg-black text-white text-sm font-black shadow-premium hover:scale-[1.02] transition-all">
                                    Request Now
                                </button>
                                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Min. {{ $package['min_guests'] }} Guests</p>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="py-24 rounded-[40px] bg-gray-50 border-2 border-dashed border-gray-100 flex flex-col items-center justify-center text-center">
                        <p class="text-gray-400 font-bold">No packages available at the moment.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Package Modal --}}
        <div x-show="isOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[300] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4" x-cloak
            @click.self="isOpen = false">
            <div class="relative w-full max-w-5xl rounded-[40px] bg-white shadow-2xl overflow-hidden">
                <button @click="isOpen = false"
                    class="absolute top-6 right-6 z-20 flex h-10 w-10 items-center justify-center rounded-full bg-white/90 backdrop-blur-sm text-black shadow-premium hover:scale-110 transition">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div class="grid grid-cols-1 lg:grid-cols-[0.9fr_1.1fr]">
                    <div class="relative bg-gray-100">
                        <div class="aspect-[16/9] lg:h-full lg:aspect-auto">
                            <img :src="pkg.images[activeImage] || pkg.cover" :alt="pkg.name"
                                class="h-full w-full object-cover">
                        </div>
                        <template x-if="pkg.images && pkg.images.length > 1">
                            <div class="absolute inset-0 flex items-center justify-between px-4">
                                <button @click.stop="prev()"
                                    class="bg-white/90 text-black rounded-2xl h-10 w-10 flex items-center justify-center shadow-premium hover:bg-white transition">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <button @click.stop="next()"
                                    class="bg-white/90 text-black rounded-2xl h-10 w-10 flex items-center justify-center shadow-premium hover:bg-white transition">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    <div class="p-8 lg:p-10 space-y-8 flex flex-col justify-center max-h-[90vh] overflow-y-auto">
                        <div class="space-y-4">
                            <div class="flex justify-between items-start">
                                <div class="space-y-1">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-amber-600">
                                        Requesting Package
                                    </p>
                                    <h3 class="text-3xl font-black text-gray-900 leading-tight" x-text="pkg.name"></h3>
                                </div>
                                <div class="text-right">
                                    <p class="text-xl font-black text-gray-900" x-text="`ETB ${Number(pkg.price_per_person || pkg.price_total).toLocaleString()}`"></p>
                                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400" x-text="pkg.price_per_person ? '/ Person' : '/ Package'"></p>
                                </div>
                            </div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                                Minimum <span x-text="pkg.min_guests" class="text-gray-900"></span> Guests Required
                            </p>
                        </div>

                        <form method="POST" action="{{ route('catering.request') }}" class="space-y-8">
                            @csrf
                            <input type="hidden" name="catering_package_id" :value="pkg.id">

                            <div class="space-y-6">
                                {{-- Primary Contact Info --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Full name</label>
                                        <input type="text" name="name"
                                            class="w-full rounded-2xl bg-gray-50 border-none px-5 py-4 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-amber-500 focus:bg-white transition-all caret-amber-500"
                                            placeholder="Your name" required />
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Phone number</label>
                                        <input type="tel" name="contact" inputmode="tel"
                                            class="w-full rounded-2xl bg-gray-50 border-none px-5 py-4 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-amber-500 focus:bg-white transition-all caret-amber-500"
                                            placeholder="0911 223 344" required />
                                    </div>
                                </div>

                                {{-- Event Details --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Event Date</label>
                                        <input type="date" name="event_date"
                                            class="w-full rounded-2xl bg-gray-50 border-none px-5 py-4 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-amber-500 focus:bg-white transition-all caret-amber-500"
                                            required />
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Estimated Guests</label>
                                        <input type="number" name="guest_count" :min="pkg.min_guests"
                                            class="w-full rounded-2xl bg-gray-50 border-none px-5 py-4 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-amber-500 focus:bg-white transition-all caret-amber-500"
                                            placeholder="Min. guests required" required />
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Special Requests (optional)</label>
                                    <textarea name="note" rows="2"
                                        class="w-full rounded-2xl bg-gray-50 border-none px-5 py-4 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-amber-500 focus:bg-white transition-all caret-amber-500"
                                        placeholder="Dietary needs, location details, etc."></textarea>
                                </div>
                            </div>

                            <div class="flex flex-col gap-4">
                                <button type="submit"
                                    class="w-full px-8 py-5 rounded-2xl bg-black text-white text-sm font-black shadow-premium hover:scale-[1.02] transition-all">
                                    Submit Catering Request
                                </button>
                                <button type="button" @click="isOpen = false"
                                    class="w-full px-8 py-4 rounded-2xl border border-gray-100 text-gray-400 text-sm font-bold hover:text-gray-900 transition-all">
                                    Go Back
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Success Confirmation Modal --}}
        <div x-show="showSuccess" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[320] flex items-center justify-center bg-black/70 backdrop-blur-sm p-4" x-cloak
            @click.self="showSuccess = false">
            <div class="relative w-full max-w-md rounded-[40px] bg-white p-12 shadow-2xl text-center space-y-6">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-green-50 text-green-500">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="space-y-2">
                    <h3 class="text-2xl font-black text-gray-900">Request Sent</h3>
                    <p class="text-sm font-medium text-gray-500 leading-relaxed">
                        {{ session('success') ?? 'We have received your catering request and will reach out shortly.' }}
                    </p>
                </div>
                <button type="button" @click="showSuccess = false"
                    class="w-full px-8 py-5 rounded-2xl bg-black text-white text-sm font-black shadow-premium hover:scale-[1.02] transition-all">
                    Dismiss
                </button>
            </div>
        </div>
    </section>

    <div class="bg-gray-50">
        <div class="max-w-[1200px] mx-auto px-6 py-24">
            <x-gallery title="Catering Moments" description="A quick look at past events, set-ups, and curated spreads." slug="catering" />
        </div>
    </div>

    <x-locations />

    <script>
        function setPrice(mode) {
            const ppBtn = document.getElementById('ppBtn');
            const totalBtn = document.getElementById('totalBtn');
            const prices = document.querySelectorAll('.price-value');
            const labels = document.querySelectorAll('.price-label');

            if (!ppBtn || !totalBtn) return;

            const activeClasses = ['bg-white', 'text-black', 'shadow-sm'];
            const inactiveClasses = ['text-gray-500', 'hover:text-gray-900'];

            if (mode === 'person') {
                ppBtn.classList.add(...activeClasses);
                ppBtn.classList.remove(...inactiveClasses);
                totalBtn.classList.remove(...activeClasses);
                totalBtn.classList.add(...inactiveClasses);
            } else {
                totalBtn.classList.add(...activeClasses);
                totalBtn.classList.remove(...inactiveClasses);
                ppBtn.classList.remove(...activeClasses);
                ppBtn.classList.add(...inactiveClasses);
            }

            prices.forEach((el, i) => {
                const pp = el.dataset.pp || '';
                const total = el.dataset.total || '';
                const primary = mode === 'total' ? total : pp;
                const fallback = mode === 'total' ? pp : total;
                const value = primary || fallback;

                if (!value) {
                    el.textContent = 'Contact us';
                    labels[i].textContent = '';
                    return;
                }

                const useTotal = value === total && total !== '';
                el.textContent = `ETB ${Number(value).toLocaleString()}`;
                labels[i].textContent = useTotal ? '/ Package' : '/ person';
            });
        };
        document.addEventListener('DOMContentLoaded', () => {
            const prices = document.querySelectorAll('.price-value');
            const toggle = document.querySelector('.pricing-toggle');
            const hasSwitch = Array.from(prices).some((el) => el.dataset.pp && el.dataset.total);

            if (!hasSwitch && toggle) {
                toggle.parentElement.classList.add('hidden');
            }
        });
    </script>
</x-layouts.app-main>
