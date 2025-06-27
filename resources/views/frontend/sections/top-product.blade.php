<div class="bg-white p-3 shadow-xl rounded-md my-5">

    <div class="flex items-center gap-4 mb-4">
        <span class="inline-block w-2 h-8 bg-gradient-to-b from-sky-500 to-sky-700 rounded-lg shadow"></span>
        <h1 class="text-3xl font-extrabold text-slate-500 tracking-tight drop-shadow-sm">
            Top Products
        </h1>
    </div>
    <ul class="grid  grid-cols-5 py-5 gap-5 ccursor-pointer ">
        @include('frontend.partials.filtered-product-list', ['products' => $topProducts])
    </ul>
    {{-- See more button --}}
    <div class="flex justify-center">
        <a href="{{ route('more-products-by-type', ['type' => 'top']) }}"
            class="bg-sky-600 text-white px-6 py-2 rounded-md font-semibold hover:bg-sky-800 transition">
            See More
        </a>
    </div>
</div>
