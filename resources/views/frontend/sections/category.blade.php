<div class="bg-gradient-to-br from-cyan-800 via-slate-400 to-sky-700 p-6 shadow-2xl rounded-2xl border border-cyan-200">

    <h1 class="text-3xl font-bold p-4 text-white flex items-center gap-2">
        <svg class="w-8 h-8 text-white animate-bounce" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24">
            <path d="M3 7h18M3 12h18M3 17h18" />
        </svg>
        CATEGORY
    </h1>
    <ul class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-6 py-6">
        @foreach ($categories as $cate)
            <li data-url="{{ route('product', ['category' => $cate->slug]) }}"
                class="category group bg-white hover:bg-cyan-50 shadow-md hover:shadow-2xl transition-all duration-300 rounded-xl border-2 border-transparent hover:border-cyan-400 pb-4 text-center cursor-pointer transform hover:-translate-y-2 hover:scale-105 relative overflow-hidden">
                <div
                    class="absolute inset-0 bg-cyan-400 opacity-0 group-hover:opacity-20 transition-opacity duration-300 rounded-xl">
                </div>
                <img class="mx-auto max-w-[100px] max-h-[100px] rounded-full border-4 border-white shadow group-hover:scale-110 transition-transform duration-300"
                    src="{{ asset($cate->image) }}" alt="{{ $cate->name }}" />
                <h1
                    class="mt-3 text-lg font-semibold text-cyan-700 group-hover:text-cyan-900 transition-colors duration-300">
                    {{ $cate->name }}
                </h1>
                <span
                    class="block mt-1 text-xs text-gray-400 group-hover:text-cyan-500 transition-colors duration-300 tracking-wide uppercase">Shop
                    Now</span>
            </li>
        @endforeach
    </ul>
</div>

@push('scripts')
    <script>
        $(document).on("click", ".category", function() {
            const url = $(this).data("url");
            window.location.href = url;
        });
    </script>
@endpush
