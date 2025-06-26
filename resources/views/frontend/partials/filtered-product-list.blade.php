@foreach ($products as $p)
    @if (isset($isFlashSell))
        @php
            $p = $p->product;
        @endphp
    @endif
    @include('frontend.partials.product-card', ['p' => $p])
@endforeach
