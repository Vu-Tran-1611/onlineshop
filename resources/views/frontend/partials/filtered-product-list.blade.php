@foreach ($products as $p)
    @if (isset($isFlashSell) || isset($isWishlist))
        @php
            $p = $p->product;
        @endphp
    @endif
    @include('frontend.partials.product-card', ['p' => $p, 'isWishlist' => $isWishlist ?? null])
@endforeach
