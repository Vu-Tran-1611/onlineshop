<div class="bg-gradient-to-br from-cyan-800 via-slate-400 to-sky-700 p-6 shadow-2xl rounded-2xl border border-cyan-200">

    <h1 class="text-3xl font-bold text-white p-4 mb-2 tracking-wide flex items-center gap-2">
        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M16 7a4 4 0 01.88 7.903A5.5 5.5 0 1112 6.5"></path>
        </svg>
        BRAND
    </h1>
    <ul class="py-6 flex flex-wrap gap-6 justify-center">
        @foreach ($brands as $br)
            <a href="{{ route('more-products-by-brand', ['brand' => $br->slug]) }}">
                <li
                    class="group cursor-pointer bg-white bg-opacity-80 rounded-xl shadow-lg hover:shadow-2xl hover:scale-105 transition-all duration-300 border border-cyan-100 w-40 h-40 flex items-center justify-center relative overflow-hidden">
                    <div class="absolute inset-0 bg-cover bg-center opacity-80 group-hover:opacity-100 transition-all duration-300"
                        style="background-image: url('{{ asset($br->logo) }}')"></div>
                    <div class="absolute inset-0 bg-cyan-900 bg-opacity-0 group-hover:bg-opacity-10 transition-all">
                    </div>

                </li>
            </a>
        @endforeach
    </ul>
</div>
