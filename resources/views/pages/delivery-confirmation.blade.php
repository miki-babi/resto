<x-layouts.app-main>
    @push('meta')
        <meta name="description" content="Same-day express delivery confirmation">
        <meta property="og:title" content="Delivery Confirmation">
    @endpush

    <section class="min-h-screen bg-[#f3f3f3] py-8">
        <div class="mx-auto max-w-4xl px-4 lg:px-6">
            <div class="rounded-3xl bg-white p-6 shadow-sm md:p-8">
                <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-medium text-amber-800">
                    Same-day express delivery order received! We are starting preparation.
                </div>

                <div class="mt-5">
                    <p class="text-sm font-semibold uppercase tracking-wide text-gray-500">Order Number</p>
                    <p class="mt-1 text-3xl font-bold text-gray-900">{{ $delivery->order_number }}</p>
                </div>

                <h1 class="mt-6 text-2xl font-bold text-gray-900 md:text-3xl">Thank you for your delivery order</h1>
                <p class="mt-2 text-sm text-gray-600">Our team is preparing your meal for express delivery.</p>

                <div class="mt-6 grid gap-4 rounded-2xl border border-gray-200 bg-gray-50 p-4 sm:grid-cols-2">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Phone</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $delivery->delivery_phone }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Delivery Address</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $delivery->delivery_address }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Order Date</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $delivery->delivery_date->format('D, M j, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Status</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900 capitalize">{{ $delivery->status }}</p>
                    </div>
                </div>

                <div class="mt-6">
                    <h2 class="text-xl font-bold text-gray-900">Order Summary</h2>

                    <div class="mt-3 space-y-3">
                        @foreach ($items as $item)
                            <div class="rounded-xl border border-gray-200 px-4 py-3">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $item['quantity'] }}x {{ $item['name'] }}</p>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-900">${{ number_format($item['line_total_price'], 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 flex items-center justify-between border-t border-gray-200 pt-4">
                        <p class="text-sm text-gray-600">Total</p>
                        <p class="text-2xl font-bold text-gray-900">${{ number_format((float) $delivery->total_price, 2) }}</p>
                    </div>
                </div>

                <div class="mt-7 flex flex-wrap gap-3">
                    <a
                        href="{{ route('preorder.menu') }}"
                        class="inline-flex items-center rounded-xl bg-black px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-800"
                    >
                        Order More
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
