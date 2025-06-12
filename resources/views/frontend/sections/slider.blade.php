<!-- Swiper -->
<div class="grid grid-cols-4 grid-rows-5 md:grid-cols-4 md:grid-rows-3 py-8 gap-5">
    <div
        class=" shadow-xl  row-start-1 col-start-1 col-span-4 row-span-1 bg-white rounded-xl p-4
    md:col-span-1 md:row-span-2">
        <h1 class="gradient_text uppercase  text-3xl  ">Hot Category</h1>
        <ul class="mt-3 text-lg flex flex-col gap-2">
            @foreach ($hotCategories as $cate)
                <li class="p-2 hover:bg-slate-200 rounded-lg"><a class="block"
                        href="{{ route('product', ['category' => $cate->slug]) }}">{{ $cate->name }}</a></li>
            @endforeach
        </ul>
    </div>
    @foreach ($categoryBanners as $banner)
        <div class="col-span-2 rounded-xl row-span-1 md:col-span-1 shadow-xl">
            <a href="#">
                <img class="  rounded-xl h-full" alt="{{ $banner->name }}" src="{{ $banner->banner }}" />
            </a>
        </div>
    @endforeach

    <div style=" --swiper-navigation-color: #fff; --swiper-pagination-color: #fff"
        class="row-start-2 col-span-4 row-span-1 md:row-start-1 md:row-span-2  md:col-start-2 md:col-span-2  swiper max-w-full h-full mySwiper">
        <div class="rounded-xl swiper-wrapper max-w-full ">
            @foreach ($sliders as $slider)
                <div class="swiper-slide rounded-xl ">
                    <img class=" rounded-xl block md:w-full h-full" src="{{ asset($slider->banner) }}" />
                </div>
            @endforeach
        </div>
        <div class=" swiper-button-next"></div>
        <div class=" swiper-button-prev"></div>
        <div class="swiper-pagination"></div>
    </div>

</div>

@push('scripts')
    <script>
        var swiper = new Swiper(".mySwiper", {
            spaceBetween: 30,
            centeredSlides: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });
    </script>
@endpush
