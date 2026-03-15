<x-layouts.staff :title="('Command Screen | ' . $pickupLocation->name)">
    <div id="new-order-banner" class="hidden fixed top-6 left-1/2 -translate-x-1/2 z-[300] rounded-2xl bg-slate-900 text-white px-6 py-3 font-black shadow-2xl">
        🔔 NEW ORDER
    </div>

    <main class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8">
        <header class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <p class="text-xs font-black text-slate-500 uppercase tracking-wider">Pickup location</p>
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">{{ $pickupLocation->name }}</h1>
                <p class="text-slate-500 font-medium mt-1">{{ $pickupLocation->address ?: '—' }}</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('staff.index') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-2 font-extrabold text-slate-900 hover:border-slate-900 transition">
                    Locations
                </a>
                <a href="/owner" class="rounded-xl border border-slate-200 bg-white px-4 py-2 font-extrabold text-slate-900 hover:border-slate-900 transition">
                    Owner Panel
                </a>
                <button id="enable-sound" type="button" class="rounded-xl bg-metro-red text-white px-4 py-2 font-black hover:bg-red-800 transition">
                    Enable Sound
                </button>
            </div>
        </header>

        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="rounded-3xl border border-slate-200 bg-white p-6">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-lg font-black text-slate-900">NEW</h2>
                    <span id="count-pending" class="rounded-full bg-slate-900 text-white px-3 py-1 text-xs font-black">{{ $pendingOrders->count() }}</span>
                </div>
                <div id="column-pending">
                    @include('pages.staff.partials.orders_column', ['orders' => $pendingOrders, 'status' => 'pending'])
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-lg font-black text-slate-900">PREPARING</h2>
                    <span id="count-preparing" class="rounded-full bg-slate-900 text-white px-3 py-1 text-xs font-black">{{ $preparingOrders->count() }}</span>
                </div>
                <div id="column-preparing">
                    @include('pages.staff.partials.orders_column', ['orders' => $preparingOrders, 'status' => 'preparing'])
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-lg font-black text-slate-900">READY</h2>
                    <span id="count-ready" class="rounded-full bg-slate-900 text-white px-3 py-1 text-xs font-black">{{ $readyOrders->count() }}</span>
                </div>
                <div id="column-ready">
                    @include('pages.staff.partials.orders_column', ['orders' => $readyOrders, 'status' => 'ready'])
                </div>
            </div>
        </section>
    </main>

    @push('scripts')
        <script>
            (function () {
                const pollUrl = @json(route('staff.command.poll', $pickupLocation));
                const csrfToken = @json(csrf_token());

                const pendingColumn = document.getElementById('column-pending');
                const preparingColumn = document.getElementById('column-preparing');
                const readyColumn = document.getElementById('column-ready');

                const pendingCount = document.getElementById('count-pending');
                const preparingCount = document.getElementById('count-preparing');
                const readyCount = document.getElementById('count-ready');

                const banner = document.getElementById('new-order-banner');
                const enableSoundBtn = document.getElementById('enable-sound');

                let pendingIds = new Set(@json($pendingIds));
                let soundEnabled = localStorage.getItem('staff_sound_enabled') === '1';

                let audioCtx = null;

                function getAudioCtx() {
                    if (!audioCtx) {
                        audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                    }
                    return audioCtx;
                }

                async function enableSound() {
                    soundEnabled = true;
                    localStorage.setItem('staff_sound_enabled', '1');

                    const ctx = getAudioCtx();
                    if (ctx.state === 'suspended') {
                        try { await ctx.resume(); } catch (e) {}
                    }

                    enableSoundBtn.textContent = 'Sound Enabled';
                    enableSoundBtn.classList.remove('bg-metro-red', 'hover:bg-red-800');
                    enableSoundBtn.classList.add('bg-emerald-600', 'hover:bg-emerald-700');
                }

                function beep() {
                    const ctx = getAudioCtx();
                    if (ctx.state === 'suspended') return;

                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();

                    osc.type = 'sine';
                    osc.frequency.value = 880;
                    gain.gain.value = 0.08;

                    osc.connect(gain);
                    gain.connect(ctx.destination);

                    osc.start();
                    osc.stop(ctx.currentTime + 0.18);
                }

                function showBanner() {
                    banner.classList.remove('hidden');
                    clearTimeout(showBanner._t);
                    showBanner._t = setTimeout(() => banner.classList.add('hidden'), 2200);
                }

                async function poll() {
                    try {
                        const res = await fetch(pollUrl, { headers: { 'Accept': 'application/json' } });
                        if (!res.ok) return;

                        const data = await res.json();
                        if (!data || !data.columns) return;

                        pendingColumn.innerHTML = data.columns.pending || '';
                        preparingColumn.innerHTML = data.columns.preparing || '';
                        readyColumn.innerHTML = data.columns.ready || '';

                        if (data.counts) {
                            pendingCount.textContent = data.counts.pending ?? pendingCount.textContent;
                            preparingCount.textContent = data.counts.preparing ?? preparingCount.textContent;
                            readyCount.textContent = data.counts.ready ?? readyCount.textContent;
                        }

                        const currentPending = Array.isArray(data.pending_ids) ? data.pending_ids : [];
                        const newlyArrived = currentPending.filter(id => !pendingIds.has(id));

                        if (newlyArrived.length) {
                            showBanner();
                            if (soundEnabled) beep();
                        }

                        pendingIds = new Set(currentPending);
                    } catch (e) {
                        // ignore
                    }
                }

                document.addEventListener('click', async function (e) {
                    const btn = e.target.closest('[data-order-action-url]');
                    if (!btn) return;

                    const url = btn.getAttribute('data-order-action-url');
                    if (!url) return;

                    btn.disabled = true;
                    btn.classList.add('opacity-60', 'cursor-not-allowed');

                    try {
                        const res = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                            },
                        });

                        if (!res.ok) {
                            const data = await res.json().catch(() => ({}));
                            alert(data.message || 'Action failed.');
                        }
                    } catch (err) {
                        alert('Network error.');
                    } finally {
                        await poll();
                        btn.disabled = false;
                        btn.classList.remove('opacity-60', 'cursor-not-allowed');
                    }
                });

                enableSoundBtn.addEventListener('click', enableSound);

                if (soundEnabled) {
                    enableSoundBtn.textContent = 'Sound Enabled';
                    enableSoundBtn.classList.remove('bg-metro-red', 'hover:bg-red-800');
                    enableSoundBtn.classList.add('bg-emerald-600', 'hover:bg-emerald-700');
                }

                poll();
                setInterval(poll, 5000);
            })();
        </script>
    @endpush
</x-layouts.staff>

