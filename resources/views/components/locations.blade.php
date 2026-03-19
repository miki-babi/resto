@blaze(compile: true)

<section class="py-20">
    <div class="container mx-auto px-4 max-w-7xl">
        <div class="flex justify-between items-center mb-8">
            <h2 class="font-serif text-3xl">Our Locations</h2>
            <!-- <div class="flex gap-2">
                    <button
                        class="w-10 h-10 border rounded-full flex items-center justify-center hover:bg-gray-100 transition">‹</button>
                    <button
                        class="w-10 h-10 border rounded-full flex items-center justify-center hover:bg-gray-100 transition">›</button>
                </div> -->
        </div>
        <div class="mb-6 flex gap-4 overflow-x-auto pb-2 ">
            <button class="pb-2  border-metro-red font-bold whitespace-nowrap">Bisrate Gebriel</button>
            {{-- <button class="pb-2 text-gray-400 font-medium whitespace-nowrap hover:text-metro-red">Decatur</button>
                <button class="pb-2 text-gray-400 font-medium whitespace-nowrap hover:text-metro-red">Northwest</button>  --}}
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
            <div class="relative h-[400px] w-full bg-gray-200 rounded-xl overflow-hidden shadow-inner">

                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126103.44835317045!2d38.577444797265635!3d8.99671990000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x164b87005e442cb9%3A0xc12d88d30e842f!2sMera%20Coffee!5e0!3m2!1sen!2set!4v1773910769673!5m2!1sen!2set"
                    class="absolute inset-0 w-full h-full grayscale-[20%] contrast-[1.1]" style="border:0;"
                    allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                </iframe>




                <div class="absolute inset-0  pointer-events-none flex items-center justify-center">
                    <div class="flex flex-col items-center">
                        {{-- Custom Pin/Tag Content --}}
                        <div
                            class="bg-metro-dark text-white px-4 py-2 rounded-full text-xs font-bold shadow-2xl flex items-center gap-2 animate-bounce">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Visit Us</span>
                        </div>
                        {{-- The "Point" of the pin --}}
                        {{-- <div class="w-1 h-4 bg-emerald-600 shadow-lg"></div> --}}
                    </div>
                </div>

            </div>
            <div class="bg-metro-dark p-10 rounded-xl flex flex-col justify-between">
                <div>
                    <p class="text-sm font-bold uppercase text-gray-500 mb-2 text-white">Mera Coffee</p>
                    <h3 class="text-3xl font-serif mb-6 text-white">Bisrate Gebriel, Addis Ababa</h3>
                    <div class="grid grid-cols-2 gap-8 mb-8">
                        <div>
                            <p class="font-bold text-sm mb-1 text-white">Address</p>
                            <p class="text-white">1420 W. Horizon Ridge Pkwy.<br />Henderson, NV 89012</p>
                        </div>
                        <div>
                            <p class="font-bold text-sm mb-1 text-white">Contact</p>
                            <p class="text-white">(702) 458-4764</p>
                        </div>
                    </div>
                    <div class=" pt-6">
                        <p class="font-bold text-sm mb-2 text-white">Hours Today</p>
                        <p class="text-white">Friday: 11:00 AM - 9:00 PM</p>
                    </div>
                </div>
                <div class="mt-8 flex items-center justify-between flex-wrap gap-4">
                    {{-- <a class="text-metro-red font-bold underline" href="#">become our customer </a> --}}
                    <a class="bg-metro-red text-white px-8 py-3 rounded font-bold uppercase hover:bg-metro-hover transition"
                        href="#">Get Directions ›</a>
                </div>
            </div>
        </div>
    </div>
</section>
