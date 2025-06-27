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
        @include('frontend.partials.filtered-product-list', [
            'products' => $flashSellProducts,
            'isFlashSell' => true,
        ])
    </ul>
    {{-- See more button --}}
    <div class="flex justify-center">
        <a href="{{ route('more-products-by-flash-sale') }}"
            class="bg-sky-600 text-white px-6 py-2 rounded-md font-semibold hover:bg-sky-800 transition">
            See More
        </a>
    </div>
</div>
@push('scripts')
    <script>
        const flashSellEndDate = @json($flashSellEndDate->end_date);
        console.log(flashSellEndDate)
        // Countdown Timer for Demo: 5 minutes from page load
        function startFlashSellTimerDemo() {
            const now = new Date();
            const end = new Date(flashSellEndDate);

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
