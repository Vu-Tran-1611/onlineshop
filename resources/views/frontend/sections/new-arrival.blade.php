<div class="bg-white p-3 shadow-xl rounded-md my-5">

    <div class="flex flex-col items-center mb-6">
        <h1
            class="text-3xl md:text-4xl font-extrabold text-gray-900 bg-gradient-to-r from-yellow-100 via-pink-100 to-purple-100 px-8 py-4 rounded-xl shadow-md tracking-wide">
            <span class="animate-pulse">🌟</span>
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-sky-600 via-pink-500 to-purple-600">
                New Arrival Products
            </span>
            <span class="animate-pulse">🌟</span>
        </h1>

    </div>

    <ul class="grid  grid-cols-5 py-5 gap-5 ccursor-pointer ">
        @include('frontend.partials.filtered-product-list', ['products' => $newProducts])
    </ul>
</div>


@push('scripts')
@endpush
