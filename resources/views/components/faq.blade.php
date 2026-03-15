@props([
    'title' => 'Frequently Asked Questions',
    'items' => []
])

<section {{ $attributes->merge(['class' => 'py-20 bg-white']) }}>
    <div class="container mx-auto px-4 max-w-5xl">
        <h2 class="text-2xl font-bold mb-12 text-gray-900">{{ $title }}</h2>
        
        <div class="border-t border-gray-200">
            @foreach($items as $item)
                <details class="group border-b border-gray-200">
                    <summary class="flex justify-between items-center py-6 font-bold text-gray-800 cursor-pointer list-none">
                        <span class="text-lg">{{ $item['question'] }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                             class="w-5 h-5 transition-transform duration-300 group-open:rotate-180">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </summary>
                    <div class="pb-6 text-gray-600 leading-relaxed">
                        {!! $item['answer'] !!}
                    </div>
                </details>
            @endforeach
        </div>
    </div>
</section>

