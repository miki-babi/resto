<x-layouts.app-main>
    {{-- 1. Push SEO tags specifically for Home --}}
    @push('meta')
        <meta name="description"
            content="Experience the heart of Bisrate Gebriel at Mera Coffee. A cozy coffee shop perfect for relaxing, working, and enjoying fine brews.">
        <meta property="og:title" content="Mera Coffee | Cozy Coffee Shop in Bisrate Gebriel">
        <meta property="og:image" content="https://images.pexels.com/photos/302902/pexels-photo-302902.jpeg">
        {{-- ... add other meta tags here ... --}}
    @endpush

    <main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-10">
      
        
        <!-- Popular Items Section -->
        <section class="mb-16">
          


            @php
    $pizzas = [
        [
            'title' => 'Cheese Pizza (14")',
            'price' => '$14.99',
            'description' => 'A delicious classic with gooey mozzarella.',
            'images' => ['https://lh3.googleusercontent.com/aida-public/AB6AXuCFLCo_kyxeWIe3N_tOQIXRNnwPe7nWUb-3XzjWdg-FCI56NDbDAfeLlbUfOv4-NHvbcfL8cL6ozIjJBqa6C3c4O6x_EHzPDnkQEHBRG3uVQBayXK3vb9_Gm8aHIg3eiiics9DznmtCjA-ee56BjW4AGKkfWbmey8VpVCKdkUGHTcjwCGCxrHCf4Jlrtn1tDIEv83tKOD1KOYKqDfNEvO9p1gY7jor2ssThyoeTrn1YALfckz9_0HYBcUi4S0xZf6mQMT8Wg4Q9vyY', '/img/pizza1-alt.jpg']
        ],
        // ... more items
    ];
@endphp

<x-featured-menu title="Popular orders" :items="$pizzas" />

            {{-- <x-featured-menu /> --}}
        </section>
        <!-- Appetizers Section -->
        <x-menu />
        <x-menu />
        <x-menu />
        <x-menu />
        <x-menu />
    </main>
    <!-- END: AwardsSection -->


    <x-locations />

  

</x-layouts.app-main>
