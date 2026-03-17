<x-layouts.app-main>
    {{-- 1. Push SEO tags specifically for Home --}}
    @push('meta')
        <meta name="description"
            content="Experience the heart of Bisrate Gebriel at Mera Coffee. A cozy coffee shop perfect for relaxing, working, and enjoying fine brews.">
        <meta property="og:title" content="Mera Coffee | Cozy Coffee Shop in Bisrate Gebriel">
        <meta property="og:image" content="https://images.pexels.com/photos/302902/pexels-photo-302902.jpeg">
        {{-- ... add other meta tags here ... --}}
    @endpush

    {{-- 2. Hero Section --}}
    <x-hero-main image="https://images.pexels.com/photos/302902/pexels-photo-302902.jpeg"
        subtitle="Best Coffee Shop around Bisrate Gebriel"
        title="Mera Coffee | Quite Cozy Coffee Shop in the heart of Bisrate Gebriel" primary-button-text="Get Directions"
        primary-button-url="https://maps.google.com" secondary-button-text="View Menu" secondary-button-url="/menu" />

    {{-- 3. Top Picks --}}
    <section class="py-6 overflow-hidden">
        <div class="container mx-auto px-4 max-w-7xl">

            @php
                $pizzas = [
                    [
                        'title' => 'Cheese Pizza (14")',
                        'price' => '$14.99',
                        'description' => 'A delicious classic with gooey mozzarella.',
                        'images' => [
                            'https://lh3.googleusercontent.com/aida-public/AB6AXuCFLCo_kyxeWIe3N_tOQIXRNnwPe7nWUb-3XzjWdg-FCI56NDbDAfeLlbUfOv4-NHvbcfL8cL6ozIjJBqa6C3c4O6x_EHzPDnkQEHBRG3uVQBayXK3vb9_Gm8aHIg3eiiics9DznmtCjA-ee56BjW4AGKkfWbmey8VpVCKdkUGHTcjwCGCxrHCf4Jlrtn1tDIEv83tKOD1KOYKqDfNEvO9p1gY7jor2ssThyoeTrn1YALfckz9_0HYBcUi4S0xZf6mQMT8Wg4Q9vyY',
                            '/img/pizza1-alt.jpg',
                        ],
                    ],
                    // ... more items
                ];
            @endphp

            <x-featured-menu title="Popular orders" :items="$pizzas" />
        </div>
    </section>

    {{-- 4. Your Gallery with Floating Tooltips --}}
    @php
        $galleryData = [
            [
                'src' =>
                    'https://lh3.googleusercontent.com/aida-public/AB6AXuARPAXMGLgaxHChxitlh1R0BdSv61FnICS1IuNE6GALPwBWZ_b663ifzsygE-nNGOG2rhmWDzwbqZv0eQGbSrtvfx-CI_IOZrAOFlJRpqMULv1gg8S1cZ3yco5ekStdsFh6Oru6qwFtRqG4ACAjr_YPnxMtN5f6TUitBdj3ryBlyi1Ceh722ngXbE0b0kQnxX9XRKAJcahy9K6nl_Wyv-V3FYW7VqVTWX3vSNdYvzhy4vV9f2a4aQiC1K5cC1We712q4w7nmvZD2ok',
                'alt' => 'Our Signature Latte',
            ],
            [
                'src' =>
                    'https://lh3.googleusercontent.com/aida-public/AB6AXuARPAXMGLgaxHChxitlh1R0BdSv61FnICS1IuNE6GALPwBWZ_b663ifzsygE-nNGOG2rhmWDzwbqZv0eQGbSrtvfx-CI_IOZrAOFlJRpqMULv1gg8S1cZ3yco5ekStdsFh6Oru6qwFtRqG4ACAjr_YPnxMtN5f6TUitBdj3ryBlyi1Ceh722ngXbE0b0kQnxX9XRKAJcahy9K6nl_Wyv-V3FYW7VqVTWX3vSNdYvzhy4vV9f2a4aQiC1K5cC1We712q4w7nmvZD2ok',
                'alt' => 'Freshly Baked Pastries',
            ],
            [
                'src' =>
                    'https://lh3.googleusercontent.com/aida-public/AB6AXuARPAXMGLgaxHChxitlh1R0BdSv61FnICS1IuNE6GALPwBWZ_b663ifzsygE-nNGOG2rhmWDzwbqZv0eQGbSrtvfx-CI_IOZrAOFlJRpqMULv1gg8S1cZ3yco5ekStdsFh6Oru6qwFtRqG4ACAjr_YPnxMtN5f6TUitBdj3ryBlyi1Ceh722ngXbE0b0kQnxX9XRKAJcahy9K6nl_Wyv-V3FYW7VqVTWX3vSNdYvzhy4vV9f2a4aQiC1K5cC1We712q4w7nmvZD2ok',
                'alt' => 'Cozy Corner for Working',
            ],
            [
                'src' =>
                    'https://lh3.googleusercontent.com/aida-public/AB6AXuARPAXMGLgaxHChxitlh1R0BdSv61FnICS1IuNE6GALPwBWZ_b663ifzsygE-nNGOG2rhmWDzwbqZv0eQGbSrtvfx-CI_IOZrAOFlJRpqMULv1gg8S1cZ3yco5ekStdsFh6Oru6qwFtRqG4ACAjr_YPnxMtN5f6TUitBdj3ryBlyi1Ceh722ngXbE0b0kQnxX9XRKAJcahy9K6nl_Wyv-V3FYW7VqVTWX3vSNdYvzhy4vV9f2a4aQiC1K5cC1We712q4w7nmvZD2ok',
                'alt' => 'Hand-poured V60',
            ],
            [
                'src' =>
                    'https://lh3.googleusercontent.com/aida-public/AB6AXuARPAXMGLgaxHChxitlh1R0BdSv61FnICS1IuNE6GALPwBWZ_b663ifzsygE-nNGOG2rhmWDzwbqZv0eQGbSrtvfx-CI_IOZrAOFlJRpqMULv1gg8S1cZ3yco5ekStdsFh6Oru6qwFtRqG4ACAjr_YPnxMtN5f6TUitBdj3ryBlyi1Ceh722ngXbE0b0kQnxX9XRKAJcahy9K6nl_Wyv-V3FYW7VqVTWX3vSNdYvzhy4vV9f2a4aQiC1K5cC1We712q4w7nmvZD2ok',
                'alt' => 'Evening Atmosphere',
            ],
            [
                'src' =>
                    'https://lh3.googleusercontent.com/aida-public/AB6AXuARPAXMGLgaxHChxitlh1R0BdSv61FnICS1IuNE6GALPwBWZ_b663ifzsygE-nNGOG2rhmWDzwbqZv0eQGbSrtvfx-CI_IOZrAOFlJRpqMULv1gg8S1cZ3yco5ekStdsFh6Oru6qwFtRqG4ACAjr_YPnxMtN5f6TUitBdj3ryBlyi1Ceh722ngXbE0b0kQnxX9XRKAJcahy9K6nl_Wyv-V3FYW7VqVTWX3vSNdYvzhy4vV9f2a4aQiC1K5cC1We712q4w7nmvZD2ok',
                'alt' => 'Premium Ethiopian Beans',
            ],
        ];
    @endphp

    <x-gallery title="Our Best Moments" :items="$galleryData" />

    {{-- 5. Rest of your sections --}}
    <x-section :reversed="true" subtitle="piza" title="America's Neighborhood Pizzeria"
        description="At Metro Pizza, we honor the great traditions of America's landmark Pizzerias. Our dough is made fresh each day from the finest wheat."
        image="https://lh3.googleusercontent.com/aida-public/AB6AXuARPAXMGLgaxHChxitlh1R0BdSv61FnICS1IuNE6GALPwBWZ_b663ifzsygE-nNGOG2rhmWDzwbqZv0eQGbSrtvfx-CI_IOZrAOFlJRpqMULv1gg8S1cZ3yco5ekStdsFh6Oru6qwFtRqG4ACAjr_YPnxMtN5f6TUitBdj3ryBlyi1Ceh722ngXbE0b0kQnxX9XRKAJcahy9K6nl_Wyv-V3FYW7VqVTWX3vSNdYvzhy4vV9f2a4aQiC1K5cC1We712q4w7nmvZD2ok"
        buttonText="Request Catering" :buttonUrl="'/menu2.html'" />


    <x-testimonials :testimonials="[
        [
            'name' => 'Jerry R.',
            'stars' => 5,
            'avatar' => 'https://i.pravatar.cc/150?u=jerry',
            'content' =>
                'Wow! This small Pizza establishment is really amazing! I would highly recommend the pepperoni and the garlic knots, they are absolute game changers for any pizza lover in the area.',
        ],
        [
            'name' => 'Jerry R.',
            'stars' => 5,
            'avatar' => 'https://i.pravatar.cc/150?u=jerry',
            'content' =>
                'Wow! This small Pizza establishment is really amazing! I would highly recommend the pepperoni and the garlic knots, they are absolute game changers for any pizza lover in the area.',
        ],
        [
            'name' => 'Jerry R.',
            'stars' => 5,
            'avatar' => 'https://i.pravatar.cc/150?u=jerry',
            'content' =>
                'Wow! This small Pizza establishment is really amazing! I would highly recommend the pepperoni and the garlic knots, they are absolute game changers for any pizza lover in the area.',
        ],
    
        // ... add more reviews here
    ]" />



    @php
        $meraFaqs = [
            [
                'question' => 'What are you known for?',
                'answer' =>
                    'We are known for our <strong>authentic Ethiopian beans</strong>, cozy workspace, and the best lattes in Bisrate Gebriel.',
            ],
            [
                'question' => 'Do you have Wi-Fi for working?',
                'answer' =>
                    'Yes! We offer high-speed fiber internet for our customers, making it the perfect spot for remote work.',
            ],
            [
                'question' => 'Do you offer delivery?',
                'answer' => 'You can find us on local delivery apps, or stop by for a quick takeout.',
            ],
        ];
    @endphp

    <x-faq :items="$meraFaqs" />
    <x-locations />

    {{-- <x-section-main backgroundImage="https://images.pexels.com/photos/302899/pexels-photo-302899.jpeg">
        <x-banner 
        image="https://images.pexels.com/photos/302899/pexels-photo-302899.jpeg" 
        title="The Mera Coffee Story"
        description="What started as a small dream in Bisrate Gebriel is now a community hub. We believe in slow-roasting, sustainable sourcing, and providing a workspace that feels like your own living room."
        buttonText="Read Our Full Story" 
        buttonUrl="/about" />
    </x-section-main> --}}
    <x-banner-child
        image="https://mattengas.com/pluto-images/funnel/images/05923b92-49f8-48a8-a6d1-7e2e8442625a?w=560&h=560&fit=cover"
        title="Mera's Story" signature="The Mera Coffee Team"
        content="We’re Matt and Enga—the pizza-loving duo behind Mattenga’s!  What started as a simple dream is now multiple locations across SAN ANTONIO. /n No shortcuts. No gimmicks. Just real ingredients and unforgettable flavors.Thanks for sharing your pizza cravings with us! Let’s eat! 🍕"
        buttonText="Visit Us Today" buttonUrl="/locations" />
    <x-banner-main backgroundImage="https://images.pexels.com/photos/302899/pexels-photo-302899.jpeg"
        subtitle="Our Craft">
        <x-banner-child
            image="https://mattengas.com/pluto-images/funnel/images/05923b92-49f8-48a8-a6d1-7e2e8442625a?w=560&h=560&fit=cover"
            title="Mera's Story" signature="The Mera Coffee Team"
            content="We’re Matt and Enga—the pizza-loving duo behind Mattenga’s!  What started as a simple dream is now multiple locations across SAN ANTONIO. /n No shortcuts. No gimmicks. Just real ingredients and unforgettable flavors.Thanks for sharing your pizza cravings with us! Let’s eat! 🍕"
            buttonText="Visit Us Today" buttonUrl="/locations" />
    </x-banner-main>


</x-layouts.app-main>
