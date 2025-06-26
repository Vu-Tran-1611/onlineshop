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
    <div class="flex py-8 gap-10">

        <div>
            {{-- Category --}}
            <div class="flex flex-col min-w-[200px]">
                <a href="" class="text-xl font-semibold py-4 border-b-2 border-slate-200"><i
                        class="fa-solid fa-list text-base"></i>&ensp; All Category</a>
                @php
                    $paras_2 = $paras;
                    $paras_2['category'] = $category->slug;
                    unset($paras_2['subcategory']);
                @endphp
                @if (!$activeSub)
                    <a href="{{ route('product', $paras_2) }}" class="my-2 text-sky-600"> <i
                            class="fa-solid fa-play text-sm"></i> {{ $category->name }}</a>
                @else
                    <a href="{{ route('product', $paras_2) }}" class="my-2 font-semibold">{{ $category->name }}</a>
                @endif
                {{-- Category --}}

                @foreach ($category->subCategories as $sub)
                    @php
                        $paras_2 = $paras;
                        $paras_2['subcategory'] = $sub->slug;
                    @endphp
                    @if ($sub->slug == $activeSub)
                        <a href="{{ route('product', $paras_2) }}" class="my-2 text-sky-600">
                            <i class="fa-solid fa-play text-sm"></i> {{ $sub->name }}

                        </a>
                    @else
                        <a href="{{ route('product', $paras_2) }}" class="my-2">
                            {{ $sub->name }}
                        </a>
                    @endif
                @endforeach
            </div>
            <div class="flex flex-col w-[200px] ">
                <div class="flex justify-between items-center border-b-2">
                    <a href="" class="text-xl font-semibold py-4  border-slate-200">
                        <i class="fa-solid fa-shuffle"></i>&ensp; Filter</a>
                    <a href="{{ route('product', ['category' => $category->slug]) }}"
                        class=" hover:bg-red-700 hover:text-white text-sm text-black filter-btn rounded-lg border-red-600 border-2 py-1   px-2">
                        Reset
                    </a>
                </div>
                <div>
                    <div class="flex flex-col">
                        <form method="GET" action="{{ route('product') }}">
                            {{-- Brand --}}
                            <div>
                                <label class="font-semibold">Brand</label>
                                @foreach ($paras as $key => $p)
                                    @if ($key != 'brand_slug')
                                        <input type="hidden" name="{{ $key }}" value="{{ $p }}" />
                                    @endif
                                @endforeach
                                <input type="hidden" class="brand-slug" name="brand_slug" />
                                @foreach ($brands as $br)
                                    <div class="flex gap-2 items-center my-3">
                                        <input
                                            {{ isset($brandSlugAsso[$br->slug]) && $brandSlugAsso[$br->slug] == 1 ? 'checked' : '' }}
                                            class="brand-filter" type="checkbox" value="{{ $br->slug }}">
                                        <label>{{ $br->name }}</label>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Price --}}
                            <div class="">
                                <label class="font-semibold">Price</label>
                                <div class="flex my-3 gap-2 items-center">
                                    <input value="{{ isset($paras['price_range']) ? $paras['price_range'] : ' ' }}"
                                        type="hidden" name="price_range" class="price-range" />
                                    <input value="{{ $from ? $from : '' }}" placeholder="$ From" type="text"
                                        class="price-from w-[50%]"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                                    <span class="text-2xl">-</span>
                                    <input value="{{ $to ? $to : '' }}" placeholder="$ To" type="text"
                                        class="price-to w-[50%]" oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                                </div>
                            </div>
                            <button
                                class="w-full hover:bg-sky-700 filter-btn my-2 rounded-sm bg-sky-600 text-white py-1 px-4">Filter</button>

                        </form>
                    </div>
                </div>

            </div>
        </div>
        <div class="flex-1">
            <div
                class="justify-between items-center bg-slate-200 p-4 rounded-lg flex text-center mb-7 cursor-pointer w-full">
                <div class="flex gap-5 ">
                    {{-- Type  --}}
                    @foreach (getAllType() as $key => $type)
                        @php
                            $paras_2 = $paras;
                            $paras_2['type'] = $key;
                        @endphp
                        <span data-url="{{ route('product', $paras_2) }}"
                            class=" type-filter rounded-sm p-2 min-w-[80px] {{ $key == $activeType ? 'bg-sky-600 text-white' : 'bg-white text-black' }}">
                            <a href="{{ route('product', $paras_2) }}">{{ $type }}</a>
                        </span>
                    @endforeach
                    {{-- Price --}}
                    @php
                        $paras_2 = $paras;

                    @endphp
                    <div>
                        <select class="price-order-filter">
                            <option {{ $order == 'asc' ? 'selected' : ' ' }}
                                value="{{ route('product', [...$paras_2, 'order' => 'asc']) }}">
                                Price:&ensp; From Low To High
                            </option>
                            <option {{ $order == 'desc' ? 'selected' : ' ' }}
                                value="{{ route('product', [...$paras_2, 'order' => 'desc']) }}">
                                Price:&ensp; From High To Low </option>
                        </select>
                    </div>
                </div>
                {{-- pagination --}}
                <div>
                    @php
                        $currentPage = $products->currentPage();
                        $lastPage = $products->lastPage();
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
            <div class="grid grid-cols-4 gap-3 z-[1] relative">
                @include('frontend.partials.filtered-product-list', ['products' => $products])

            </div>
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

            // Filter on brand -----------------------
            $(".brand-filter").on("click", function(e) {
                const brands = $(".brand-filter:checked");
                const brandsID = [];
                $.each(brands, function(index, value) {
                    brandsID.push($(value).val());
                });
                $brandsIDStr = brandsID.join(",");
                $(".brand-slug").val($brandsIDStr);
            })
            // Filter on brand -----------------------

            // Filter on price ------------------------
            str = $(".price-range").val()
            const priceRange = str.split(",");
            $(".price-from").on("change", function() {
                $price = $(this).val();
                priceRange[0] = $price;
                $(".price-range").val(priceRange);

            });

            $(".price-to").on("change", function() {
                $price = $(this).val();
                priceRange[1] = $price;
                $(".price-range").val(priceRange);
            });
            // Filter on price ------------------------

            // Filter on price order ------------------------

            $(".price-order-filter").on("change", function() {
                const url = $(this).val();
                window.location.replace(url);
            })
            // Filter on price order ------------------------

        });
    </script>
@endpush
