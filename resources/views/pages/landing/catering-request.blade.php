<x-layouts.app-main>
    @push('meta')
        <meta name="description" content="Request catering from Mera Coffee.">
        <meta property="og:title" content="Mera Coffee Catering Request">
        <meta property="og:image" content="https://images.pexels.com/photos/302902/pexels-photo-302902.jpeg">
    @endpush

    <x-hero-main
        image="https://images.pexels.com/photos/302902/pexels-photo-302902.jpeg"
        subtitle="Tell us about your event"
        title="Request Catering"
        primary-button-text="View Packages"
        primary-button-url="/catering#packages"
        secondary-button-text="Call Us"
        secondary-button-url="tel:+251000000000"
    />

    <section class="py-14 bg-gray-50">
        <div class="container mx-auto max-w-6xl px-4">
            <div class="grid gap-10 lg:grid-cols-[1.1fr_1fr]">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 font-serif">Request Catering</h2>
                    <p class="mt-3 text-gray-600 text-lg">
                        Tell us a bit about your event and we will confirm availability, menu options, and next steps.
                    </p>

                    <div class="mt-6 space-y-4 text-sm text-gray-600">
                        <div class="flex items-start gap-3">
                            <span class="mt-1 inline-flex h-2 w-2 rounded-full bg-metro-red"></span>
                            <p>Flexible packages for meetings, birthdays, and corporate events.</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="mt-1 inline-flex h-2 w-2 rounded-full bg-metro-red"></span>
                            <p>Delivery and setup available upon request.</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="mt-1 inline-flex h-2 w-2 rounded-full bg-metro-red"></span>
                            <p>Need something custom? Leave a note and we will follow up.</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl bg-white p-6 shadow-lg">
                    @if (session('success'))
                        <div class="mb-4 rounded-2xl bg-green-50 px-4 py-3 text-sm text-green-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 rounded-2xl bg-red-50 px-4 py-3 text-sm text-red-700">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('catering.request') }}" class="space-y-4">
                        @csrf
                        @php
                            $selectedPackageId = old('catering_package_id', $selectedPackageId ?? null);
                        @endphp
                        <div>
                            <label class="text-sm font-semibold text-gray-700">Package</label>
                            <select
                                name="catering_package_id"
                                class="mt-2 w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-metro-red focus:outline-none"
                                required
                            >
                                <option value="" disabled {{ $selectedPackageId ? '' : 'selected' }}>Select a package</option>
                                @foreach ($cateringPackages ?? [] as $package)
                                    <option value="{{ $package['id'] }}" {{ (string) $selectedPackageId === (string) $package['id'] ? 'selected' : '' }}>
                                        {{ $package['name'] }} (Min {{ $package['min_guests'] }} guests)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700">Full name</label>
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                class="mt-2 w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-metro-red focus:outline-none"
                                placeholder="Your name"
                                required
                            />
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700">Contact</label>
                            <input
                                type="text"
                                name="contact"
                                value="{{ old('contact') }}"
                                class="mt-2 w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-metro-red focus:outline-none"
                                placeholder="Phone or email"
                                required
                            />
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700">Notes (optional)</label>
                            <textarea
                                name="note"
                                rows="4"
                                class="mt-2 w-full rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-metro-red focus:outline-none"
                                placeholder="Event date, guest count, dietary needs, etc."
                            >{{ old('note') }}</textarea>
                        </div>

                        <button
                            type="submit"
                            class="w-full rounded-xl bg-metro-dark px-4 py-3 text-sm font-semibold text-white transition hover:bg-metro-hover"
                        >
                            Submit Catering Request
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <x-locations />
</x-layouts.app-main>
