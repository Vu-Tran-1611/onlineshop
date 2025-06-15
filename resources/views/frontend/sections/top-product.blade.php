<div class="bg-white p-3 shadow-xl rounded-md my-5">

    <div class="flex items-center gap-4 mb-4">
        <span class="inline-block w-2 h-8 bg-gradient-to-b from-sky-500 to-sky-700 rounded-lg shadow"></span>
        <h1 class="text-3xl font-extrabold text-slate-500 tracking-tight drop-shadow-sm">
            Top Products
        </h1>
    </div>
    <ul class="grid  grid-cols-5 py-5 gap-5 ccursor-pointer ">
        @foreach ($topProducts as $p)
            <li
                class=" cursor-pointer group bg-white rounded-xl overflow-hidden shadow-lg relative hover:shadow-2xl hover:-translate-y-2 transition-all hover:border-sky-600 flex flex-col justify-between leading-6 border border-slate-100">
                <div class="relative">
                    <img class="min-h-[180px] w-full object-cover group-hover:scale-105 transition-transform duration-300"
                        src="{{ asset($p->thumb_image) }}" />
                    <div class="absolute top-2 left-2 flex gap-2 w-full pr-2 justify-between">
                        <span class="bg-sky-600 rounded-full text-white px-3 py-1 text-xs shadow">
                            {{ getProductType($p) }}
                        </span>
                        @if (checkSale($p))
                            <span class="bg-pink-600 rounded-full text-white px-3 py-1 text-xs shadow animate-bounce">
                                -{{ calculateSalePercent($p) . '%' }}
                            </span>
                        @endif
                    </div>
                    <div class="absolute top-2 right-2">
                        <button class="bg-white/80 hover:bg-sky-100 rounded-full p-2 shadow transition add-to-wishlist"
                            data-id="{{ $p->id }}">
                            <i class="fa-regular fa-heart text-sky-600"></i>
                        </button>
                    </div>
                </div>
                <div class="p-4 flex flex-col gap-2">
                    <h1 class="font-semibold text-base text-slate-800 group-hover:text-sky-700 truncate"
                        title="{{ $p->name }}">{{ $p->name }}</h1>

                    <div class="flex items-center justify-between mt-2">
                        <span class="text-orange-700 font-bold text-lg">${{ $p->price }}</span>
                        <span class="text-xs text-slate-500 flex items-center gap-1">
                            <i class="fa-solid fa-fire text-orange-700"></i> {{ $p->soldCount() }} Sold
                        </span>
                    </div>
                    <div class="flex gap-1 mt-1">
                        @php
                            $avgRating = $p->averageRating();
                            $numberOfReviews = $p->numberOfReviews();
                        @endphp
                        @for ($i = 0; $i < 5; $i++)
                            <i
                                class="fa-star {{ $i < $avgRating ? 'fa-solid text-yellow-400' : 'fa-regular text-slate-300' }}"></i>
                        @endfor
                        <span class="text-xs text-slate-400 ml-1">({{ $numberOfReviews }})</span>
                    </div>
                    <button data-url="{{ route('product', ['product' => $p->slug]) }}"
                        class="product mt-3 w-full bg-sky-600 hover:bg-sky-700 text-white rounded-lg py-1.5 font-medium transition-all opacity-0 group-hover:opacity-100 group-hover:translate-y-0 translate-y-2 duration-200">
                        View Details
                    </button>
                </div>
            </li>
        @endforeach
    </ul>
</div>
