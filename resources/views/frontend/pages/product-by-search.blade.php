@extends('frontend.layout.master')
@section('content')
    <div class="flex py-8 gap-10">
        <div class="w-64">
            <div class="bg-white rounded-lg shadow p-3 mb-6">
                <h2 class="font-semibold text-slate-800 mb-2">
                    Results for the search
                </h2>
                <p class="text-slate-500 text-sm">
                    Showing {{ $products->total() }} product{{ $products->total() == 1 ? '' : 's' }} found.
                </p>
            </div>
            {{--  Filter --}}
            <form method="GET" action="{{ route('product-by-search') }}" class="space-y-6">
                <input type="hidden" name="keyword" value="{{ request('keyword') }}">
                {{-- Search Filter --}}
                {{-- Category Filter --}}
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-slate-700 mb-2">Category</h3>

                    <div class="relative group">
                        <select name="category"
                            class="w-full  appearance-none rounded-2xl border border-slate-300 bg-white px-4 py-2 pr-10 text-sm text-slate-700 shadow-md transition-all duration-300 focus:border-sky-500 focus:ring-2 focus:ring-sky-500 focus:outline-none group-hover:border-sky-400">
                            <option value="" class="font-semibold">All Categories</option>

                            @foreach ($relatedCategories as $cat)
                                <option value="{{ $cat->slug }}" class="font-semibold"
                                    {{ request('category') == $cat->slug ? 'selected' : '' }}>
                                    {{ $cat->name }}

                                </option>

                                @if ($cat->subcategories)
                                    @foreach ($cat->subcategories as $sub)
                                        <option value="{{ $sub->slug }}"
                                            {{ request('category') == $sub->slug ? 'selected' : '' }}>
                                            &nbsp;&nbsp;&nbsp;&nbsp; {{ $sub->name }}
                                        </option>
                                    @endforeach
                                @endif
                            @endforeach
                        </select>


                    </div>
                </div>


                {{-- Brand Filter --}}
                <div class="mb-6 bg-slate-50 border border-slate-200 rounded-2xl p-4 shadow-sm">
                    <h3 class="text-sm font-semibold text-slate-700 mb-3">Brand</h3>

                    <div class="max-h-100 overflow-y-auto space-y-2 pr-1 custom-scroll">
                        @foreach ($brands as $brand)
                            <label class="flex items-center gap-2 p-2 rounded-lg hover:bg-white transition cursor-pointer">
                                <input type="checkbox"
                                    {{ request('brand') && in_array($brand->slug, request('brand')) ? 'checked' : '' }}
                                    class="brand-filter h-4 w-4 text-sky-600 border-gray-300 rounded focus:ring-sky-500"
                                    value="{{ $brand->slug }}" name="brand[]">

                                <span class="text-sm text-slate-700">{{ $brand->name }}</span>
                            </label>
                        @endforeach
                    </div>

                </div>



                {{-- Price Range Filter --}}
                <div class="mb-6 bg-slate-50 border border-slate-200 rounded-2xl p-4 shadow-sm">

                    <h3 class="text-sm font-semibold text-slate-700 mb-2">Price Range</h3>
                    <div class="flex items-center gap-2">
                        <input type="number" min="0" name="from" value="{{ request('from') }}"
                            class="price-from w-20 rounded border px-2 py-1" placeholder="From">
                        <span>-</span>
                        <input type="number" min="0" name="to" value="{{ request('to') }}"
                            class="price-to w-20 rounded border px-2 py-1" placeholder="To">
                    </div>
                </div>

                {{-- Price Order --}}
                <div class="mb-6 bg-slate-50 border border-slate-200 rounded-2xl p-4 shadow-sm">

                    <h3 class="text-sm font-semibold text-slate-700 mb-2">
                        Sort by:
                    </h3>
                    <select id="price-order"
                        class="w-full price-order-filter rounded-lg border border-slate-300 px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-sky-400 transition"
                        name="order">
                        <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>
                            Price: Low to High
                        </option>
                        <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>
                            Price: High to Low
                        </option>
                    </select>
                </div>

                <button type="submit"
                    class="w-full bg-sky-600 hover:bg-sky-700 text-white rounded-lg py-2 font-medium transition">
                    Apply Filters
                </button>
            </form>
        </div>
        {{-- Products By Search --}}

        <div class="flex-1">

            @if (!$products->isEmpty())
                <div
                    class="flex justify-between items-center bg-slate-200 p-4 rounded-lg  text-right mb-7 cursor-pointer w-full">

                    {{-- Search Result Keywords --}}
                    <div>
                        <span class="text-slate-700 font-semibold">Search
                            Results for: </span>
                        <span class="text-sky-600 font-bold italic">{{ request('keyword') ?? request('keyword') }}</span>
                    </div>
                    {{-- pagination --}}
                    <div>

                        {{ $products->withQueryString()->links('vendor.pagination.tailwind') }}
                    </div>



                </div>
            @endif
            {{-- Products --}}
            <div class="grid grid-cols-4 gap-3 z-[1] relative">
                {{-- If No product --}}
                @if ($products->count() == 0)
                    <div
                        class="col-span-4 flex flex-col items-center justify-center py-16 bg-white rounded-xl shadow-inner border border-slate-100">

                        <h2 class="text-2xl font-bold text-slate-700 mb-2">No Products Found</h2>
                        <p class="text-slate-500 mb-4">We couldn't find any products matching your search. Please try again
                        </p>

                    </div>
                @endif
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
        });
    </script>
@endpush
