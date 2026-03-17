@props([
    'logo' ,
    'links' => [],
    'ctas' => [],
])

<div x-data="{ mobileMenuOpen: false }" class="relative bg-white border-b border-gray-100 sticky top-0 z-[100]">
    <nav
        {{ $attributes->merge(['class' => 'container mx-auto px-4 h-20 flex items-center justify-between max-w-7xl']) }}>

        {{-- Logo --}}
        <div class="flex-shrink-0">
            <a href="/">
                <img alt="Logo" class="h-10 w-auto md:h-12 object-cover rounded-full" src="{{ $logo }}" />
            </a>
        </div>

        {{-- Desktop Navigation (Hidden on Mobile) --}}
        <div class="hidden lg:flex items-center space-x-8 text-sm font-medium uppercase tracking-wider">
            @foreach ($links as $label => $data)
                @if (is_array($data))
                    <div class="relative group" x-data="{ open: false }" @mouseenter="open = true"
                        @mouseleave="open = false">
                        <button class="flex  font-serif items-center hover:text-metro-red transition uppercase">
                            {{ $label }}
                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" 
         x-cloak
         {{-- Added transition for an even smoother feel --}}
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         class="absolute left-0 mt-2 w-48 bg-white border border-gray-100 shadow-xl rounded-xl py-2 z-50 normal-case">
        
        @foreach ($data as $childLabel => $childUrl)
            <a href="{{ $childUrl }}"
                class="block px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-metro-red font-serif">
                {{ $childLabel }}
            </a>
        @endforeach
    </div>
                    </div>
                @else
                    <a class="hover:text-metro-red transition font-serif" href="{{ $data }}">{{ $label }}</a>
                @endif
            @endforeach
        </div>
        {{-- Dynamic CTAs --}}
        <div class=" hidden lg:flex space-x-4">
            @foreach ($ctas as $cta)
                <a href="{{ $cta['url'] ?? '#' }}"
                    class="px-5 py-2 rounded-md font-bold uppercase text-xs transition shadow-sm {{ $cta['class'] ?? 'bg-metro-dark text-white hover:bg-red-800' }}">
                    {{ $cta['text'] }}
                </a>
            @endforeach
        </div>

        {{-- Mobile Actions --}}
        <div class="flex items-center space-x-4 lg:hidden">
            <div class="">
                <a href="/menu"
                    class="bg-metro-grey border border-gray-200 px-5 py-2 rounded-md shadow-sm text-sm font-bold text-black">
                    Menu
                </a>
            </div>

            <button @click="mobileMenuOpen = true" class=" text-gray-800 p-1">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </nav>

    {{-- Full-Screen Mobile Menu Overlay --}}
    <div x-show="mobileMenuOpen" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        class="fixed inset-0 w-full h-full bg-white z-[110] flex flex-col">

        {{-- Header inside full-screen menu --}}
        <div class="h-20 flex items-center justify-between px-6 border-b border-gray-50">
            <span class="text-xs font-bold uppercase tracking-widest text-gray-400 font-serif">Mera Coffee</span>

            <button @click="mobileMenuOpen = false"
                class="text-gray-900 bg-gray-100 p-2 rounded-full transition hover:bg-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Left-Aligned Content --}}
        <div class="flex-1 overflow-y-auto px-8 py-10 font-serif">
            <div class="flex flex-col space-y-6">
                @foreach ($links as $label => $data)
                    @if (is_array($data))
                        <div x-data="{ subOpen: false }" class="border-b border-gray-50 pb-4">
                            <button @click="subOpen = !subOpen"
                                class="w-full text-2xl font-bold text-gray-900 uppercase flex items-center justify-between">
                                {{ $label }}
                                <svg class="w-5 h-5 transition-transform duration-300"
                                    :class="subOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="subOpen" x-collapse class="mt-4 space-y-4 pl-4 border-l-2 border-metro-red">
                                @foreach ($data as $childLabel => $childUrl)
                                    <a href="{{ $childUrl }}"
                                        class="block text-gray-500 text-lg hover:text-metro-red transition">{{ $childLabel }}</a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ $data }}"
                            class="text-2xl font-bold text-gray-900 uppercase border-b border-gray-50 pb-4 block hover:text-metro-red transition">
                            {{ $label }}
                        </a>
                    @endif
                @endforeach
            </div>

            {{-- CTAs Stacked at the Bottom --}}
            <div class="mt-12 space-y-4">
                @foreach ($ctas as $cta)
                    <a href="{{ $cta['url'] ?? '#' }}"
                        class="w-full block text-center px-6 py-4 rounded-xl font-bold uppercase text-sm tracking-wider transition {{ $cta['class'] ?? 'bg-metro-dark text-white' }}">
                        {{ $cta['text'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
