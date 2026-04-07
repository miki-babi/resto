<x-layouts.app-main>
    @push('meta')
        <meta name="description" content="{{ $preOrder->source_type === 'cake' ? 'Cake and pastry preorder confirmation' : 'Menu preorder confirmation' }}">
        <meta property="og:title" content="Preorder Confirmation">
    @endpush

    @php
        $isCakePreorder = $preOrder->source_type === 'cake';
        $preorderLabel = $isCakePreorder ? 'Cake & Pastry' : 'Menu';
        $repeatPreorderRoute = $isCakePreorder ? 'preorder.cake' : 'preorder.menu';
        $emptyItemLabel = $isCakePreorder ? 'Pastry Item' : 'Menu Item';
        $pickupDateLabel = $preOrder->pickup_date?->format('D, M j, Y');
        $pickupTimeLabel = $preOrder->pickup_time ? \Carbon\Carbon::parse((string) $preOrder->pickup_time)->format('g:i A') : null;
        $items = is_array($preOrder->items_summary) ? $preOrder->items_summary : [];
    @endphp

    <section class="min-h-screen bg-[#f3f3f3] py-8">
        <div class="mx-auto max-w-4xl px-4 lg:px-6">
            <div class="rounded-3xl bg-white p-6 shadow-sm md:p-8">
                <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-800">
                    {{ $preorderLabel }} preorder received successfully.
                </div>

                <div class="mt-5">
                    <p class="text-sm font-semibold uppercase tracking-wide text-gray-500">Order Number</p>
                    <p class="mt-1 text-3xl font-bold text-gray-900">{{ $preOrder->order_number }}</p>
                </div>

                <h1 class="mt-6 text-2xl font-bold text-gray-900 md:text-3xl">Thank you for your {{ strtolower($preorderLabel) }} preorder</h1>
                <p class="mt-2 text-sm text-gray-600">Please keep your order number for pickup and support.</p>

                <div class="mt-6 grid gap-4 rounded-2xl border border-gray-200 bg-gray-50 p-4 sm:grid-cols-2">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Phone</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $preOrder->phone }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Pickup Location</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $preOrder->pickupLocation?->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Pickup Date</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $pickupDateLabel ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Pickup Time</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $pickupTimeLabel ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="mt-6">
                    <h2 class="text-xl font-bold text-gray-900">Order Summary</h2>

                    <div class="mt-3 space-y-3">
                        @forelse ($items as $item)
                            @php
                                $name = trim((string) ($item['name'] ?? $emptyItemLabel));
                                $quantity = (int) ($item['quantity'] ?? 0);
                                $lineTotal = (float) ($item['line_total_price'] ?? 0);
                                $variant = trim((string) ($item['variant'] ?? ''));
                                $addons = collect($item['addons'] ?? [])->filter()->values()->all();
                            @endphp

                            <div class="rounded-xl border border-gray-200 px-4 py-3">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $quantity }}x {{ $name }}</p>
                                        @if ($variant !== '')
                                            <p class="text-xs text-gray-600">Variant: {{ $variant }}</p>
                                        @endif
                                        @if (!empty($addons))
                                            <p class="text-xs text-gray-600">Addons: {{ implode(', ', $addons) }}</p>
                                        @endif
                                    </div>
                                    <p class="text-sm font-semibold text-gray-900">${{ number_format($lineTotal, 2) }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="rounded-xl border border-dashed border-gray-300 px-4 py-3 text-sm text-gray-600">
                                No item summary was captured for this preorder.
                            </p>
                        @endforelse
                    </div>

                    <div class="mt-4 flex items-center justify-between border-t border-gray-200 pt-4">
                        <p class="text-sm text-gray-600">Total</p>
                        <p class="text-2xl font-bold text-gray-900">${{ number_format((float) $preOrder->total_price, 2) }}</p>
                    </div>
                </div>

                <div class="mt-7 flex flex-wrap gap-3">
                    <a
                        href="{{ route($repeatPreorderRoute) }}"
                        class="inline-flex items-center rounded-xl bg-black px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-800"
                    >
                        Place Another {{ $preorderLabel }} Preorder
                    </a>
                    <a
                        href="{{ route('menu') }}"
                        class="inline-flex items-center rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-100"
                    >
                        Back to Menu
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app-main>
