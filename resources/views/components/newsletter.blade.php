<section class="py-12 lg:py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <!-- Colorful Gradient Background -->
        <div class="relative bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 rounded-3xl overflow-hidden shadow-2xl">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>

            <!-- Content -->
            <div class="relative px-6 sm:px-10 lg:px-16 py-12 sm:py-14 lg:py-16">
                <!-- Header -->
                <div class="text-center mb-8 sm:mb-10">
                    <!-- Flash Messages -->
                    @if ($message = Session::get('success'))
                        <div class="mb-4 p-4 bg-green-100/20 backdrop-blur-md border border-green-400/50 text-green-200 rounded-lg text-sm">
                            {{ $message }}
                        </div>
                    @endif

                    @if ($message = Session::get('info'))
                        <div class="mb-4 p-4 bg-blue-100/20 backdrop-blur-md border border-blue-400/50 text-blue-200 rounded-lg text-sm">
                            {{ $message }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100/20 backdrop-blur-md border border-red-400/50 text-red-200 rounded-lg text-sm">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <div class="inline-block mb-4">
                        <span class="inline-flex items-center justify-center w-14 h-14 bg-white/20 backdrop-blur-md rounded-full">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white mb-3 sm:mb-4">
                        Stay in the Loop!
                    </h2>
                    <p class="text-base sm:text-lg text-white/90 max-w-2xl mx-auto leading-relaxed">
                        Get exclusive job opportunities, career tips, and industry insights delivered straight to your inbox every week.
                    </p>
                </div>

                <!-- Form -->
                <form class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto" action="{{ route('newsletter.subscribe') }}" method="POST">
                    @csrf
                    <!-- Email Input -->
                    <input
                        type="email"
                        name="email"
                        placeholder="Enter your email"
                        required
                        class="flex-grow px-5 py-3 sm:py-3.5 bg-white/95 backdrop-blur-sm text-gray-900 placeholder-gray-500 rounded-full focus:outline-none focus:ring-2 focus:ring-white/50 transition-all duration-200 text-sm sm:text-base font-medium"
                    >

                    <!-- Subscribe Button -->
                    <button
                        type="submit"
                        class="px-6 sm:px-8 py-3 sm:py-3.5 bg-white text-purple-600 font-bold rounded-full hover:bg-blue-50 transition-all duration-300 transform hover:scale-105 hover:shadow-lg whitespace-nowrap text-sm sm:text-base"
                    >
                        Subscribe
                    </button>
                </form>

                <!-- Features Below Form -->
                <div class="mt-10 sm:mt-12 grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
                    <!-- Feature 1 -->
                    <div class="flex items-start gap-3 bg-white/10 backdrop-blur-md rounded-2xl p-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-white/20">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-white">Fresh Opportunities</h3>
                            <p class="text-xs text-white/75 mt-0.5">New jobs daily</p>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="flex items-start gap-3 bg-white/10 backdrop-blur-md rounded-2xl p-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-white/20">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-white">Personalized Tips</h3>
                            <p class="text-xs text-white/75 mt-0.5">For your career</p>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="flex items-start gap-3 bg-white/10 backdrop-blur-md rounded-2xl p-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-white/20">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-white">100% Spam-Free</h3>
                            <p class="text-xs text-white/75 mt-0.5">Unsubscribe anytime</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
