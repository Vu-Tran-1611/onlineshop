<div
    class="bg-gradient-to-br from-orange-100 via-white to-sky-200 p-6 shadow-2xl rounded-2xl my-8 border border-orange-200">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-orange-600 flex items-center gap-2">
            <i class="fa-solid fa-bolt text-yellow-400 animate-pulse"></i>
            FLASH SELL
        </h1>
        <div id="flash-sell-timer"
            class="flex items-center gap-3 bg-gradient-to-r from-sky-700 via-orange-500 to-pink-500 text-white px-7 py-3 rounded-xl shadow-xl font-mono text-xl font-bold  backdrop-blur-sm">
            <i class="fa-regular fa-clock mr-3 text-3xl animate-spin-slow"></i>
            <div class="flex items-center gap-2">
                <div class="flex flex-col items-center">
                    <span id="days"
                        class="bg-white/20 px-3 py-1 rounded-lg text-2xl tracking-widest shadow-inner">00</span>
                    <span class="text-xs mt-1 uppercase tracking-wide">Days</span>
                </div>
                <span class="text-2xl font-extrabold">:</span>
                <div class="flex flex-col items-center">
                    <span id="hours"
                        class="bg-white/20 px-3 py-1 rounded-lg text-2xl tracking-widest shadow-inner">00</span>
                    <span class="text-xs mt-1 uppercase tracking-wide">Hrs</span>
                </div>
                <span class="text-2xl font-extrabold">:</span>
                <div class="flex flex-col items-center">
                    <span id="minutes"
                        class="bg-white/20 px-3 py-1 rounded-lg text-2xl tracking-widest shadow-inner">00</span>
                    <span class="text-xs mt-1 uppercase tracking-wide">Min</span>
                </div>
                <span class="text-2xl font-extrabold">:</span>
                <div class="flex flex-col items-center">
                    <span id="seconds"
                        class="bg-white/20 px-3 py-1 rounded-lg text-2xl tracking-widest shadow-inner">00</span>
                    <span class="text-xs mt-1 uppercase tracking-wide">Sec</span>
                </div>
            </div>
        </div>
    </div>
    <ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 py-5">
        @foreach ($flashSellProducts as $p)
            <li
                class="group bg-white rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl hover:-translate-y-2 transition-all border border-slate-100 flex flex-col justify-between leading-6 relative">
                <div class="relative">
                    <img class="min-h-[180px] w-full object-cover group-hover:scale-105 transition-transform duration-300"
                        src="{{ asset($p->product->thumb_image) }}" />
                    <div class="absolute top-3 left-3 flex gap-2 w-full pr-2 justify-between">
                        <span
                            class="bg-sky-600 rounded-full text-white px-3 py-1 text-xs shadow font-semibold tracking-wide">
                            {{ getProductType($p->product) }}
                        </span>
                        @if (checkSale($p->product))
                            <span
                                class="bg-gradient-to-r from-pink-600 to-orange-500 rounded-full text-white px-3 py-1 text-xs shadow font-bold animate-bounce">
                                -{{ calculateSalePercent($p->product) . '%' }}
                            </span>
                        @endif
                    </div>
                    <div class="absolute top-3 right-3">
                        <button class="bg-white/90 hover:bg-sky-100 rounded-full p-2 shadow transition add-to-wishlist"
                            data-id="{{ $p->product->id }}">
                            <i class="fa-regular fa-heart text-sky-600 text-lg"></i>
                        </button>
                    </div>
                </div>
                <div class="p-5 flex flex-col gap-2">
                    <h1 class="font-semibold text-lg text-slate-800 group-hover:text-sky-700 truncate"
                        title="{{ $p->product->name }}">{{ $p->product->name }}</h1>
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-orange-700 font-bold text-xl">${{ $p->product->price }}</span>
                        <span class="text-xs text-slate-500 flex items-center gap-1">
                            <i class="fa-solid fa-fire text-orange-700"></i> {{ $p->product->soldCount() }} Sold
                        </span>
                    </div>
                    <div class="flex gap-1 mt-1">
                        @php
                            $numberOfReviews = $p->product->numberOfReviews();
                            $avgRating = $p->product->averageRating();
                        @endphp
                        @for ($i = 0; $i < 5; $i++)
                            <i
                                class="fa-star {{ $i < $avgRating ? 'fa-solid text-yellow-400' : 'fa-regular text-slate-300' }}"></i>
                        @endfor
                        <span class="text-xs text-slate-400 ml-1">{{ $numberOfReviews }}</span>
                    </div>
                    <button data-url="{{ route('product', ['product' => $p->product->slug]) }}"
                        class="product mt-4 w-full border-2 border-sky-600 text-sky-700 hover:bg-sky-600 hover:text-white transition-colors rounded-lg py-2 font-semibold shadow group-hover:opacity-100 group-hover:translate-y-0 translate-y-2 opacity-0 duration-200 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-eye"></i>
                        <span>View Details</span>
                    </button>
                </div>
            </li>
        @endforeach
    </ul>
</div>
@push('scripts')
    <script>
        // Countdown Timer for Demo: 5 minutes from page load
        function startFlashSellTimerDemo() {
            const now = new Date();
            const end = new Date(now.getTime() + 5 * 60 * 1000); // 5 minutes from now

            function pad(n) {
                return n < 10 ? '0' + n : n;
            }

            function updateTimer() {
                const now = new Date().getTime();
                const distance = end.getTime() - now;
                if (distance < 0) {
                    document.getElementById("flash-sell-timer").innerHTML =
                        '<span class="text-red-200 font-bold">Flash Sell Ended</span>';
                    clearInterval(timerInterval);
                    return;
                }
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                document.getElementById("days").textContent = pad(days);
                document.getElementById("hours").textContent = pad(hours);
                document.getElementById("minutes").textContent = pad(minutes);
                document.getElementById("seconds").textContent = pad(seconds);
            }
            updateTimer();
            var timerInterval = setInterval(updateTimer, 1000);
        }
        startFlashSellTimerDemo();

        // Product button click
        $(".product").on("click", function() {
            const url = $(this).data("url");
            window.location.replace(url);
        });
    </script>
@endpush
