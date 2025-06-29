@php
    $paras = request()->input();
    // Check is brand filtered
    if (isset($paras['brand_slug'])) {
        $brand_slugs = explode(',', $paras['brand_slug']);
        $brandSlugAsso = [];
        foreach ($brand_slugs as $key => $value) {
            $brandSlugAsso[$value] = 1;
        }
    }
    // Check is price filtered
    $from = null;
    $to = null;
    if (isset($paras['price_range'])) {
        $array = explode(',', $paras['price_range']);
        $from = $array[0];
        if (isset($array[1])) {
            $to = $array[1];
        }
    }
    // check if price order is filtered
    $order = 'asc';
    if (isset($paras['order'])) {
        $order = $paras['order'];
    }
@endphp
@extends('frontend.layout.master')
@section('content')
    {{-- Shop Information --}}

    @include('frontend.partials.shop-information', [
        'shop' => $shop,
        'isVisited' => true,
    ])

    {{-- Shop Products --}}
    <div class="flex py-8 gap-10">
        {{-- Filter --}}
        <div>
            {{-- Category --}}
            <div class="flex flex-col min-w-[200px]">
                <a href="{{ route('shop', [...$paras, 'subcategory' => null]) }}"
                    class="text-xl font-semibold py-4 border-b-2 border-slate-200"><i
                        class="fa-solid fa-list text-base"></i>&ensp; All Category</a>
                <ul class="flex flex-col gap-2 mt-4">
                    @foreach ($shopCategories as $key => $category)
                        @php
                            $paras_2 = $paras;
                            $paras_2['subcategory'] = $category->slug ?? '';
                        @endphp
                        @if (isset($activeSub) && isset($category->slug) && $activeSub == $category->slug)
                            <a href="{{ route('shop', $paras_2) }}" class="my-2 text-sky-600"> <i
                                    class="fa-solid fa-play text-sm"></i> {{ $category->name ?? '' }}</a>
                        @else
                            <a href="{{ route('shop', $paras_2) }}" class="my-2">{{ $category->name ?? '' }}</a>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
        {{-- Products By Shop --}}

        <div class="flex-1">
            {{-- Sort By --}}
            <div
                class="justify-between items-center bg-slate-200 p-4 rounded-lg flex text-center mb-7 cursor-pointer w-full">
                <div class="flex gap-5 ">
                    {{-- Type  --}}
                    @foreach (getAllType() as $key => $type)
                        @php
                            $paras_2 = $paras;
                            $paras_2['type'] = $key;
                        @endphp
                        <span data-url="{{ route('shop', $paras_2) }}"
                            class=" type-filter rounded-sm p-2 min-w-[80px] {{ $key == $activeType ? 'bg-sky-600 text-white' : 'bg-white text-black' }}">
                            <a href="{{ route('shop', $paras_2) }}">{{ $type }}</a>
                        </span>
                    @endforeach
                    {{-- Price --}}
                    @php
                        $paras_2 = $paras;
                    @endphp
                </div>
                {{-- pagination --}}
                <div>
                    @php
                        $currentPage = $shopProducts->currentPage();
                        $lastPage = $shopProducts->lastPage();
                    @endphp
                    <span>{{ $currentPage }}/{{ $lastPage }}</span>&emsp;
                    <a href="{{ route('product', [...$paras, 'page' => $currentPage == 1 ? $currentPage : $currentPage - 1]) }}"
                        class="border-2 border-slate-200 bg-slate-300 py-2 px-3 hover:bg-slate-400">
                        < </a>
                            <a href="{{ route('product', [...$paras, 'page' => $currentPage == $lastPage ? $currentPage : $currentPage + 1]) }}"
                                class=" border-2 border-slate-200 bg-slate-300 py-2 px-3 hover:bg-slate-400">
                                > </a>
                </div>
            </div>

            {{-- Filtered Products --}}
            <ul class="grid grid-cols-4 py-5 gap-5">
                @include('frontend.partials.filtered-product-list', ['products' => $shopProducts])
            </ul>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        $(document).ready(function() {
            $(".product").on("click", function() {
                const url = $(this).data("url");
                window.location.replace(url);
            });
            // Filter on Type -----------------------
            $(".type-filter").on("click", function() {
                window.location.replace($(this).data("url"));
            });
            // Filter on Type -----------------------


            // Follow or unfollow shop
            $(".follow-button").click(function(e) {
                e.preventDefault();
                let button = $(this);
                let shopId = "{{ $shop->id }}";
                let isFollowed = @json($isFollowed);
                $.ajax({
                    url: "{{ route('user.shop.follow') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        shop_id: shopId,
                    },
                    success: function(response) {

                        if (response.success) {
                            isFollowed = response.isFollowing; // Update the follow state
                            // Update the button text and icon
                            if (isFollowed) {
                                button.html('<i class="fa-solid fa-heart"></i> Unfollow');
                                $(".followers").text(parseInt($(".followers").text()) + 1);

                            } else {
                                button.html('<i class="fa-regular fa-heart"></i> Follow');
                                $(".followers").text(parseInt($(".followers").text()) - 1);
                            }
                            Toastify({
                                text: response.message,
                                duration: 3000,
                                backgroundColor: "linear-gradient(to right, #22c55e, #16a34a)", // green
                            }).showToast();
                        } else {
                            Toastify({
                                text: response.message,
                                duration: 3000,
                                backgroundColor: "linear-gradient(to right, #ef4444, #dc2626)", // red
                            }).showToast();
                        }
                    },
                    error: function(xhr) {
                        Toastify({
                            text: 'You need to login first',
                            duration: 3000,
                            backgroundColor: "linear-gradient(to right, #ef4444, #dc2626)", // red
                        }).showToast();
                    }
                });
            });
        });
    </script>
@endpush
