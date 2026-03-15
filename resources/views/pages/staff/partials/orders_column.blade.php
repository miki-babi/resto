@php
    /** @var \Illuminate\Support\Collection|\App\Models\MenuItemOrder[] $orders */
@endphp

@if (! $orders->count())
    <div class="rounded-2xl border border-dashed border-slate-300 bg-white/50 p-6 text-center text-sm font-bold text-slate-500">
        No orders here yet.
    </div>
@else
    <div class="space-y-4">
        @foreach ($orders as $order)
            @php
                $minutesLeft = $order->minutesLeft();
                $warningText = null;
                $warningClass = null;

                if ($minutesLeft >= 0 && $minutesLeft <= 5) {
                    $warningText = "⚠ {$minutesLeft} min left";
                    $warningClass = 'text-amber-700 bg-amber-50 border-amber-200';
                } elseif ($minutesLeft < 0) {
                    $warningText = 'Late by ' . abs($minutesLeft) . ' min';
                    $warningClass = 'text-red-700 bg-red-50 border-red-200';
                }
            @endphp

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-lg font-black text-slate-900">#{{ $order->id }}</p>
                        <p class="text-xs font-bold text-slate-500 mt-1">Pickup {{ $order->pickupLabel() }}</p>
                    </div>

                    @if ($warningText)
                        <div class="flex-shrink-0 rounded-xl border px-3 py-2 text-xs font-black {{ $warningClass }}">
                            {{ $warningText }}
                        </div>
                    @endif
                </div>

                <div class="mt-4 space-y-2">
                    @foreach ($order->items as $item)
                        <div class="text-sm">
                            <p class="font-extrabold text-slate-900">
                                {{ $item->quantity }}× {{ $item->title }}
                                @if ($item->variant_name)
                                    <span class="text-xs font-bold text-slate-500">({{ $item->variant_name }})</span>
                                @endif
                            </p>
                            @if ($item->addons->count())
                                <p class="text-xs text-slate-500 mt-1">
                                    + {{ $item->addons->pluck('name')->join(', ') }}
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="mt-5">
                    @if ($status === 'pending')
                        <button
                            type="button"
                            class="w-full rounded-xl bg-slate-900 text-white font-black py-3 hover:bg-black transition"
                            data-order-action-url="{{ route('staff.orders.accept', $order) }}"
                        >
                            Accept
                        </button>
                    @elseif ($status === 'preparing')
                        <button
                            type="button"
                            class="w-full rounded-xl bg-metro-red text-white font-black py-3 hover:bg-red-800 transition"
                            data-order-action-url="{{ route('staff.orders.ready', $order) }}"
                        >
                            Ready
                        </button>
                    @elseif ($status === 'ready')
                        <button
                            type="button"
                            class="w-full rounded-xl bg-emerald-600 text-white font-black py-3 hover:bg-emerald-700 transition"
                            data-order-action-url="{{ route('staff.orders.picked_up', $order) }}"
                        >
                            Picked Up
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif

