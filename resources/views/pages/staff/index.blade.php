<x-layouts.staff title="Staff | Pickup Locations">
    <main class="max-w-5xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">Staff</h1>
                <p class="text-slate-500 font-medium mt-1">Choose a pickup location to open the Command Screen.</p>
            </div>

            <a href="/owner" class="rounded-xl border border-slate-200 bg-white px-4 py-2 font-extrabold text-slate-900 hover:border-slate-900 transition">
                Owner Panel
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($locations as $location)
                <a
                    href="{{ route('staff.command', $location) }}"
                    class="bg-white rounded-2xl border border-slate-200 hover:border-metro-red hover:shadow-md transition p-6"
                >
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <h2 class="text-xl font-black text-slate-900 truncate">{{ $location->name }}</h2>
                            <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ $location->address ?: '—' }}</p>
                        </div>
                        <span class="material-symbols-outlined text-metro-red">arrow_forward</span>
                    </div>
                    <p class="mt-4 text-sm font-extrabold text-slate-900">Open Command Screen</p>
                </a>
            @endforeach
        </div>
    </main>
</x-layouts.staff>

