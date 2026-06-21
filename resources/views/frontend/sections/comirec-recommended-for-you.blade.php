<div class="box-gradient box-gradient-{{ $comirecRecommendedProducts->isNotEmpty() ? 'filled' : 'empty' }}
    my-8 rounded-2xl shadow-2xl {{ $comirecRecommendedProducts->isNotEmpty() ? '!min-h-[620px]' : '' }}">
    {{-- Content --}}
    <div class="p-6 bg-gradient rounded-2xl bg-white relative z-10"
        style="--box-height: {{ $comirecRecommendedProducts->isNotEmpty() ? '99%' : '95%' }};">
        <div class="flex items-center justify-between mb-6">
            <h1 style="color:rgb(50, 118, 122)" class="text-3xl font-bold flex items-center gap-2">
                Continue Exploring Your Style

            </h1>
        </div>

        @if ($comirecRecommendedProducts->isEmpty())
            <div class="text-center text-gray-500 py-5">
                <p class="text-lg font-bold">Sorry, no recommended products available at the moment ...</p>
                <p>Please login and interact with some of our items to try the recommendations</p>
            </div>
        @else
            <div class="relative py-5">
                <div class="swiper comirecSwiper px-1">
                    <ul class="swiper-wrapper cursor-pointer">
                        @foreach ($comirecRecommendedProducts as $p)
                            <div class="swiper-slide !h-auto">
                                @include('frontend.partials.product-card', ['p' => $p])
                            </div>
                        @endforeach
                    </ul>
                </div>

                <button
                    class="comirec-prev absolute top-1/2 -translate-y-1/2 left-0 md:-left-4 z-20 h-9 w-9 rounded-full bg-white/95 border border-slate-200 shadow-md text-slate-700 hover:bg-sky-600 hover:text-white transition">
                    <i class="fa-solid fa-chevron-left text-sm"></i>
                </button>
                <button
                    class="comirec-next absolute top-1/2 -translate-y-1/2 right-0 md:-right-4 z-20 h-9 w-9 rounded-full bg-white/95 border border-slate-200 shadow-md text-slate-700 hover:bg-sky-600 hover:text-white transition">
                    <i class="fa-solid fa-chevron-right text-sm"></i>
                </button>

                <div class="comirec-pagination  text-center"></div>
            </div>


        @endif


    </div>


</div>


@push('scripts')
    <script>
        new Swiper('.comirecSwiper', {
            slidesPerView: 1.1,
            spaceBetween: 14,
            speed: 700,
            grabCursor: true,
            navigation: {
                nextEl: '.comirec-next',
                prevEl: '.comirec-prev',
            },
            pagination: {
                el: '.comirec-pagination',
                clickable: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 16,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 20,
                },
                1280: {
                    slidesPerView: 4,
                    spaceBetween: 20,
                }
            }
        });

        // Product button click
        $(".product").on("click", function() {
            const url = $(this).data("url");
            window.location.replace(url);
        });
    </script>
@endpush
