<x-layouts.app-main>
<section class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md" x-data="feedbackWizard()">
        
        <div x-show="step === 1" x-transition>
            <h1 class="text-2xl font-bold mb-2">We value your feedback!</h1>
            <p class="text-gray-600 mb-6">How was your experience with us?</p>
            
            <div class="flex justify-center space-x-2 mb-4">
                <template x-for="i in 5" :key="i">
                    <button 
                        type="button" 
                        class="text-5xl transition-transform hover:scale-110" 
                        :class="i <= stars ? 'text-yellow-400' : 'text-gray-300'"
                        @click="handleStarClick(i)"
                        x-html="i <= stars ? '&#9733;' : '&#9734;'">
                    </button>
                </template>
            </div>
            <p class="text-center font-medium text-gray-500" x-text="starText"></p>
        </div>

        <div x-show="step === 'complaint'" x-transition>
            <button @click="step = 1" class="text-sm text-blue-500 mb-4 hover:underline">&larr; Back</button>
            <h2 class="text-xl font-bold mb-4">We're sorry to hear that.</h2>
            
            <form @submit.prevent="submitComplaint">
                <div class="space-y-4">
                    <div>
                        <label class="block mb-1 font-semibold text-gray-700">Name</label>
                        <input type="text" x-model="name" required class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-red-400 outline-none" placeholder="Your name">
                    </div>

                    <div>
                        <label class="block mb-1 font-semibold text-gray-700">Phone Number</label>
                        <input type="tel" x-model="phone" required class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-red-400 outline-none" placeholder="05x xxx xxxx">
                    </div>

                    <div>
                        <label class="block mb-1 font-semibold text-gray-700">What went wrong?</label>
                        <textarea x-model="complaint" required class="w-full border rounded-lg p-3 h-32 focus:ring-2 focus:ring-red-400 outline-none" placeholder="Tell us more..."></textarea>
                    </div>

                    <button type="submit" 
                            class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-lg transition-colors"
                            :disabled="loading">
                        <span x-show="!loading">Send Feedback</span>
                        <span x-show="loading">Sending...</span>
                    </button>
                </div>
            </form>
        </div>

        <div x-show="step === 'review'" x-transition class="text-center">
            <div class="text-6xl mb-4">🎉</div>
            <h2 class="text-2xl font-bold mb-2">Awesome!</h2>
            <p class="text-gray-600 mb-6">We're so glad you had a great experience. Would you mind sharing it on Google?</p>
            
            <div class="flex flex-col space-y-3">
                <a :href="googleReviewLink" target="_blank" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg transition-colors">
                    Write a Google Review
                </a>
                <button @click="step = 1" class="text-gray-400 text-sm hover:underline">Maybe later</button>
            </div>
        </div>

        <div x-show="step === 'success'" x-transition class="text-center py-8">
            <div class="text-green-500 text-6xl mb-4">✓</div>
            <h2 class="text-2xl font-bold mb-2">Thank You!</h2>
            <p class="text-gray-600">Your feedback has been received. We will look into it immediately.</p>
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
                    
                    // Logic to switch steps based on rating
                    setTimeout(() => {
                        if (this.stars < 3) {
                            this.step = 'complaint';
                        } else {
                            this.step = 'review';
                        }
                    }, 400); // Slight delay for visual feedback on the stars
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
