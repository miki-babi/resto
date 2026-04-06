<x-layouts.app-main>
    @push('meta')
        <meta name="description" content="Place a preorder for cakes and pastry items.">
        <meta property="og:title" content="Cake Preorder">
    @endpush

    <section class="bg-gray-50 py-14">
        <div class="container mx-auto max-w-6xl px-4">
            <div class="mb-8">
                <h1 class="font-serif text-4xl font-bold text-gray-900">Preorder Cakes & Pastries</h1>
                <p class="mt-3 text-gray-600">
                    Share your phone number, pickup details, and quantities.
                </p>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-lg md:p-8">
                @if (session('success'))
                    <div class="mb-6 rounded-2xl bg-green-50 px-4 py-3 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-2xl bg-red-50 px-4 py-3 text-sm text-red-700">
                        <ul class="list-inside list-disc">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('preorder.cake.submit') }}" class="space-y-8">
                    @csrf

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Phone Number</label>
                            <input
                                type="text"
                                name="phone"
                                value="{{ old('phone') }}"
                                placeholder="+2519..."
                                required
                                class="mt-2 w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-metro-red focus:outline-none"
                            />
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700">Pickup Location</label>
                            <select
                                name="pickup_location_id"
                                required
                                class="mt-2 w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-metro-red focus:outline-none"
                            >
                                <option value="" disabled {{ old('pickup_location_id') ? '' : 'selected' }}>Select location</option>
                                @foreach ($pickupLocations as $location)
                                    <option value="{{ $location->id }}" {{ (string) old('pickup_location_id') === (string) $location->id ? 'selected' : '' }}>
                                        {{ $location->name }}@if($location->address) - {{ $location->address }}@endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700">Pickup Date</label>
                            <input
                                type="date"
                                name="pickup_date"
                                min="{{ now()->toDateString() }}"
                                value="{{ old('pickup_date') }}"
                                required
                                class="mt-2 w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-metro-red focus:outline-none"
                            />
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700">Pickup Time</label>
                            <input
                                type="time"
                                name="pickup_time"
                                value="{{ old('pickup_time') }}"
                                required
                                class="mt-2 w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-metro-red focus:outline-none"
                            />
                        </div>
                    </div>

                    <div>
                        <h2 class="font-serif text-2xl font-bold text-gray-900">Pastry Items</h2>
                        <p class="mt-2 text-sm text-gray-600">
                            Set quantity for each pastry item you want.
                        </p>

                        @error('quantities')
                            <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                        @enderror

                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                            @forelse ($pastryItems as $item)
                                <div class="rounded-2xl border border-gray-200 p-4">
                                    <p class="font-semibold text-gray-900">{{ $item->name }}</p>
                                    @if ($item->description)
                                        <p class="mt-1 text-sm text-gray-600">{{ $item->description }}</p>
                                    @endif

                                    <div class="mt-3 flex items-center justify-between gap-3">
                                        <p class="text-sm font-semibold text-gray-700">${{ number_format((float) $item->price, 2) }}</p>
                                        <input
                                            type="number"
                                            min="0"
                                            max="100"
                                            step="1"
                                            name="quantities[{{ $item->id }}]"
                                            value="{{ old('quantities.' . $item->id, 0) }}"
                                            class="w-24 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm focus:border-metro-red focus:outline-none"
                                            aria-label="Quantity for {{ $item->name }}"
                                        />
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800 md:col-span-2">
                                    No pastry items are currently available for preorder.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-xl bg-metro-dark px-4 py-3 text-sm font-semibold text-white transition hover:bg-metro-hover"
                    >
                        Place Cake Preorder
                    </button>
                </form>
            </div>
        </div>
    </section>

    <x-locations />
</x-layouts.app-main>
