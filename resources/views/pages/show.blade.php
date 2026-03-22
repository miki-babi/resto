<x-layouts.app-main>
    @php
        $heroImage = $page?->getFirstMediaUrl('hero_image') ?: 'https://images.pexels.com/photos/302902/pexels-photo-302902.jpeg';
        $heroVideo = $page?->getFirstMediaUrl('hero_video');
        $heroTitle = $page?->hero_headline ?: $page?->title;
        $heroSubtitle = $page?->hero_subtitle;
        $primaryText = $page?->primary_cta_text;
        $primaryUrl = $page?->primary_cta_url ?: '#';
        $secondaryText = $page?->secondary_cta_text;
        $secondaryUrl = $page?->secondary_cta_url ?: '#';
    @endphp

    <x-hero-main
        :image="$heroImage"
        :backgroundVideo="$heroVideo"
        :subtitle="$heroSubtitle"
        :title="$heroTitle"
        :primary-button-text="$primaryText ?: 'Learn More'"
        :primary-button-url="$primaryUrl"
        :secondary-button-text="$secondaryText"
        :secondary-button-url="$secondaryUrl"
    />

    @if($page?->hero_subtitle || $page?->hero_headline)
        <x-section
            :title="$page?->hero_headline ?: $page?->title"
            :description="$page?->hero_subtitle ?: ''"
            :image="$heroImage"
        />
    @endif

    @if(!empty($menuItems))
        <section class="py-12">
            <div class="container mx-auto max-w-7xl ">
                <x-menu :title="$page->menuCategory?->name ?? 'Menu'" :items="$menuItems" />
            </div>
        </section>
    @endif

    @if($page?->gallery)
        <x-gallery :title="$page->gallery?->display_title" :slug="$page->gallery?->slug" />
    @endif

    <x-testimonials />
    <x-locations />
</x-layouts.app-main>
