@blaze(compile: true)

@props([
    'backgroundImage',

])

<section {{ $attributes->merge(['class' => 'relative h-auto md:h-[700px] flex items-end text-white overflow-hidden']) }}
    style="background-image: url('{{ $backgroundImage }}'); background-size: cover; background-position: center;" loading="lazy">

    {{-- Dark Gradient Overlay --}}
    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>

    <div class="container mx-auto px-4 pb-12 relative z-10 max-w-7xl">
       {{ $slot }}
    </div>
</section>