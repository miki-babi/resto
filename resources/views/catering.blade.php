<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ __('Wee Catering') }} | {{ __('Curated Catering Experiences') }}</title>
    <meta name="description"
        content="A premium digital package menu for curated cafe and catering experiences, from brunch events to elegant celebrations." />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Great+Vibes&family=Montserrat:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="m-0 font-sans bg-[radial-gradient(circle_at_top_left,rgba(199,143,105,0.18),transparent_22%),radial-gradient(circle_at_90%_12%,rgba(215,124,48,0.12),transparent_24%),linear-gradient(180deg,var(--color-bg)_0%,var(--color-bg-secondary)_100%)] dark:bg-[linear-gradient(180deg,var(--color-bg-dark)_0%,var(--color-bg-secondary-dark)_100%)] text-text dark:text-text-dark min-h-screen transition-colors duration-260 overflow-x-hidden">
    <div class="progress-bar fixed top-0 left-0 z-[1200] w-0 h-1 bg-gradient-to-r from-gold to-accent shadow-[0_0_18px_rgba(199,143,105,0.55)]"
        id="progressBar"></div>

    <div class="site-shell relative">
        <div class="nav-wrap sticky top-0 z-[1100] py-4">
            <div class="container mx-auto px-4 max-w-[--max-width]">
                <nav
                    class="navbar flex items-center justify-between gap-4 py-[0.95rem] px-[1.2rem] bg-surface/72 dark:bg-surface-dark/72 backdrop-blur-[14px] border border-line dark:border-white/10 rounded-full shadow-soft transition-all duration-260">
                    <a class="brand flex items-center gap-3 min-w-0" href="#top">
                        <span
                            class="brand-mark w-11 h-11 grid place-items-center rounded-full bg-gradient-to-br from-accent to-gold text-white font-serif text-xl shadow-[0_10px_24px_rgba(215,124,48,0.3)]">W</span>
                        <span class="brand-copy hidden sm:grid min-w-0">
                            <strong
                                class="text-[0.98rem] font-bold tracking-wider uppercase text-text dark:text-text-dark">{{ __('Wee Catering') }}</strong>
                            <span
                                class="text-[0.82rem] text-text-soft dark:text-text-soft-dark truncate">{{ __('Cafe and catering studio') }}</span>
                        </span>
                    </a>

                    <div
                        class="lang-switcher flex items-center gap-2 px-3 py-1.5 bg-bg-secondary dark:bg-bg-secondary-dark rounded-full border border-line dark:border-white/10 text-[0.7rem] font-bold uppercase tracking-widest">
                        <a href="{{ route('catering2.localized', ['lang' => 'en']) }}"
                            class="{{ app()->getLocale() == 'en' ? 'text-accent' : 'text-text-soft' }} hover:text-accent transition-colors">EN</a>
                        <span class="w-px h-3 bg-line dark:bg-white/10"></span>
                        <a href="{{ route('catering2.localized', ['lang' => 'am']) }}"
                            class="{{ app()->getLocale() == 'am' ? 'text-accent' : 'text-text-soft' }} hover:text-accent transition-colors">AM</a>
                    </div>
                    <a class="button px-6 py-2.5 rounded-full font-bold bg-gradient-to-r from-accent to-gold text-white shadow-soft hover:-translate-y-0.5 transition-all text-sm"
                        href="#contact">{{ __('Book Now') }}</a>
            </div>
            </nav>
        </div>
    </div>

    <main id="top">
        <section class="hero px-4 py-4 md:py-8">
            <div class="container mx-auto max-w-[--max-width]">
                <div
                    class="hero-panel relative min-h-[min(88vh,860px)] rounded-[clamp(28px,4vw,44px)] overflow-hidden shadow-shadow border border-white/20 bg-[url('https://cdn.hahu.jobs/public/sheger-gebeta/2dabf9a4-caef-4ccc-9de8-bb460cca6057.webp')] bg-center bg-cover bg-no-repeat reveal">
                    <div class="absolute inset-0 bg-[rgba(18,14,12,0.42)]"></div>
                    <div
                        class="absolute inset-x-0 bottom-0 h-[45%] bg-gradient-to-t from-[rgba(18,14,12,0.56)] via-[rgba(22,16,13,0.18)] to-transparent pointer-events-none">
                    </div>

                    <div
                        class="hero-grid relative z-10 grid grid-cols-1 lg:grid-cols-[1.2fr_0.8fr] gap-8 p-6 md:p-12 items-center lg:items-end h-full min-h-[inherit]">
                        <div class="hero-copy grid gap-5 self-center">
                            <span
                                class="eyebrow block text-gold text-[0.88rem] font-semibold tracking-[0.24em] uppercase">{{ __('Boutique Catering Atelier') }}</span>
                            <h1 class="font-serif text-[clamp(2.5rem,6vw,4.8rem)] leading-[0.95] font-bold text-white">
                                {{ __('Curated Catering') }}<br>
                                <span
                                    class="script block font-script text-gold font-normal lg:inline lg:ml-2 tracking-normal lowercase text-[1.2em]">{{ __('Experiences') }}</span>
                            </h1>
                            <p class="text-[rgba(251,246,239,0.85)] text-lg max-w-[58ch] leading-relaxed">
                                {{ __('Elegant packages crafted for every occasion, from intimate cafe mornings to refined weddings, birthdays, and polished corporate gatherings.') }}
                            </p>
                            <div class="hero-actions flex items-center gap-4 flex-wrap mt-2">
                                <a class="button px-8 py-4 rounded-full font-bold bg-gradient-to-r from-accent to-gold text-white shadow-soft hover:-translate-y-1 transition-all"
                                    href="#packages">{{ __('View Packages') }}</a>
                                <a class="button px-8 py-4 rounded-full font-bold bg-white/10 backdrop-blur-md border border-white/20 text-white shadow-soft hover:-translate-y-1 transition-all"
                                    href="#gallery">{{ __('See Gallery') }}</a>
                            </div>
                        </div>

                        <aside class="hero-aside hidden lg:grid gap-4 self-end justify-self-end w-full max-w-[360px]">
                            <div
                                class="glass-card p-6 bg-white/10 backdrop-blur-xl border border-white/20 rounded-[28px] text-white shadow-soft">
                                <p class="text-[rgba(251,246,239,0.8)] text-sm leading-relaxed italic">"Signature
                                    Event Mood: Soft florals, plated desserts, specialty drinks, and a table setup
                                    that feels editorial."</p>
                            </div>
                            <div class="mini-stack grid grid-cols-2 gap-4">
                                <div
                                    class="stat-card p-5 bg-white/10 backdrop-blur-xl border border-white/20 rounded-[28px] text-white shadow-soft">
                                    <strong class="block text-3xl font-serif mb-1 italic">150+</strong>
                                    <p class="text-[rgba(251,246,239,0.8)] text-xs">
                                        {{ __('Events styled with premium charm.') }}</p>
                                </div>
                                <div
                                    class="stat-card p-5 bg-white/10 backdrop-blur-xl border border-white/20 rounded-[28px] text-white shadow-soft">
                                    <strong class="block text-3xl font-serif mb-1 italic">24h</strong>
                                    <p class="text-[rgba(251,246,239,0.8)] text-xs">
                                        {{ __('Fast response for inquiries.') }}</p>
                                </div>
                            </div>
                        </aside>
                    </div>
                </div>
            </div>
        </section>

        <section class="section py-24 relative overflow-hidden" id="packages">
            <div class="container mx-auto px-4 max-w-[--max-width]">
                <div class="menu-topbar flex flex-col md:flex-row md:items-end justify-between gap-6 mb-11 reveal">
                    <div class="section-header max-w-[620px]">
                        <span
                            class="eyebrow block text-accent text-[0.88rem] font-semibold tracking-[0.24em] uppercase">
                            <span
                                class="script font-script text-gold font-normal tracking-normal lowercase text-[1.6em]">Signature</span>
                            {{ __('Menu Collection') }}
                        </span>
                        <p class="text-text-soft dark:text-text-soft-dark mt-2">
                            {{ __('Switch between per-person pricing and full package estimates to match your event planning style.') }}
                        </p>
                    </div>

                    <div class="pricing-toggle flex gap-2 p-1.5 bg-card dark:bg-card-dark border border-line dark:border-white/10 rounded-full shadow-soft"
                        role="group">
                        <button onclick="setPrice('person')" id="ppBtn"
                            class="px-6 py-2 rounded-full text-sm font-bold transition-all">{{ __('Per Person') }}</button>
                        <button onclick="setPrice('total')" id="totalBtn"
                            class="px-6 py-2 rounded-full text-sm font-bold transition-all">{{ __('Full Package') }}</button>
                    </div>
                </div>

                <div class="packages grid gap-8">
                    <!-- Package 1 -->
                    <article
                        class="package-card grid grid-cols-1 lg:grid-cols-[minmax(300px,0.95fr)_minmax(0,1.05fr)] gap-8 p-8 rounded-[40px] bg-gradient-to-b from-card to-surface-strong dark:from-card-dark dark:to-surface-strong-dark border border-line dark:border-white/10 shadow-soft hover:-translate-y-1 transition-all duration-300 reveal">
                        <div class="package-visual relative min-h-[340px] grid place-items-center">
                            <div class="image-stack relative w-full h-full">
                                <img class="image-main absolute inset-0 w-[80%] h-full object-cover rounded-[32px] shadow-shadow transition-transform duration-500"
                                    src="https://cdn.hahu.jobs/public/sheger-gebeta/2bb874c8-8484-4394-afb8-2be1b6fe40ed.webp"
                                    alt="Coffee Package Main" />
                                <img class="image-accent absolute bottom-8 right-0 w-[50%] h-[55%] object-cover rounded-[28px] border-8 border-surface-strong dark:border-surface-strong-dark shadow-shadow rotate-3 group-hover:rotate-6 transition-transform duration-500"
                                    src="https://cdn.hahu.jobs/public/sheger-gebeta/a95d863c-a50d-4e47-919c-67bb452f943d.webp"
                                    alt="Coffee Package Accent" />
                            </div>
                        </div>
                        <div class="package-content grid gap-5">
                            <div class="package-meta flex items-center gap-3">
                                <span
                                    class="badge px-4 py-1.5 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs font-bold uppercase tracking-wider">{{ __('Budget Friendly') }}</span>
                            </div>
                            <div class="grid gap-2">
                                <h3 class="font-serif text-3xl font-bold text-text dark:text-text-dark">
                                    {{ __('Basic Coffee Package') }}</h3>
                                <p class="text-text-soft dark:text-text-soft-dark leading-relaxed">
                                    {{ __('A clean, stylish morning setup for small meetings, pop-ups, and intimate birthday brunches.') }}
                                </p>
                            </div>
                            <div class="price-row flex items-baseline gap-2">
                                <span class="price-value font-serif text-5xl font-bold text-gold" data-pp="450"
                                    data-total="13500">{{ __('ETB') }} 450</span>
                                <span class="price-label text-text-soft dark:text-text-soft-dark text-sm">/
                                    {{ __('person') }}</span>
                            </div>
                            <ul class="package-list grid gap-3">
                                <li class="flex items-start gap-3 transition-colors hover:text-accent-hover group">
                                    <span class="w-2 h-2 mt-2 rounded-full bg-gold shadow-[0_0_8px_var(--gold)]"></span>
                                    <span>{{ __('Freshly brewed coffee and tea selection') }}</span>
                                </li>
                                <li class="flex items-start gap-3 transition-colors hover:text-accent-hover group">
                                    <span
                                        class="w-2 h-2 mt-2 rounded-full bg-gold shadow-[0_0_8px_var(--gold)]"></span>
                                    <span>{{ __('Two artisan pastries per guest') }}</span>
                                </li>
                                <li class="flex items-start gap-3 transition-colors hover:text-accent-hover group">
                                    <span
                                        class="w-2 h-2 mt-2 rounded-full bg-gold shadow-[0_0_8px_var(--gold)]"></span>
                                    <span>{{ __('Minimal tabletop styling with neutral serving ware') }}</span>
                                </li>
                            </ul>
                            <div class="mt-4">
                                <a href="#contact"
                                    class="inline-block px-8 py-3 rounded-full font-bold bg-surface dark:bg-surface-dark border border-line dark:border-white/10 hover:border-gold transition-colors text-sm">{{ __('Inquire Now') }}</a>
                            </div>
                        </div>
                    </article>

                    <!-- Package 2 -->
                    <article
                        class="package-card grid grid-cols-1 lg:grid-cols-[minmax(0,1.05fr)_minmax(300px,0.95fr)] gap-8 p-8 rounded-[40px] bg-gradient-to-b from-card to-surface-strong dark:from-card-dark dark:to-surface-strong-dark border border-line dark:border-white/10 shadow-soft hover:-translate-y-1 transition-all duration-300 reveal">
                        <div class="package-visual relative min-h-[340px] grid place-items-center lg:order-2">
                            <div class="image-stack relative w-full h-full">
                                <img class="image-main absolute inset-0 w-[80%] h-full ml-auto object-cover rounded-[32px] shadow-shadow transition-transform duration-500"
                                    src="https://cdn.hahu.jobs/public/sheger-gebeta/c1de923d-6c7d-438c-bb88-540bc4d03802.jpeg"
                                    alt="Social Package Main" />
                                <img class="image-accent absolute bottom-8 left-0 w-[50%] h-[55%] object-cover rounded-[28px] border-8 border-surface-strong dark:border-surface-strong-dark shadow-shadow -rotate-3 group-hover:-rotate-6 transition-transform duration-500"
                                    src="https://images.unsplash.com/photo-1481833761820-0509d3217039?auto=format&fit=crop&w=900&q=80"
                                    alt="Social Package Accent" />
                            </div>
                        </div>
                        <div class="package-content grid gap-5 lg:order-1">
                            <div class="package-meta flex items-center gap-3">
                                <span
                                    class="badge px-4 py-1.5 rounded-full bg-accent/10 text-accent text-xs font-bold uppercase tracking-wider">{{ __('Best Seller') }}</span>
                            </div>
                            <div class="grid gap-2">
                                <h3 class="font-serif text-3xl font-bold text-text dark:text-text-dark">
                                    {{ __('Standard Social Package') }}</h3>
                                <p class="text-text-soft dark:text-text-soft-dark leading-relaxed">
                                    {{ __('Balanced, elevated, and easy to love. Perfect for office catering, bridal showers, and receptions.') }}
                                </p>
                            </div>
                            <div class="price-row flex items-baseline gap-2">
                                <span class="price-value font-serif text-5xl font-bold text-gold" data-pp="750"
                                    data-total="22500">{{ __('ETB') }} 750</span>
                                <span class="price-label text-text-soft dark:text-text-soft-dark text-sm">/
                                    {{ __('person') }}</span>
                            </div>
                            <ul class="package-list grid gap-3">
                                <li class="flex items-start gap-3 transition-colors hover:text-accent-hover group">
                                    <span
                                        class="w-2 h-2 mt-2 rounded-full bg-gold shadow-[0_0_8px_var(--gold)]"></span>
                                    <span>{{ __('Coffee or juice station with premium add-ons') }}</span>
                                </li>
                                <li class="flex items-start gap-3 transition-colors hover:text-accent-hover group">
                                    <span
                                        class="w-2 h-2 mt-2 rounded-full bg-gold shadow-[0_0_8px_var(--gold)]"></span>
                                    <span>{{ __('Signature sandwiches and savory bites') }}</span>
                                </li>
                                <li class="flex items-start gap-3 transition-colors hover:text-accent-hover group">
                                    <span
                                        class="w-2 h-2 mt-2 rounded-full bg-gold shadow-[0_0_8px_var(--gold)]"></span>
                                    <span>{{ __('Styled buffet presentation for 20-30 guests') }}</span>
                                </li>
                            </ul>
                            <div class="mt-4">
                                <a href="#contact"
                                    class="inline-block px-8 py-3 rounded-full font-bold bg-surface dark:bg-surface-dark border border-line dark:border-white/10 hover:border-gold transition-colors text-sm">{{ __('Inquire Now') }}</a>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section class="section py-24 relative overflow-hidden" id="about">
            <div class="container mx-auto px-4 max-w-[--max-width]">
                <div
                    class="about-grid grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_minmax(320px,0.9fr)] gap-[clamp(1.8rem,4vw,3rem)] items-center">
                    <div
                        class="about-card p-[clamp(1.5rem,4vw,2.3rem)] rounded-[32px] bg-gradient-to-b from-card to-surface-strong dark:from-card-dark dark:to-surface-strong-dark border border-line dark:border-white/10 shadow-soft grid gap-[1.2rem] reveal">
                        <span
                            class="eyebrow block text-accent text-[0.88rem] font-semibold tracking-[0.24em] uppercase">{{ __('About wee catering') }}</span>
                        <h2
                            class="font-serif text-[clamp(1.5rem,4vw,2.8rem)] leading-[1.1] font-bold text-text dark:text-text-dark">
                            {{ __('We blend cafe warmth with event-level polish.') }}
                        </h2>
                        <p class="text-text-soft dark:text-text-soft-dark leading-[1.75]">
                            {{ __('Wee Catering creates elevated food moments that feel soft, modern, and intentionally styled. Our menus are designed for clients who want more than catering. They want atmosphere, detail, and a table guests cannot stop photographing.') }}
                        </p>
                        <p class="text-text-soft dark:text-text-soft-dark leading-[1.75]">
                            {{ __('From intimate birthday brunches to executive breakfast service and wedding mornings, every package is built with flavor, presentation, and flow in mind.') }}
                        </p>
                    </div>

                    <div class="story-collage relative min-h-[500px] reveal" aria-label="Curated event food collage">
                        <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=1000&q=80"
                            alt="Cafe table"
                            class="absolute w-[64%] h-[58%] top-0 left-0 object-cover rounded-[28px] shadow-shadow -rotate-5 hover:-translate-y-1 transition-all duration-300" />
                        <img src="https://images.unsplash.com/photo-1467003909585-2f8a72700288?auto=format&fit=crop&w=900&q=80"
                            alt="Pastries"
                            class="absolute w-[52%] h-[48%] bottom-[8%] right-0 object-cover rounded-[28px] rotate-4 z-[1] border-8 border-surface-strong dark:border-surface-strong-dark shadow-shadow hover:-translate-y-1 transition-all duration-300" />
                        <img src="https://images.unsplash.com/photo-1515003197210-e0cd71810b5f?auto=format&fit=crop&w=1000&q=80"
                            alt="Catering table"
                            class="absolute w-[32%] h-[32%] top-[45%] left-[45%] -translate-x-1/2 -translate-y-1/2 object-cover rounded-[28px] rotate-2 z-[2] border-6 border-surface-strong dark:border-surface-strong-dark shadow-shadow hover:-translate-y-1 transition-all duration-300" />
                    </div>
                </div>
            </div>
        </section>

        <section class="section py-24 relative overflow-hidden" id="gallery">
            <div class="container mx-auto px-4 max-w-[--max-width]">
                <div class="section-header grid gap-4 max-w-[720px] mb-11 reveal">
                    <span class="eyebrow block text-accent text-[0.88rem] font-semibold tracking-[0.24em] uppercase">
                        <span
                            class="script font-script text-gold font-normal tracking-normal lowercase text-[1.6em]">{{ __('Special') }}</span>
                        {{ __('Instagram Moments') }}
                    </span>
                    <p class="text-text-soft dark:text-text-soft-dark leading-[1.75]">
                        {{ __('Image-forward presentation matters. This gallery gives your brand an editorial, social-first feel while keeping the menu front and center.') }}
                    </p>
                </div>

                <div class="gallery grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <figure class="gallery-card group reveal">
                        <img src="https://images.unsplash.com/photo-1521017432531-fbd92d768814?auto=format&fit=crop&w=900&q=80"
                            alt="Latte"
                            class="w-full aspect-square object-cover rounded-[24px] shadow-soft group-hover:-translate-y-1 transition-all duration-300" />
                        <figcaption class="mt-3 text-[0.9rem] text-text-soft dark:text-text-soft-dark text-center">
                            {{ __('Morning tables with clean cafe elegance.') }}</figcaption>
                    </figure>
                    <figure class="gallery-card group reveal">
                        <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=900&q=80"
                            alt="Brunch"
                            class="w-full aspect-square object-cover rounded-[24px] shadow-soft group-hover:-translate-y-1 transition-all duration-300" />
                        <figcaption class="mt-3 text-[0.9rem] text-text-soft dark:text-text-soft-dark text-center">
                            {{ __('Brunch presentations that feel modern.') }}</figcaption>
                    </figure>
                    <figure class="gallery-card group reveal">
                        <img src="https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=900&q=80"
                            alt="Table"
                            class="w-full aspect-square object-cover rounded-[24px] shadow-soft group-hover:-translate-y-1 transition-all duration-300" />
                        <figcaption class="mt-3 text-[0.9rem] text-text-soft dark:text-text-soft-dark text-center">
                            {{ __('Fresh, colorful spreads for hosting.') }}</figcaption>
                    </figure>
                </div>
            </div>
        </section>

        <section class="section py-24 relative overflow-hidden" id="contact">
            <div class="container mx-auto px-4 max-w-[--max-width]">
                <div
                    class="contact-wrap grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_minmax(320px,0.8fr)] gap-8 items-start reveal">
                    <div
                        class="contact-card p-10 rounded-[32px] bg-gradient-to-b from-card to-surface-strong dark:from-card-dark dark:to-surface-strong-dark border border-line dark:border-white/10 shadow-soft">
                        <span
                            class="eyebrow block text-accent text-[0.88rem] font-semibold tracking-[0.24em] uppercase">{{ __('Book Your Package') }}</span>
                        <h2
                            class="font-serif text-4xl lg:text-5xl font-bold text-text dark:text-text-dark mt-4 leading-[1.1]">
                            {{ __('Let us style the table your guests will talk about.') }}</h2>
                        <p class="text-text-soft dark:text-text-soft-dark leading-relaxed mt-4">
                            {{ __('Share your event date, guest count, and preferred package. We will shape a quote that feels seamless and elevated.') }}
                        </p>

                        <div class="flex items-center gap-4 flex-wrap mt-8">
                            <a class="px-6 py-3 rounded-full font-bold bg-[#59d98d] text-white shadow-soft hover:-translate-y-1 transition-all"
                                href="#">WhatsApp</a>
                            <a class="px-6 py-3 rounded-full font-bold bg-[#24a1de] text-white shadow-soft hover:-translate-y-1 transition-all"
                                href="#">Telegram</a>
                            <a class="px-6 py-3 rounded-full font-bold bg-black text-white shadow-soft hover:-translate-y-1 transition-all"
                                href="tel:09092686">Call Us</a>
                        </div>
                    </div>
                    <div
                        class="form-card p-10 rounded-[32px] bg-gradient-to-b from-card to-surface-strong dark:from-card-dark dark:to-surface-strong-dark border border-line dark:border-white/10 shadow-soft">
                        <form action="#" method="post" class="grid gap-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div class="grid gap-2">
                                    <label
                                        class="text-xs font-bold uppercase tracking-wider text-text-soft dark:text-text-soft-dark">{{ __('Your Name') }}</label>
                                    <input type="text" placeholder="Amina Yusuf"
                                        class="w-full px-5 py-3 rounded-xl bg-surface dark:bg-surface-dark border border-line dark:border-white/10 focus:outline-none focus:border-gold transition-all" />
                                </div>
                                <div class="grid gap-2">
                                    <label
                                        class="text-xs font-bold uppercase tracking-wider text-text-soft dark:text-text-soft-dark">{{ __('Event Date') }}</label>
                                    <input type="date"
                                        class="w-full px-5 py-3 rounded-xl bg-surface dark:bg-surface-dark border border-line dark:border-white/10 focus:outline-none focus:border-gold transition-all" />
                                </div>
                            </div>
                            <div class="grid gap-2">
                                <label
                                    class="text-xs font-bold uppercase tracking-wider text-text-soft dark:text-text-soft-dark">{{ __('Package Type') }}</label>
                                <select
                                    class="w-full px-5 py-3 rounded-xl bg-surface dark:bg-surface-dark border border-line dark:border-white/10 focus:outline-none focus:border-gold transition-all">
                                    <option>{{ __('Basic Coffee Package') }}</option>
                                    <option>{{ __('Standard Social Package') }}</option>
                                    <option>{{ __('Premium Brunch Package') }}</option>
                                </select>
                            </div>
                            <div class="grid gap-2">
                                <label
                                    class="text-xs font-bold uppercase tracking-wider text-text-soft dark:text-text-soft-dark">{{ __('Details') }}</label>
                                <textarea placeholder="{{ __('The occasion and vibe...') }}"
                                    class="w-full px-5 py-3 rounded-xl bg-surface dark:bg-surface-dark border border-line dark:border-white/10 focus:outline-none focus:border-gold transition-all min-h-[100px]"></textarea>
                            </div>
                            <button type="submit"
                                class="w-full py-4 rounded-full font-bold bg-gradient-to-r from-accent to-gold text-white shadow-soft hover:shadow-lg transition-all">{{ __('Send Inquiry') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>


    <x-footer-main />
    <a class="fixed bottom-6 right-6 z-[1000] flex items-center gap-2 px-6 py-3.5 rounded-full bg-black text-white shadow-shadow hover:scale-105 transition-all group"
        href="tel:09092686">
        <span class="text-xl font-bold transition-transform group-hover:rotate-12">+</span>
        <span class="font-bold text-sm tracking-wide">Call Now</span>
    </a>
    </div>

    <script>
        // Theme Logic
        const setTheme = (theme) => {
            document.documentElement.classList.toggle('dark', theme === 'dark');
            localStorage.setItem('theme', theme);
            const lightBtn = document.getElementById('lightBtn');
            const darkBtn = document.getElementById('darkBtn');
            if (!lightBtn || !darkBtn) return;

            if (theme === 'dark') {
                darkBtn.classList.add('bg-white', 'text-black', 'shadow-sm');
                lightBtn.classList.remove('bg-white', 'text-black', 'shadow-sm');
                lightBtn.classList.add('text-text-soft');
            } else {
                lightBtn.classList.add('bg-white', 'text-black', 'shadow-sm');
                darkBtn.classList.remove('bg-white', 'text-black', 'shadow-sm');
                darkBtn.classList.add('text-text-soft');
            }
        };

        // Initialize Theme
        const savedTheme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ?
            'dark' : 'light');
        setTheme(savedTheme);

        // Pricing Logic
        const setPrice = (mode) => {
            const prices = document.querySelectorAll('.price-value');
            const labels = document.querySelectorAll('.price-label');
            const totalBtn = document.getElementById('totalBtn');
            const ppBtn = document.getElementById('ppBtn');

            if (!totalBtn || !ppBtn) return;

            if (mode === 'total') {
                totalBtn.classList.add('bg-gold', 'text-white', 'shadow-md');
                ppBtn.classList.remove('bg-gold', 'text-white', 'shadow-md');
            } else {
                ppBtn.classList.add('bg-gold', 'text-white', 'shadow-md');
                totalBtn.classList.remove('bg-gold', 'text-white', 'shadow-md');
            }

            prices.forEach((el, i) => {
                const val = mode === 'total' ? el.dataset.total : el.dataset.pp;
                el.textContent = `{{ __('ETB') }} ${Number(val).toLocaleString()}`;
                labels[i].textContent = mode === 'total' ? '/ {{ __('Package') }}' : '/ {{ __('person') }}';
            });
        };
        setPrice('person');

        // Scroll Progress
        const progressBar = document.getElementById('progressBar');
        window.addEventListener('scroll', () => {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            progressBar.style.width = scrolled + "%";
        }, {
            passive: true
        });

        // Reveal Observer
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('opacity-100', 'translate-y-0');
                    entry.target.classList.remove('opacity-0', 'translate-y-12');
                }
            });
        }, {
            threshold: 0.1
        });

        document.querySelectorAll('.reveal').forEach(el => {
            el.classList.add('transition-all', 'duration-[800ms]', 'opacity-0', 'translate-y-12');
            observer.observe(el);
        });
    </script>
</body>

</html>
