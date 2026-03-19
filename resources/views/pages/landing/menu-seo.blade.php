<x-layouts.app-main>
    {{-- 1. Push SEO tags specifically for Home --}}
    @push('meta')
        <meta name="description"
            content="Experience the heart of Bisrate Gebriel at Mera Coffee. A cozy coffee shop perfect for relaxing, working, and enjoying fine brews.">
        <meta property="og:title" content="Mera Coffee | Cozy Coffee Shop in Bisrate Gebriel">
        <meta property="og:image" content="https://images.pexels.com/photos/302902/pexels-photo-302902.jpeg">
        @php
            $faqSchema = collect($faqs ?? [])->map(function ($faq) {
                return [
                    '@type' => 'Question',
                    'name' => data_get($faq, 'question'),
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => trim(strip_tags((string) data_get($faq, 'answer', ''))),
                    ],
                ];
            })->filter(fn ($faq) => filled($faq['name']) && filled($faq['acceptedAnswer']['text']))->values();
        @endphp
        @if ($faqSchema->isNotEmpty())
            <script type="application/ld+json">{!! json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => $faqSchema,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
        @endif
        {{-- ... add other meta tags here ... --}}
    @endpush

    {{-- 2. Hero Section --}}
    <x-hero-main image="https://images.pexels.com/photos/302902/pexels-photo-302902.jpeg"
   backgroundVideo="{{ asset('asset/backgroundvideo3.mp4') }}"
        subtitle="Best Coffee Shop around Bisrate Gebriel"
        title="Mera Coffee | Quite Cozy Coffee Shop in the heart of Bisrate Gebriel" primary-button-text="Get Directions"
        primary-button-url="https://maps.google.com" secondary-button-text="View Menu" secondary-button-url="/menu" />

    {{-- 3. Top Picks --}}
    <section class="py-6 overflow-hidden">
        <div class="container mx-auto px-4 max-w-7xl">
            <x-featured-menu title="Popular orders" :items="$featuredItems ?? []" />
        </div>
    </section>

    {{-- 4. Gallery --}}
    @php
        $slug = 'best-foods-around-bole';
        $title = null;
    @endphp

    <x-gallery :title="$title" :slug="$slug" />

    {{-- 5. Rest of your sections --}}
    {{-- <x-section :reversed="true" subtitle="piza" title="America's Neighborhood Pizzeria"
        description="At Metro Pizza, we honor the great traditions of America's landmark Pizzerias. Our dough is made fresh each day from the finest wheat."
        image="https://lh3.googleusercontent.com/aida-public/AB6AXuARPAXMGLgaxHChxitlh1R0BdSv61FnICS1IuNE6GALPwBWZ_b663ifzsygE-nNGOG2rhmWDzwbqZv0eQGbSrtvfx-CI_IOZrAOFlJRpqMULv1gg8S1cZ3yco5ekStdsFh6Oru6qwFtRqG4ACAjr_YPnxMtN5f6TUitBdj3ryBlyi1Ceh722ngXbE0b0kQnxX9XRKAJcahy9K6nl_Wyv-V3FYW7VqVTWX3vSNdYvzhy4vV9f2a4aQiC1K5cC1We712q4w7nmvZD2ok"
        buttonText="Request Catering" :buttonUrl="'/menu2.html'" /> --}}

    <x-testimonials />

    <x-faq :items="$faqs ?? []" />
    <x-locations />

    {{-- <x-section-main backgroundImage="https://images.pexels.com/photos/302899/pexels-photo-302899.jpeg">
        <x-banner 
        image="https://images.pexels.com/photos/302899/pexels-photo-302899.jpeg" 
        title="The Mera Coffee Story"
        description="What started as a small dream in Bisrate Gebriel is now a community hub. We believe in slow-roasting, sustainable sourcing, and providing a workspace that feels like your own living room."
        buttonText="Read Our Full Story" 
        buttonUrl="/about" />
    </x-section-main> --}}
    {{-- <x-banner-child
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
    </x-banner-main> --}}


</x-layouts.app-main>
