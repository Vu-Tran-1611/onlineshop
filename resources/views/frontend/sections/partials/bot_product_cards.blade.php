{{-- Product cards for the shopping assistant --}}
@php
    // Return description text without the leading bold title
    $descriptionText = function (?string $html): string {
        $html = (string) $html;
        if (preg_match_all('/<p[^>]*>(.*?)<\/p>/is', $html, $matches) && !empty($matches[1])) {
            $html = implode(' ', $matches[1]);
        }

        return \Illuminate\Support\Str::squish(strip_tags(str_replace('<', ' <', $html)));
    };

    // Format a price with thousands separators
    $money = fn ($value) => '$' . number_format((float) $value, fmod((float) $value, 1) ? 2 : 0);
@endphp

<ul class="flex flex-col gap-4" aria-label="Suggested products">
    @foreach ($products as $p)
        @php
            $onSale = checkSale($p);
            $rating = round((float) $p->averageRating(), 1);
            $reviews = $p->numberOfReviews();
            $type = getProductType($p);
            $shortText = $descriptionText($p->short_description);
            $longText = $descriptionText($p->long_description);
            $url = route('product', ['product' => $p->slug]);
        @endphp

        <li class="flex gap-5 rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:border-sky-600 hover:shadow-lg">
            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                class="relative flex h-36 w-36 flex-none items-center justify-center overflow-hidden rounded-lg bg-slate-50 sm:h-56 sm:w-56">
                <img src="{{ asset($p->thumb_image) }}" alt="{{ $p->name }}" loading="lazy"
                    class="h-full w-full object-contain transition-transform duration-300 hover:scale-105">
                @if ($type)
                    <span class="absolute left-2 top-2 rounded-full bg-sky-600 px-3 py-1 text-xs text-white shadow">{{ $type }}</span>
                @endif
            </a>

            <div class="flex min-w-0 flex-1 flex-col">
                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                    class="line-clamp-2 text-lg font-semibold leading-snug text-slate-800 hover:text-sky-700">
                    {{ $p->name }}
                </a>

                <p class="mt-0.5 text-sm text-slate-500">
                    {{ $p->brand?->name }}@if ($p->brand && $p->category) · @endif{{ $p->category?->name }}
                </p>

                <div class="mt-1 flex items-center gap-1 text-sm">
                    @if ($reviews > 0)
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="fa-star {{ $i <= round($rating) ? 'fa-solid text-yellow-400' : 'fa-regular text-slate-300' }}" aria-hidden="true"></i>
                        @endfor
                        <span class="ml-1 text-slate-600">{{ $rating }}</span>
                        <span class="text-slate-400">({{ $reviews }})</span>
                    @else
                        <span class="text-slate-400">No reviews yet</span>
                    @endif
                </div>

                <div class="mt-2 flex flex-wrap items-baseline gap-x-2">
                    <span class="text-2xl font-bold text-orange-700">{{ $money($onSale ? $p->offer_price : $p->price) }}</span>
                    @if ($onSale)
                        <span class="text-sm font-semibold text-gray-400 line-through">{{ $money($p->price) }}</span>
                        <span class="rounded-full bg-orange-100 px-2 py-0.5 text-xs font-semibold text-orange-700">
                            -{{ calculateSalePercent($p) }}%
                        </span>
                    @endif
                </div>

                @if ($shortText !== '')
                    <p class="mt-2 text-sm font-medium text-slate-700">{{ $shortText }}</p>
                @endif

                @if ($longText !== '')
                    <p class="mt-3 border-t border-slate-100 pt-3 text-sm leading-relaxed text-slate-500">{{ $longText }}</p>
                @endif

                <div class="mt-4">
                    <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center rounded-lg bg-sky-600 px-4 py-1.5 font-medium text-white transition-all hover:bg-sky-700">
                        View details
                    </a>
                </div>
            </div>
        </li>
    @endforeach
</ul>
