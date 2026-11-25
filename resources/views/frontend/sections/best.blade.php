<div class="bg-white p-3 shadow-xl rounded-md my-5">
    <div
        class="flex items-center gap-6 p-6 text-sky-800">
        <h1 class="text-3xl font-bold tracking-wide uppercase">
            Best Products
        </h1>

    </div>
    <ul class="grid grid-cols-5 py-5 gap-5">
        @include('frontend.partials.filtered-product-list', ['products' => $bestProducts])
    </ul>
    {{-- See more button --}}
    <div class="flex justify-center">
        <a href="{{ route('more-products-by-type', ['type' => 'best']) }}"
            class="bg-sky-600 text-white px-6 py-2 rounded-md font-semibold hover:bg-sky-800 transition">
            See More
        </a>
    </div>
</div>
