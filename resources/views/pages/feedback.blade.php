<x-layouts.app-main>
<section class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="bg-gradient-to-b from-card to-white dark:from-card-dark dark:to-surface-strong-dark border border-line dark:border-white/10 p-10 rounded-[40px] shadow-soft w-full max-w-lg min-h-[500px] flex flex-col items-center text-center" x-data="feedbackWizard()">
        
        <!-- Constant Header -->
        <div class="mb-10 w-full">
            <h1 class="font-serif text-3xl md:text-4xl font-bold text-text dark:text-text-dark mb-3 italic">
                {{ __('We value your feedback!') }}
            </h1>
            <p class="text-text-soft dark:text-text-soft-dark text-lg mb-6 leading-relaxed">
                {{ __('How was your experience with us?') }}
            </p>
            
            <!-- Star Selection -->
            <div class="flex justify-center space-x-2">
                <template x-for="i in 5" :key="i">
                    <button 
                        type="button" 
                        class="text-5xl transition-all transform hover:scale-110 active:scale-95 duration-200" 
                        :class="i <= stars ? 'text-gold' : 'text-gray-200 dark:text-white/10'"
                        @click="handleStarClick(i)"
                        x-html="i <= stars ? '&#9733;' : '&#9734;'">
                    </button>
                </template>
            </div>
            <p class="mt-3 text-sm font-bold uppercase tracking-widest text-gold/80" x-text="starText"></p>

            <!-- Next Step Button (Only in Step 1) -->
            <div class="mt-8 transition-all" x-show="step === 1 && stars > 0" x-transition>
                <button @click="nextStep()" 
                    class="bg-metro-red hover:bg-black text-white px-10 py-3 rounded-full font-bold uppercase tracking-widest text-sm shadow-lg hover:shadow-xl transition-all duration-300">
                    {{ __('Continue') }}
                </button>
            </div>
        </div>

        <div class="w-full flex-grow flex flex-col">
            
            <!-- Step 1 Placeholder (Stars not yet selected) -->
            <div x-show="step === 1 && stars === 0" x-transition class="py-10">
                <div class="w-16 h-1 bg-gold/20 mx-auto rounded-full"></div>
                <p class="mt-4 text-xs font-bold text-gray-400 uppercase tracking-[0.2em]">{{ __('Select a rating to continue') }}</p>
            </div>

            <!-- Step: Complaint (Stars < 3) -->
            <div x-show="step === 'complaint'" x-transition class="text-left">
                <h2 class="font-serif text-2xl font-bold mb-6 text-metro-dark dark:text-accent">
                    {{ __("We're sorry to hear that.") }}
                </h2>
                
                <form @submit.prevent="submitComplaint" class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Name') }}</label>
                            <input type="text" x-model="name" required 
                                class="w-full rounded-xl border border-gray-200 dark:border-white/10 dark:bg-card-dark px-4 py-3 text-sm focus:border-metro-red focus:ring-1 focus:ring-metro-red transition-all outline-none" 
                                placeholder="{{ __('Your name') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Phone Number') }}</label>
                            <input type="tel" x-model="phone" required 
                                class="w-full rounded-xl border border-gray-200 dark:border-white/10 dark:bg-card-dark px-4 py-3 text-sm focus:border-metro-red focus:ring-1 focus:ring-metro-red transition-all outline-none" 
                                placeholder="09xxxxxxxx">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('What went wrong?') }}</label>
                        <textarea x-model="complaint" required 
                            class="w-full rounded-xl border border-gray-200 dark:border-white/10 dark:bg-card-dark px-4 py-3 h-32 text-sm focus:border-metro-red focus:ring-1 focus:ring-metro-red transition-all outline-none" 
                            placeholder="{{ __('Tell us more about your experience...') }}"></textarea>
                    </div>

                    <button type="submit" 
                            class="w-full bg-metro-dark hover:bg-metro-hover text-white font-bold py-4 rounded-full shadow-lg transition-all duration-300 flex items-center justify-center space-x-2"
                            :disabled="loading">
                        <span x-show="!loading" class="uppercase tracking-widest text-sm">{{ __('Send Feedback') }}</span>
                        <span x-show="loading" class="uppercase tracking-widest text-sm">{{ __('Sending...') }}</span>
                    </button>
                    <button type="button" @click="step = 1; stars = 0; starText = 'Select your rating'" 
                        class="w-full text-center text-text-soft dark:text-text-soft-dark text-xs font-bold uppercase tracking-widest hover:text-gold transition-colors mt-2">
                        {{ __('Changed your mind?') }}
                    </button>
                </form>
            </div>

            <!-- Step: Review (Stars >= 3) -->
            <div x-show="step === 'review'" x-transition class="py-6">
                <div class="text-7xl mb-6">✨</div>
                <h2 class="font-serif text-3xl font-bold mb-4 text-gray-900 dark:text-white">{{ __('Awesome!') }}</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-10 leading-relaxed max-w-sm mx-auto">
                    {{ __("We're so glad you had a great experience. Would you mind sharing it on Google?") }}
                </p>
                
                <div class="flex flex-col space-y-4 max-w-xs mx-auto">
                    <a :href="googleReviewLink" target="_blank" 
                        class="bg-metro-red hover:bg-black dark:hover:bg-accent hover:shadow-xl text-white font-bold py-4 rounded-full transition-all duration-300 uppercase tracking-widest text-sm">
                        {{ __('Write a Google Review') }}
                    </a>
                    <button @click="step = 1; stars = 0; starText = 'Select your rating'" 
                        class="text-text-soft dark:text-text-soft-dark text-xs font-bold uppercase tracking-widest hover:text-gold transition-colors">
                        {{ __('Maybe later') }}
                    </button>
                </div>
            </div>

            <!-- Step: Success (After complaint) -->
            <div x-show="step === 'success'" x-transition class="py-12">
                <div class="w-20 h-20 bg-green-50 dark:bg-green-900/20 rounded-full flex items-center justify-center mx-auto mb-6 border-2 border-green-100 dark:border-green-800">
                    <span class="text-green-500 text-4xl">✓</span>
                </div>
                <h2 class="font-serif text-3xl font-bold mb-3 text-gray-900 dark:text-white">{{ __('Thank You!') }}</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-10 uppercase tracking-widest text-xs font-bold">{{ __('Feedback Received') }}</p>
                <a href="/" class="bg-surface dark:bg-card-dark border border-line dark:border-white/10 px-8 py-3 rounded-full font-bold hover:border-gold transition-colors text-sm uppercase tracking-widest">
                    {{ __('Back to Home') }}
                </a>
            </div>

        </div>

    </div>

    <script>
        function feedbackWizard() {
            return {
                step: 1, // 1, 'complaint', 'review', 'success'
                stars: 0,
                name: '',
                phone: '',
                complaint: '',
                loading: false,
                starText: 'Select your rating',
                googleReviewLink: '{{ $feedback->google_review_link ?? "#" }}',

                handleStarClick(val) {
                    this.stars = val;
                    this.starText = `${val} Star${val > 1 ? 's' : ''}`;
                },

                nextStep() {
                    if (this.stars > 0) {
                        if (this.stars < 3) {
                            this.step = 'complaint';
                        } else {
                            this.step = 'review';
                        }
                    }
                },

                submitComplaint() {
                    this.loading = true;
                    
                    fetch('{{ route("feedback.submit", $feedback->id) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            stars: this.stars,
                            customer_name: this.name,
                            customer_phone: this.phone,
                            complaint: this.complaint,
                        })
                    })
                    .then(res => {
                        if(res.ok) {
                            this.step = 'success';
                        } else {
                            alert('Something went wrong. Please try again.');
                        }
                    })
                    .catch(() => alert('Network error.'))
                    .finally(() => this.loading = false);
                }
            }
        }
    </script>
</section>
</x-layouts.app-main>
