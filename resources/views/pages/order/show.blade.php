<x-layouts.app-main>
    @php
        $statusLabels = [
            'pending' => 'Pending',
            'preparing' => 'Preparing',
            'ready' => 'Ready',
            'completed' => 'Picked Up',
            'cancelled' => 'Cancelled',
        ];
    @endphp

    <main class="flex-1 max-w-3xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
            <div class="flex items-start justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">Order #{{ $order->id }}</h1>
                    <p class="text-slate-500 font-medium mt-1">
                        Pickup:
                        <span class="font-bold text-slate-900">{{ $order->pickupLocation?->name ?? '—' }}</span>
                        • <span class="font-bold text-slate-900">{{ $order->pickupLabel() }}</span>
                    </p>
                </div>

                <div class="text-right">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Status</p>
                    <p id="order-status" class="text-xl font-black text-metro-red">{{ $statusLabels[$order->status] ?? $order->status }}</p>
                </div>
            </div>

            <div class="mt-8 border-t border-slate-200 pt-6">
                <h2 class="text-lg font-extrabold text-slate-900 mb-4">Items</h2>
                <div class="space-y-3">
                    @foreach ($order->items as $item)
                        <div class="rounded-2xl border border-slate-200 p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    <p class="font-extrabold text-slate-900">{{ $item->quantity }} × {{ $item->title }}</p>
                                    @if ($item->variant_name)
                                        <p class="text-xs text-slate-500 mt-1">Variant: {{ $item->variant_name }}</p>
                                    @endif
                                    @if ($item->addons->count())
                                        <p class="text-xs text-slate-500 mt-1">
                                            <span class="font-bold">Add-ons:</span>
                                            {{ $item->addons->pluck('name')->join(', ') }}
                                        </p>
                                    @endif
                                </div>
                                <p class="font-black text-slate-900">Br {{ number_format((float) $item->unit_price, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-8 border-t border-slate-200 pt-6 flex items-center justify-between">
                <p class="text-sm font-bold text-slate-500">Total</p>
                <p class="text-2xl font-black text-slate-900">Br {{ number_format((float) $order->total_price, 2) }}</p>
            </div>

            <p class="text-xs text-slate-500 mt-6">Keep this page open to see live status updates.</p>
        </div>
    </main>

    @push('scripts')
        <script>
            (function () {
                const statusLabels = @json($statusLabels);
                const statusEl = document.getElementById('order-status');

                async function poll() {
                    try {
                        const res = await fetch('{{ route('order.poll', $order->public_token) }}', {
                            headers: { 'Accept': 'application/json' }
                        });

                        if (!res.ok) return;
                        const data = await res.json();

                        if (data && data.status) {
                            statusEl.textContent = statusLabels[data.status] || data.status;
                        }
                    } catch (e) {
                        // ignore
                    }
                }

                poll();
                setInterval(poll, 10000);
            })();
        </script>
    @endpush
</x-layouts.app-main>

