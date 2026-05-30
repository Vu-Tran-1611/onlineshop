<div class="box-gradient box-gradient-{{ $comirecRecommendedProducts->isNotEmpty() ? 'filled' : 'empty' }}
    my-8 rounded-2xl shadow-2xl">

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
            <ul class="grid  grid-cols-5 py-5 gap-5 cursor-pointer ">
                @include('frontend.partials.filtered-product-list', ['products' => $comirecRecommendedProducts])
            </ul>
            <div class="flex justify-center">
                <a href="{{ route('user.more-products-by-comirec') }}"
                    class="bg-sky-600 text-white px-6 py-2 rounded-md font-semibold hover:bg-sky-800 transition">
                    See More
                </a>
            </div>
        @endif


    </div>

    {{-- Effects --}}
    <div class="blob-gradient">
    </div>
</div>


@push('scripts')
    <script>
        // Product button click
        $(".product").on("click", function() {
            const url = $(this).data("url");
            window.location.replace(url);
        });
    </script>
@endpush
