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
@endphp

<ul class="flex flex-col gap-3" aria-label="Suggested products">
    @foreach ($products as $p)
        @php
            $onSale = checkSale($p);
            $rating = round((float) $p->averageRating(), 1);
            $reviews = $p->numberOfReviews();
            $shortText = $descriptionText($p->short_description);
            $longText = $descriptionText($p->long_description);
            $url = route('product', ['product' => $p->slug]);
        @endphp

        <li class="flex gap-4 rounded-xl border border-[#D5D9D9] bg-white p-3 text-[#0F1111]">
            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                class="flex h-28 w-28 flex-none items-center justify-center overflow-hidden rounded-lg bg-[#F7F8F8]">
                <img src="{{ asset($p->thumb_image) }}" alt="{{ $p->name }}" loading="lazy"
                    class="h-full w-full object-contain">
            </a>

            <div class="min-w-0 flex-1">
                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                    class="line-clamp-2 text-base font-medium leading-snug hover:text-[#C7511F] hover:underline">
                    {{ $p->name }}
                </a>

                <p class="mt-0.5 text-xs text-[#565959]">
                    {{ $p->brand?->name }}@if ($p->brand && $p->category) · @endif{{ $p->category?->name }}
                </p>

                <div class="mt-1 flex items-center gap-1 text-xs">
                    @if ($reviews > 0)
                        <span class="font-medium">{{ $rating }}</span>
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="fa-star {{ $i <= round($rating) ? 'fa-solid text-[#FFA41C]' : 'fa-regular text-slate-300' }}" aria-hidden="true"></i>
                        @endfor
                        <span class="text-[#007185]">({{ $reviews }})</span>
                    @else
                        <span class="text-[#565959]">No reviews yet</span>
                    @endif
                    <span class="text-[#565959]">· {{ $p->soldCount() }} sold</span>
                </div>

                <div class="mt-1 flex flex-wrap items-baseline gap-x-2">
                    <span class="text-xl font-medium">${{ $onSale ? $p->offer_price : $p->price }}</span>
                    @if ($onSale)
                        <span class="text-xs text-[#565959] line-through">${{ $p->price }}</span>
                        <span class="rounded bg-[#CC0C39] px-1.5 py-0.5 text-xs font-medium text-white">
                            {{ calculateSalePercent($p) }}% off
                        </span>
                    @endif
                </div>

                @if ($shortText !== '')
                    <p class="mt-1 line-clamp-2 text-sm text-[#0F1111]">{{ $shortText }}</p>
                @endif

                <div class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1 text-sm">
                    @if ($longText !== '')
                        <details class="group w-full">
                            <summary class="inline-flex cursor-pointer list-none items-center gap-1 text-[#007185] hover:text-[#C7511F] hover:underline">
                                More details
                                <i class="fa-solid fa-chevron-down text-xs transition group-open:rotate-180" aria-hidden="true"></i>
                            </summary>
                            <p class="mt-2 text-sm leading-relaxed text-[#0F1111]">{{ $longText }}</p>
                        </details>
                    @endif
                    <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center gap-1 rounded-full bg-[#FFD814] px-4 py-1.5 font-medium text-[#0F1111] hover:bg-[#F7CA00]">
                        View product
                    </a>
                </div>
            </div>
        </li>
    @endforeach
</ul>
