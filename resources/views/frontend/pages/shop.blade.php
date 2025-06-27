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
    <div
        class="bg-gradient-to-r from-slate-100 to-white p-7 my-10 flex flex-col md:flex-row items-center gap-8 rounded-2xl shadow-lg border border-sky-100">
        <div class="flex items-center gap-6 ">
            <div class="rounded-full overflow-hidden border-4 border-sky-200 shadow w-[100px] h-[100px]">
                <img src="{{ asset($shop->banner) }}" width="100" height="100" class="object-cover w-[100px] h-[100px]" />
            </div>
            <div class="flex flex-col justify-between border-r-2 pr-7 border-sky-100 h-full">
                <span class="text-2xl font-bold text-sky-700">{{ $shop->name }}</span>
                <span class="text-xs text-slate-400 mt-1 flex items-center gap-1">
                    <i class="fa-solid fa-circle text-green-400 text-[8px]"></i> Online 2 hours ago
                </span>
                <div class="my-4 flex gap-3">
                    <button data-id="{{ $shop->user->id }}" data-banner="{{ $shop->banner }}"
                        data-name="{{ $shop->name }}"
                        class="show-chat-pannel flex items-center gap-2 text-sky-600 border border-sky-600 hover:bg-sky-600 hover:text-white transition px-4 py-2 rounded-lg font-semibold shadow">
                        <i class="fa-brands fa-rocketchat"></i> Chat
                    </button>
                    <button
                        class="flex items-center gap-2 text-sky-600 border border-sky-600 hover:bg-sky-600 hover:text-white transition px-4 py-2 rounded-lg font-semibold shadow">
                        <i class="fa-solid fa-heart"></i> Follow
                    </button>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-x-10 gap-y-4 w-full md:pl-10">
            <div class="flex flex-col items-start">
                <span class="text-slate-500 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-star text-yellow-400"></i> Vote
                </span>
                <span class="text-xl font-bold text-sky-600">94K</span>
            </div>
            <div class="flex flex-col items-start">
                <span class="text-slate-500 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-thumbs-up text-green-400"></i> Feedback Rate
                </span>
                <span class="text-xl font-bold text-sky-600">85%</span>
            </div>
            <div class="flex flex-col items-start">
                <span class="text-slate-500 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-calendar-check text-blue-400"></i> Join
                </span>
                <span class="text-xl font-bold text-sky-600">5 Years ago</span>
            </div>
            <div class="flex flex-col items-start">
                <span class="text-slate-500 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-boxes-stacked text-purple-400"></i> Products
                </span>
                <span class="text-xl font-bold text-sky-600">94</span>
            </div>
            <div class="flex flex-col items-start">
                <span class="text-slate-500 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-clock text-pink-400"></i> Response Time
                </span>
                <span class="text-xl font-bold text-sky-600">In hours</span>
            </div>
            <div class="flex flex-col items-start">
                <span class="text-slate-500 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-users text-indigo-400"></i> Follower
                </span>
                <span class="text-xl font-bold text-sky-600">100K</span>
            </div>
        </div>
    </div>
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
        });
    </script>
@endpush
