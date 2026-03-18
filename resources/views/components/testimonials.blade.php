{{-- @blaze(compile: true)

<section class="py-20 px-4">
    <div class="container mx-auto max-w-7xl bg-[#eeeeee] py-16 px-8 rounded-[3rem]">
        
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 tracking-tight">
                What our guests are saying
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <div class="bg-white p-10 rounded-[2rem] shadow-sm flex flex-col justify-between min-h-[350px]">
                <div>
                    <div class="text-black text-xl mb-6">★★★★★</div>
                    <p class="text-gray-800 text-base leading-relaxed mb-4 content-visibility-auto">
                        Wow! This small Pizza establishment is really amazing! The pizza here is to die for! You definitely have to come in and try it yourself! It only has like two tables for seating. I would...
                    </p>
                    <a href="#" class="text-gray-500 text-sm underline hover:text-black transition">View more</a>
                </div>
                
                <div class="flex items-center gap-4 mt-8">
                    <img src="https://i.pravatar.cc/150?u=jerry" alt="Jerry R." class="w-12 h-12 rounded-full object-cover shadow-sm" />
                    <p class="font-semibold text-gray-700">Jerry R.</p>
                </div>
            </div>

            <div class="bg-white p-10 rounded-[2rem] shadow-sm flex flex-col justify-between min-h-[350px]">
                <div>
                    <div class="text-black text-xl mb-6">★★★★★</div>
                    <p class="text-gray-800 text-base leading-relaxed mb-4 content-visibility-auto ">
                        I'm confident this is the best pizzeria in New Braunfels. The pizza is so fresh, thin, lightly crispy and flavorful. They always have the sweetest employees working - literally all of...
                    </p>
                    <a href="#" class="text-gray-500 text-sm underline hover:text-black transition">View more</a>
                </div>
                
                <div class="flex items-center gap-4 mt-8">
                    <img src="https://i.pravatar.cc/150?u=leslie" alt="Leslie E." class="w-12 h-12 rounded-full object-cover shadow-sm" />
                    <p class="font-semibold text-gray-700">Leslie E.</p>
                </div>
            </div>

            <div class="bg-white p-10 rounded-[2rem] shadow-sm flex flex-col justify-between min-h-[350px]">
                <div>
                    <div class="text-black text-xl mb-6">★★★★★</div>
                    <p class="text-gray-800 text-base leading-relaxed mb-4 content-visibility-auto">
                        Hands down the best pizza in the area without a doubt. Ive tried just about every pizza joint in the area and none of them can compare to Mattenga's. I have ordered a couple times and...
                    </p>
                    <a href="#" class="text-gray-500 text-sm underline hover:text-black transition">View more</a>
                </div>
                
                <div class="flex items-center gap-4 mt-8">
                    <img src="https://i.pravatar.cc/150?u=ryan" alt="Ryan F." class="w-12 h-12 rounded-full object-cover shadow-sm" />
                    <p class="font-semibold text-gray-700">Ryan F.</p>
                </div>
            </div>

        </div>
    </div>
</section> --}}

@props([
    'title' => 'What our guests are saying',
    'testimonials' => []
])

@php
    $testimonials = collect($testimonials);

    if ($testimonials->isEmpty()) {
        $testimonials = \App\Models\Review::query()
            ->featured()
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($review) => [
                'name' => $review->reviewer_name,
                'stars' => $review->stars,
                'avatar' => $review->getFirstMediaUrl('avatar') ?: 'https://placehold.co/96x96?text=' . urlencode((string) str($review->reviewer_name)->substr(0, 1)),
                'content' => $review->content,
            ]);
    }
@endphp

<section class="py-20 px-4">
    <div class="container mx-auto max-w-7xl bg-[#eeeeee] py-16 px-8 rounded-[3rem]">
        
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 tracking-tight">
                {{ $title }}
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($testimonials as $item)
                <div 
                    x-data="{ expanded: false }" 
                    class="bg-white p-10 rounded-[2rem] shadow-sm flex flex-col justify-between min-h-[350px]"
                >
                    <div>
                        <div class="text-black text-xl mb-6">
                            {{ str_repeat('★', $item['stars'] ?? 5) }}
                        </div>
                        
                        {{-- The Magic: Line Clamp 4 --}}
                        <p 
                            :class="expanded ? '' : 'line-clamp-4'"
                            class="text-gray-800 text-base leading-relaxed mb-2 transition-all duration-300"
                        >
                            {{ $item['content'] }}
                        </p>

                        <button 
                            @click="expanded = !expanded"
                            class="text-gray-500 text-sm underline hover:text-black transition cursor-pointer"
                        >
                            <span x-text="expanded ? 'See less' : 'See more'"></span>
                        </button>
                    </div>
                    
                    <div class="flex items-center gap-4 mt-8">
                        <img 
                            src="{{ $item['avatar'] }}" 
                            alt="{{ $item['name'] }}" 
                            class="w-12 h-12 rounded-full object-cover shadow-sm" 
                        />
                        <p class="font-semibold text-gray-700">{{ $item['name'] }}</p>
                    </div>
                </div>
            @empty
                <div class="md:col-span-3 text-center text-gray-600">
                    No featured testimonials available yet.
                </div>
            @endforelse
        </div>
    </div>
</section>
