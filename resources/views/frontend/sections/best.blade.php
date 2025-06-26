<div class="bg-white p-3 shadow-xl rounded-md my-5">
    <div
        class="flex items-center gap-6 p-6 bg-gradient-to-r from-sky-700 via-sky-500 to-sky-700 rounded-xl shadow-lg mb-6 border border-slate-200">
        <i class="fa-solid fa-crown text-yellow-400 text-3xl"></i>
        <h1 class="text-3xl font-bold text-white tracking-wide uppercase">
            Best Products
        </h1>
        <span
            class="ml-auto bg-white/70 text-sky-700 px-6 py-2 rounded-full text-base font-semibold shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-star text-yellow-400"></i>
            Top Picks
        </span>
    </div>
    <ul class="grid grid-cols-5 py-5 gap-5">
        @include('frontend.partials.filtered-product-list', ['products' => $bestProducts])
    </ul>
</div>
