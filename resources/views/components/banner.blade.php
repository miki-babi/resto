@blaze(compile: true)

@props([
    'image',
    'title',
    'description',
    'reversed' => false,
    'buttonText' => null,
    'buttonUrl' => '#'
])

<section class="relative mx-auto max-w-7xl px-4 py-12">
    <div class="relative h-[550px] w-full overflow-hidden rounded-[2.5rem] shadow-2xl">

        <img src="https://mattengas.com/pluto-images/funnel/images/8497172b-8ba2-4eed-8735-0e6382617a6a?w=1920&fit=cover"
            alt="Mattenga's Pizza and Breadsticks" class="absolute inset-0 h-full w-full object-cover" />

        <div class="absolute inset-0 bg-black/10"></div>

        <div class="relative flex h-full items-center px-6 md:px-12">

            <div
                class="w-full max-w-lg rounded-[2rem] bg-white/85 p-8 md:p-12 backdrop-blur-md shadow-lg border border-white/30">

                <h2 class="font-sans text-2xl md:text-3xl font-bold mb-4 text-gray-900 tracking-tight">
                    {{ $title }}
                </h2>

                <p class="text-gray-700 text-sm md:text-base mb-8 leading-relaxed">
                    {{ $description }}
                </p>

                <a href="{{ $buttonUrl }}"
                    class="inline-flex items-center bg-[#b90000] text-white px-6 py-4 rounded-xl font-bold text-sm transition hover:bg-red-800 group">
                    {{ $buttonText }}
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="ml-2 h-4 w-4 transition-transform group-hover:translate-x-1" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="9 5l7 7-7 7" />
                    </svg>
                </a>

            </div>
        </div>
    </div>
</section>
