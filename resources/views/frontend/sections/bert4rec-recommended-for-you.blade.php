<div class="box-gradient box-gradient-{{ $bert4RecRecommendedProducts->isNotEmpty() ? 'filled' : 'empty' }}
    my-8 rounded-2xl {{ $bert4RecRecommendedProducts->isNotEmpty() ? '!min-h-[620px]' : '' }}">
    {{-- Content --}}
    <div class="p-6 bg-gradient rounded-2xl bg-white relative z-10"
        style="--box-height: {{ $bert4RecRecommendedProducts->isNotEmpty() ? '99%' : '95%' }};">
        <div class="flex items-center justify-between mb-6">
            <h1 style="color:rgb(50, 118, 122)" class="text-3xl font-bold flex items-center gap-2">
                Recommended based on your cart items

            </h1>
        </div>

        @if ($bert4RecRecommendedProducts->isEmpty())
            <div class="text-center text-gray-500 py-5">
                <p class="text-lg font-bold">Sorry, no recommended products available at the moment ...</p>
                <p>Please login and interact with some of our items to try the recommendations</p>
            </div>
        @else
            <div class="relative py-5">
                <div class="swiper bert4recSwiper px-1">
                    <ul class="swiper-wrapper cursor-pointer items-stretch">
                        @foreach ($bert4RecRecommendedProducts as $p)
                            <div class="swiper-slide !h-auto flex">
                                @include('frontend.partials.product-card', ['p' => $p])
                            </div>
                        @endforeach
                    </ul>
                </div>

                <button
                    class="bert4rec-prev absolute top-1/2 -translate-y-1/2 left-0 md:-left-4 z-20 h-9 w-9 rounded-full bg-white/95 border border-slate-200  text-slate-700 hover:bg-sky-600 hover:text-white transition">
                    <i class="fa-solid fa-chevron-left text-sm"></i>
                </button>
                <button
                    class="bert4rec-next absolute top-1/2 -translate-y-1/2 right-0 md:-right-4 z-20 h-9 w-9 rounded-full bg-white/95 border border-slate-200  text-slate-700 hover:bg-sky-600 hover:text-white transition">
                    <i class="fa-solid fa-chevron-right text-sm"></i>
                </button>

                <div class="bert4rec-pagination  text-center"></div>
            </div>


        @endif


    </div>


</div>


@push('scripts')
    <script>
        new Swiper('.bert4recSwiper', {
            slidesPerView: 1.1,
            spaceBetween: 14,
            speed: 700,
            grabCursor: true,
            navigation: {
                nextEl: '.bert4rec-next',
                prevEl: '.bert4rec-prev',
            },
            pagination: {
                el: '.bert4rec-pagination',
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
                    slidesPerView: 5,
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
