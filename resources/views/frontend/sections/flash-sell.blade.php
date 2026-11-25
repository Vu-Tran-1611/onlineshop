<div class="bg-white rounded-2xl  p-6 my-8">
    <div class="flex justify-between gap-4 mb-6">
        <div
        class="flex items-center gap-6 p-6 font-bold mb-6 text-orange-500">
            <h1 class="text-3xl  tracking-wide uppercase">
                Flash Sell Products
            </h1>

        </div>
        {{-- Countdown --}}
        <div id="flash-sell-timer" class="flex items-center gap-3 bg-slate-100 rounded-2xl px-5 py-3">
            <i class="fa-regular fa-clock text-red-500 text-3xl mr-1 pb-6 animate-pulse"></i>
            <div class="flex flex-col items-center">
                <span id="days"
                    class="bg-slate-900 text-white rounded-xl w-16 h-16 flex items-center justify-center text-3xl font-extrabold font-mono shadow-sm">00</span>
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-600 mt-2">Days</span>
            </div>
            <span class="text-slate-400 font-extrabold text-3xl pb-6">:</span>
            <div class="flex flex-col items-center">
                <span id="hours"
                    class="bg-slate-900 text-white rounded-xl w-16 h-16 flex items-center justify-center text-3xl font-extrabold font-mono shadow-sm">00</span>
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-600 mt-2">Hrs</span>
            </div>
            <span class="text-slate-400 font-extrabold text-3xl pb-6">:</span>
            <div class="flex flex-col items-center">
                <span id="minutes"
                    class="bg-slate-900 text-white rounded-xl w-16 h-16 flex items-center justify-center text-3xl font-extrabold font-mono shadow-sm">00</span>
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-600 mt-2">Min</span>
            </div>
            <span class="text-slate-400 font-extrabold text-3xl pb-6">:</span>
            <div class="flex flex-col items-center">
                <span id="seconds"
                    class="bg-red-600 text-white rounded-xl w-16 h-16 flex items-center justify-center text-3xl font-extrabold font-mono shadow-sm">00</span>
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-600 mt-2">Sec</span>
            </div>
        </div>
    </div>
    <ul class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 py-2">
        @include('frontend.partials.filtered-product-list', [
            'products' => $flashSellProducts,
            'isFlashSell' => true,
        ])
    </ul>
    {{-- See more button --}}

    <div class="flex justify-center mt-6">
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
                        '<span class="text-red-600 bg-red-50 px-4 py-2 rounded-lg font-bold">Flash Sell Ended</span>';
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
