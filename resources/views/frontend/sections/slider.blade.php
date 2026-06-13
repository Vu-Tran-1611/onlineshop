
<div class="relative overflow-hidden bg-gradient-to-br ">
    <div class="container mx-auto  py-12">
        <div class="grid lg:grid-cols-12 gap-8 items-stretch">



            <!-- Main Content Area -->
            <div class="lg:col-span-12 flex flex-col">
                <!-- Hero Slider -->
                <div class="relative mb-8">
                    <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff; --swiper-pagination-bullet-inactive-color: rgba(255,255,255,0.5);"
                        class="swiper mySwiper rounded-3xl overflow-hidden shadow-2xl bg-white/10 backdrop-blur-sm border border-white/20">
                        <div class="swiper-wrapper">
                            @foreach ($sliders as $slider)
                                <div class="swiper-slide relative group cursor-pointer" onclick="window.location='{{ route('product',$slider->url) }}'">
                                    <div class="relative overflow-hidden">
                                        <img class="w-full h-96 lg:h-[500px] object-fill transition-transform duration-700 group-hover:scale-105"
                                            src="{{ asset($slider->banner) }}"
                                            alt="Hero Slide" />
                                        <!-- Gradient Overlay -->
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Custom Navigation -->
                        <div class="swiper-button-next !w-12 !h-12 !bg-white/20 !backdrop-blur-md !rounded-full !border !border-white/30 after:!text-base after:!font-bold hover:!bg-white/30 transition-all duration-300"></div>
                        <div class="swiper-button-prev !w-12 !h-12 !bg-white/20 !backdrop-blur-md !rounded-full !border !border-white/30 after:!text-base after:!font-bold hover:!bg-white/30 transition-all duration-300"></div>

                        <!-- Custom Pagination -->
                        <div class="swiper-pagination !bottom-6"></div>
                    </div>


                </div>

                {{--
                <!-- Category Showcase -->
                <div class="bg-white/60 backdrop-blur-md rounded-3xl shadow-xl border border-white/20 p-8 flex-1 flex flex-col">
                    <!-- Section Header -->
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                                Featured Categories
                            </h3>
                            <p class="text-gray-600 mt-2">Discover amazing products across different categories</p>
                        </div>

                    </div>

                    <!-- Category Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 xl:grid-cols-5 gap-6 flex-1">
                        @foreach ($categoryBanners as $index => $banner)
                            <div class="group cursor-pointer h-full rounded-2xl border overflow-hidden relative">
                                <a href="#" class="block h-full">
                                    <div class="relative bg-white  rounded-2xl shadow-lg">
                                        <!-- Image Container -->
                                        <div class="aspect-square overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100 flex-shrink-0 relative">
                                            <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                                alt="{{ $banner->name }}"
                                                src="{{ $banner->banner }}" />
                                            <!-- Hover Overlay -->
                                            <div class="absolute inset-0 bg-gradient-to-t from-purple-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                        </div>

                                        <!-- Content -->
                                        <div class="p-4 text-center flex-1 flex items-center justify-center">
                                            <h5 class="font-semibold text-gray-800 group-hover:text-purple-700 transition-colors duration-300 text-sm leading-tight">
                                                {{ $banner->name }}
                                            </h5>
                                        </div>

                                        <!-- Gradient Border Animation -->
                                        <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-purple-400 via-indigo-400 to-blue-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10 blur-sm transform scale-105"></div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                --}}

            </div>
        </div>
    </div>

    <!-- Background Decorations -->
    <div class="absolute top-1/4 right-1/4 w-64 h-64 bg-gradient-to-br from-purple-200/30 to-indigo-200/30 rounded-full blur-3xl"></div>
    <div class="absolute bottom-1/4 left-1/4 w-96 h-96 bg-gradient-to-br from-blue-200/20 to-cyan-200/20 rounded-full blur-3xl"></div>
</div>

@push('scripts')
    <script>
        var swiper = new Swiper(".mySwiper", {
            spaceBetween: 30,
            centeredSlides: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            speed: 1200,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            effect: 'slide',
            slidesPerView: 1,
            loop: true
        });
    </script>
@endpush
