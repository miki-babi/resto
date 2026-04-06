<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    
    {{-- Dynamic Title --}}
    <title>{{ $title ?? 'Mera Coffee | Best Coffee Shop in Bisrate Gebriel' }}</title>

    {{-- SEO Stacks: This allows each page to have its own description/og tags --}}
    @stack('meta')

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
 <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
</head>

<body class="font-sans text-metroDark bg-white antialiased">
    
    <header class="sticky top-0 z-50 bg-white border-b border-gray-100 shadow-sm">
        @php
    // $navLinks = [
    //     'Menu' => '/menu',
    //     'Catering' => '/catering',

    //     'Our Story' => [
    //         'Our Heritage' => '/history',
    //         'The Roastery' => '/roasting',
    //         'Sustainability' => '/impact',
    //     ],
    // ];

    $navCtas = [
        [
            'text' => 'Get Directions',
            'url' => 'https://maps.google.com',
            'class' => 'bg-metro-dark text-white hover:bg-red-700 font-serif' // Primary Color
        ],
        [
            'text' => 'View Menu',
            'url' => '/menu',
            'class' => 'bg-metro-red text-white hover:bg-black font-serif' // Secondary Color
        ],
    ];
@endphp

<x-nav-main 
    {{-- :links="$navLinks"  --}}
    :ctas="$navCtas" 
    logo="https://lh3.googleusercontent.com/aida-public/AB6AXuCVdcILOZsUkPUSb0rfkVxML6tVOWvrYEGILcFJfE9OBzOqy4Zg4xBhCIAFRdtpRPjsPjOEM2W2eyZ0lTHvbSKhb_lurp3JphRbZ5y9E4RyfK7HottcXImI_ZB_S_PjqBUiJjBXq5jtSgoMgBbmQ86HlLVz7NoTXGVD86wCQ25a9W-jjJ8hp3aHqYVUVxmAnHV8rEc5UknHfWpypT6ro72otWPf5zxlpZb2CaUTvkkmWBT2ZoyaCavbvR6iHyGuEvpJlyVgh_mDD6s"
/>
    </header>

    {{-- This is where the specific page content (Home, Menu, etc.) goes --}}
    <main>
        {{ $slot }}
    </main>

    <x-footer-main />

    @stack('scripts')
</body>
</html>
