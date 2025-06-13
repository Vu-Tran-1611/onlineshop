@extends('frontend.layout.master')
@section('content')
    <div class="hidden product-information" data-description ="{{ $product->short_description }}"
        data-brandid = "{{ $product->brand->id }}" data-vendorid = "{{ $product->shopProfile->id }}"
        data-imageurl = "{{ $product->thumb_image }}">
    </div>
    <div class="py-10  ">

        <div class="rounded-xl relative grid grid-cols-[450px_500px] gap-x-10 bg-white p-5">
            {{-- Loading --}}
            <div role="status"
                class="loading z-[100] absolute w-full h-full hidden items-center justify-center bg-[#eeeeee7d]">
                <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                    viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                        fill="currentColor" />
                    <path
                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                        fill="currentFill" />
                </svg>
                <div class=" text-black">&emsp;Loading...</div>
            </div>
            {{-- Loading --}}
            <!-- Swiper -->
            <div class="">
                <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff" class="swiper mySwiper2">
                    <div class="swiper-wrapper">
                        @foreach ($product->productImageGalleries as $image)
                            <div class="swiper-slide " data-name="{{ $image->name }}">
                                <img data-imageurl ="{{ $image->image }}" src="{{ $image->image }}" />
                            </div>
                        @endforeach

                    </div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
                <div thumbsSlider="" class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        @foreach ($product->productImageGalleries as $image)
                            <div class="swiper-slide">
                                <img src="{{ $image->image }}" />
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- Product Information -->
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $product->name }}</h1>

                <div class="rounded-2xl text-3xl text-sky-600 my-6 flex items-center gap-4 bg-slate-100 p-3 shadow-sm">
                    @if (checkSale($product))
                        {{-- Original Price (crossed out) --}}
                        <span class="text-gray-400 text-2xl line-through">
                            ${{ $product->price }}
                        </span>

                        {{-- Discounted Price --}}
                        <span class="price font-semibold text-sky-700">
                            ${{ $product->offer_price }}
                        </span>

                        {{-- Discount Badge --}}
                        <span class="text-xs bg-sky-500 text-white font-semibold px-2 py-1 rounded-full shadow-sm">
                            -{{ calculateSalePercent($product) }}%
                        </span>
                    @else
                        {{-- Regular Price --}}
                        <span class="price font-semibold text-sky-700">
                            ${{ $product->price }}
                        </span>
                    @endif
                </div>

                <div class="">
                    {{-- Delivery Section --}}
                    <div class="flex my-10">
                        <span class="text-slate-500 min-w-[100px]">Delivery</span>
                        <span>
                            <span class="text-slate-500"><i class="fa-solid fa-truck"></i> Delivering to &ensp;</span> 6320
                            Creekbend Drive<br />
                            <span class="text-slate-500">Shipping Fee &ensp;</span> $0
                        </span>
                    </div>

                    {{-- Variant Selection --}}
                    <div class="relative">
                        {{-- Overlay message for variant not selected --}}
                        <div
                            class="variant-select hidden absolute rounded-lg top-[50%] left-[50%] -translate-y-[50%] -translate-x-[50%] w-[110%] h-[140%] bg-[#ffc3c34d]">
                            <div class="absolute bottom-0 w-full text-center text-red-600">Please select a variant first
                            </div>
                        </div>

                        @foreach ($product->productVariants as $variant)
                            @if ($variant->status == 1)
                                <div class="flex my-10 variant">
                                    {{-- Variant name (e.g., Size, Color) --}}
                                    <span class="text-slate-500 min-w-[100px] capitalize">{{ $variant->name }}</span>

                                    {{-- Variant items --}}
                                    <div class="flex flex-wrap gap-4">
                                        @foreach ($variant->product_variant_item as $item)
                                            @if ($item->status == 1)
                                                <p data-price="{{ $item->price }}"
                                                    data-isswipe="{{ $variant->is_swipe }}"
                                                    data-variantid="{{ $variant->id }}" data-name="{{ $item->name }}"
                                                    data-variantname="{{ $variant->name }}" data-id="{{ $item->id }}"
                                                    class="variant-{{ $variant->id }} variant-item-button border-2 text-sm px-3 py-2 cursor-pointer">
                                                    {{ $item->name }}
                                                </p>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    {{-- Quantity Selector --}}
                    <div class="flex my-10 items-center">
                        <span class="text-slate-500 min-w-[100px] capitalize">Quantity</span>
                        <div class="flex">
                            {{-- Decrease quantity --}}
                            <p class="decrease cursor-pointer border-2 border-slate-200 py-1 px-3">-</p>

                            {{-- Quantity input --}}
                            <input value="1" type="text"
                                class="quantity text-center w-[80px] border-x-0 border-y-2 border-slate-200 focus:ring-0 focus:border-slate-200"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')" />

                            {{-- Increase quantity --}}
                            <p data-max="{{ $product->qty }}"
                                class="increase cursor-pointer border-2 border-slate-200 py-1 px-3">
                                +
                            </p>
                        </div>

                        {{-- Stock message --}}
                        <p class="text-sm text-slate-500">&emsp;{{ $product->qty }} available</p>
                    </div>

                    <div class="flex gap-7">
                        <form>
                            <input type="hidden" name="temp_id" value="{{ $product->id }}" />
                            <input type="hidden" name="id" value="{{ $product->id }}" />
                            <input type="hidden" name="name" value="{{ $product->name }}" />
                            <input type="hidden" name="price"
                                value="{{ checkSale($product) ? $product->offer_price : $product->price }}" />
                            <input type="hidden" name="quantity" value="1" />
                            <input type="hidden" name="attributes" />
                            <div class="flex flex-col md:flex-row gap-3 mt-6">
                                {{-- Add to Cart --}}
                                <button
                                    class="add-to-cart flex items-center justify-center gap-2 text-white bg-sky-600 border-2 border-sky-600 hover:bg-white hover:text-sky-600 transition-all font-semibold text-sx rounded-xl px-3 py-2 shadow-md hover:shadow-lg min-w-[180px]">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                    Add to Cart
                                </button>

                                {{-- Check Out --}}
                                <button
                                    class="flex items-center justify-center gap-2 text-sky-600 bg-white border-2 border-sky-600 hover:bg-sky-600 hover:text-white transition-all font-semibold text-sx rounded-xl px-3 py-2 shadow-md hover:shadow-lg min-w-[180px]">
                                    <i class="fa-solid fa-money-bill-wave"></i>
                                    Check Out
                                </button>

                                {{-- Add to Wishlist --}}
                                <button onclick="event.preventDefault()"
                                    class="add-to-wishlist flex items-center justify-center gap-2 text-pink-600 bg-white border-2 border-pink-500 hover:bg-pink-500 hover:text-white transition-all font-semibold text-sx rounded-xl px-3 py-2 shadow-md hover:shadow-lg min-w-[180px]"
                                    data-id="{{ $product->id }}">
                                    <i class="fa-solid fa-heart"></i>
                                    Add to Wishlist
                                </button>
                            </div>

                        </form>

                    </div>
                    <div class="text-sm my-3">
                        <i class="fa-regular fa-circle-check"></i>&ensp;Free return in 15 days !
                    </div>
                </div>
            </div>
        </div>

        {{-- Shop Information --}}
        <div
            class="bg-gradient-to-r from-slate-100 to-white p-7 my-10 flex flex-col md:flex-row items-center gap-8 rounded-2xl shadow-lg border border-sky-100">
            <div class="flex items-center gap-6 ">
                <div class="rounded-full overflow-hidden border-4 border-sky-200 shadow w-[100px] h-[100px]">
                    <img src="{{ asset($shop->banner) }}" width="100" height="100"
                        class="object-cover w-[100px] h-[100px]" />
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
                            <i class="fa-solid fa-shop"></i> View Shop
                        </button>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-x-10 gap-y-4 w-full md:pl-10">
                <div class="flex flex-col items-start">
                    <span class="text-slate-500 text-sm">Vote</span>
                    <span class="text-xl font-bold text-sky-600">94K</span>
                </div>
                <div class="flex flex-col items-start">
                    <span class="text-slate-500 text-sm">Feedback Rate</span>
                    <span class="text-xl font-bold text-sky-600">85%</span>
                </div>
                <div class="flex flex-col items-start">
                    <span class="text-slate-500 text-sm">Join</span>
                    <span class="text-xl font-bold text-sky-600">5 Years ago</span>
                </div>
                <div class="flex flex-col items-start">
                    <span class="text-slate-500 text-sm">Products</span>
                    <span class="text-xl font-bold text-sky-600">94</span>
                </div>
                <div class="flex flex-col items-start">
                    <span class="text-slate-500 text-sm">Response Time</span>
                    <span class="text-xl font-bold text-sky-600">In hours</span>
                </div>
                <div class="flex flex-col items-start">
                    <span class="text-slate-500 text-sm">Follower</span>
                    <span class="text-xl font-bold text-sky-600">100K</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-5 rounded-xl">
            <div class="bg-white p-5 my-7 gap-5 col-span-9">
                <div class="mb-8">
                    <div
                        class="bg-gradient-to-r to-white from-slate-100 p-4 font-semibold text-lg rounded-lg shadow-sm flex items-center gap-2">
                        <i class="fa-solid fa-info-circle text-sky-500"></i>
                        <span class="tracking-wide text-sky-700">PRODUCT DETAIL</span>
                    </div>
                    <div class="my-10 leading-10 p-6 bg-white rounded-xl shadow flex flex-col gap-6">
                        <div class="flex gap-10 items-center">
                            <span class="text-slate-500 min-w-[120px] font-medium flex items-center gap-2">
                                <i class="fa-solid fa-layer-group text-sky-700"></i> Category
                            </span>
                            <span class="text-slate-700 font-semibold">
                                {{ $product->category->name }}
                                @if (!empty($product->subCategory->name))
                                    <span class="text-slate-400"> - {{ $product->subCategory->name }}</span>
                                @endif
                            </span>
                        </div>
                        <div class="flex gap-10 items-center">
                            <span class="text-slate-500 min-w-[120px] font-medium flex items-center gap-2">
                                <i class="fa-solid fa-boxes-stacked text-sky-700"></i> Quantity
                            </span>
                            <span class="text-slate-700 font-semibold">{{ $product->qty }}</span>
                        </div>
                        <div class="flex gap-10 items-center">
                            <span class="text-slate-500 min-w-[120px] font-medium flex items-center gap-2">
                                <i class="fa-solid fa-certificate text-sky-700"></i> Brand
                            </span>
                            <span class="text-slate-700 font-semibold">{{ $product->brand->name }}</span>
                        </div>
                    </div>
                </div>
                <div>
                    <div
                        class="bg-gradient-to-r to-white from-slate-100 p-4 font-semibold text-lg rounded-lg shadow-sm flex items-center gap-2">
                        <i class="fa-solid fa-info-circle text-sky-500"></i>
                        <span class="tracking-wide text-sky-700">PRODUCT DESCRIPTION</span>
                    </div>
                    <div class="my-10 leading-10 p-3">
                        <div class="gap-16">
                            {!! $product->short_description !!}
                        </div>
                        <div class="gap-16">
                            {!! $product->long_description !!}
                        </div>

                    </div>
                </div>
            </div>
            <div class=" bg-white p-5 my-7 gap-5 col-span-3">
                <h2 class="text-slate-500">Top picks from Shop</h2>

                <div class="flex flex-col gap-4 pt-5">
                    @foreach ($productsBelongsToShop as $t)
                        <li data-url="{{ route('product', ['product' => $t->slug]) }}"
                            class= " product cursor-pointer shadow-lg relative hover:shadow-lg
                             hover:shadow-slate-400 hover:-translate-y-1 transition-all  
                             flex flex-col justify-between  leading-6 rounded-lg border
                              border-slate-100 overflow-hidden">
                            <img class="min-h-[100px] w-full" src="{{ asset($t->thumb_image) }}" />
                            <div class="absolute w-full text-xs flex justify-between">
                                <span class="bg-sky-600 rounded-sm text-white  p-1 ">
                                    {{ getProductType($t) }}
                                </span>
                                @if (checkSale($t))
                                    <span class="bg-sky-700 rounded-sm text-white p-1 ">
                                        {{ calculateSalePercent($t) . '%' }}
                                    </span>
                                @endif
                            </div>
                            <div class=" p-2">
                                <h1>{{ $t->name }}</h1>
                                <p class="flex justify-between items-center mt-3">
                                    <span class="text-orange-500 font-bold">${{ $t->price }}</span>
                                    <span class="text-sm ">30 Sold</span>
                                </p>
                            </div>

                        </li>
                    @endforeach
                </div>
            </div>


        </div>
        <div>
            <div class="flex items-center gap-3 my-8">
                <span class="block w-10 h-1 bg-gradient-to-r from-sky-400 to-sky-700 rounded"></span>
                <h1 class="text-slate-700 uppercase text-2xl font-bold tracking-wide">From the same shop</h1>
                <span class="block flex-1 h-1 bg-gradient-to-l from-sky-400 to-sky-700 rounded"></span>
            </div>
            <div class="grid grid-cols-5 gap-4 pt-5">
                @foreach ($productsBelongsToShop as $p)
                    <li data-url="{{ route('product', ['product' => $p->slug]) }}"
                        class=" cursor-pointer group bg-white rounded-xl overflow-hidden shadow-lg relative hover:shadow-2xl hover:-translate-y-2 transition-all hover:border-sky-600 flex flex-col justify-between leading-6 border border-slate-100">
                        <div class="relative">
                            <img class="min-h-[180px] w-full object-cover group-hover:scale-105 transition-transform duration-300"
                                src="{{ asset($p->thumb_image) }}" />
                            <div class="absolute top-2 left-2 flex gap-2 w-full pr-2 justify-between">
                                <span class="bg-sky-600 rounded-full text-white px-3 py-1 text-xs shadow">
                                    {{ getProductType($p) }}
                                </span>
                                @if (checkSale($p))
                                    <span
                                        class="bg-pink-600 rounded-full text-white px-3 py-1 text-xs shadow animate-bounce">
                                        -{{ calculateSalePercent($p) . '%' }}
                                    </span>
                                @endif
                            </div>
                            <div class="absolute top-2 right-2">
                                <button
                                    class="bg-white/80 hover:bg-sky-100 rounded-full p-2 shadow transition add-to-wishlist"
                                    data-id="{{ $p->id }}">
                                    <i class="fa-regular fa-heart text-sky-600"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-4 flex flex-col gap-2">
                            <h1 class="font-semibold text-base text-slate-800 group-hover:text-sky-700 truncate"
                                title="{{ $p->name }}">{{ $p->name }}</h1>
                            <p class="font-semibold text-base text-slate-800 group-hover:text-sky-700 truncate">
                                {!! $p->short_description !!}</p>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-orange-700 font-bold text-lg">${{ $p->price }}</span>
                                <span class="text-xs text-slate-500 flex items-center gap-1">
                                    <i class="fa-solid fa-fire text-orange-700"></i> 30 Sold
                                </span>
                            </div>
                            <div class="flex gap-1 mt-1">
                                @for ($i = 0; $i < 5; $i++)
                                    <i
                                        class="fa-star {{ $i < 4 ? 'fa-solid text-yellow-400' : 'fa-regular text-slate-300' }}"></i>
                                @endfor
                                <span class="text-xs text-slate-400 ml-1">(120)</span>
                            </div>
                            <button data-url="{{ route('product', ['product' => $p->slug]) }}"
                                class="product mt-3 w-full bg-sky-600 hover:bg-sky-700 text-white rounded-lg py-1.5 font-medium transition-all opacity-0 group-hover:opacity-100 group-hover:translate-y-0 translate-y-2 duration-200">
                                View Details
                            </button>
                        </div>
                    </li>
                @endforeach
            </div>
        </div>

        <div>
            <div class="flex items-center gap-3 my-8">
                <span class="block w-10 h-1 bg-gradient-to-r from-sky-400 to-sky-700 rounded"></span>
                <h1 class="text-slate-700 uppercase text-2xl font-bold tracking-wide">You may also like</h1>
                <span class="block flex-1 h-1 bg-gradient-to-l from-sky-400 to-sky-700 rounded"></span>
            </div>
            <div class="grid gap-4 pt-5 grid-cols-5">
                @foreach ($productsBelongsToSameCategory as $p)
                    <li data-url="{{ route('product', ['product' => $p->slug]) }}"
                        class=" cursor-pointer group bg-white rounded-xl overflow-hidden shadow-lg relative hover:shadow-2xl hover:-translate-y-2 transition-all hover:border-sky-600 flex flex-col justify-between leading-6 border border-slate-100">
                        <div class="relative">
                            <img class="min-h-[180px] w-full object-cover group-hover:scale-105 transition-transform duration-300"
                                src="{{ asset($p->thumb_image) }}" />
                            <div class="absolute top-2 left-2 flex gap-2 w-full pr-2 justify-between">
                                <span class="bg-sky-600 rounded-full text-white px-3 py-1 text-xs shadow">
                                    {{ getProductType($p) }}
                                </span>
                                @if (checkSale($p))
                                    <span
                                        class="bg-pink-600 rounded-full text-white px-3 py-1 text-xs shadow animate-bounce">
                                        -{{ calculateSalePercent($p) . '%' }}
                                    </span>
                                @endif
                            </div>
                            <div class="absolute top-2 right-2">
                                <button
                                    class="bg-white/80 hover:bg-sky-100 rounded-full p-2 shadow transition add-to-wishlist"
                                    data-id="{{ $p->id }}">
                                    <i class="fa-regular fa-heart text-sky-600"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-4 flex flex-col gap-2">
                            <h1 class="font-semibold text-base text-slate-800 group-hover:text-sky-700 truncate"
                                title="{{ $p->name }}">{{ $p->name }}</h1>
                            <p class="font-semibold text-base text-slate-800 group-hover:text-sky-700 truncate">
                                {!! $p->short_description !!}</p>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-orange-700 font-bold text-lg">${{ $p->price }}</span>
                                <span class="text-xs text-slate-500 flex items-center gap-1">
                                    <i class="fa-solid fa-fire text-orange-700"></i> 30 Sold
                                </span>
                            </div>
                            <div class="flex gap-1 mt-1">
                                @for ($i = 0; $i < 5; $i++)
                                    <i
                                        class="fa-star {{ $i < 4 ? 'fa-solid text-yellow-400' : 'fa-regular text-slate-300' }}"></i>
                                @endfor
                                <span class="text-xs text-slate-400 ml-1">(120)</span>
                            </div>
                            <button data-url="{{ route('product', ['product' => $p->slug]) }}"
                                class="product mt-3 w-full bg-sky-600 hover:bg-sky-700 text-white rounded-lg py-1.5 font-medium transition-all opacity-0 group-hover:opacity-100 group-hover:translate-y-0 translate-y-2 duration-200">
                                View Details
                            </button>
                        </div>
                    </li>
                @endforeach
            </div>
        </div>
    @endsection



    @push('styles')
        <link rel="stylesheet" href="{{ asset('frontend/product-detail/slider.css') }}">
    @endpush
    @push('scripts')
        <!-- Swiper JS
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        -->
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

        <!-- Initialize Swiper -->
        <script>
            $(document).ready(function() {
                // Add to Wishlist button handler

                $(".add-to-wishlist").on("click", function(e) {
                    e.preventDefault();
                    const productId = $(this).data("id");
                    $.ajax({
                        type: "POST",
                        url: "{{ route('user.profile.wishlist.add-to-wishlist') }}",
                        data: {
                            product_id: productId
                        },
                        dataType: "json",
                        success: function(response, textStatus, jqXHR) {
                            console.log(response);
                            // Not in wishlist
                            if (response.success == true) {
                                Toastify({
                                    text: response.message,
                                    duration: 3000,
                                    backgroundColor: "linear-gradient(to right, #22c55e, #16a34a)", // green

                                }).showToast();
                            }
                            // Already in wishlist
                            else {
                                Toastify({
                                    text: response.message,
                                    backgroundColor: "linear-gradient(to right, #ef4444, #b91c1c)", // red/danger
                                }).showToast();
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.table(jqXHR)
                        }
                    });
                });

                function init() {
                    let allVarNames = [];
                    const imageURL = $(".product-information").data("imageurl");
                    const brandID = $(".product-information").data("brandid");
                    const vendorID = $(".product-information").data("vendorid");
                    const productDescription = $(".product-information").data("description");
                    const id = $('input[name="temp_id"]').val();
                    allVarNames.push({
                        imageURL: imageURL
                    }, {
                        brand_id: brandID
                    }, {
                        product_id: id
                    }, {
                        vendor_id: vendorID
                    }, {
                        product_description: productDescription
                    });
                    const allVarNamesJson = JSON.stringify(allVarNames);
                    $('input[name="attributes"]').val(allVarNamesJson);
                }
                init()
                var swiper = new Swiper(".mySwiper", {
                    spaceBetween: 10,
                    slidesPerView: 4,
                    freeMode: true,
                    watchSlidesProgress: true,
                });
                var swiper2 = new Swiper(".mySwiper2", {
                    spaceBetween: 10,
                    navigation: {
                        nextEl: ".swiper-button-next",
                        prevEl: ".swiper-button-prev",
                    },
                    thumbs: {
                        swiper: swiper,
                    },
                });
                // Move slide 
                function moveToSlide(name) {
                    var slides = $('.swiper-slide');
                    slides.each(function(index) {
                        if ($(this).data('name').toLowerCase() === name.toLowerCase()) {
                            swiper2.slideTo(index);
                            return false; // Exit the loop once the slide is found
                        }
                    });
                }
                // Variant item handle --------------------------
                $(".variant-item-button").on("click", function() {
                    const variantId = $(this).data("variantid");
                    let allVarNames = [];
                    const id = $('input[name="temp_id"]').val();
                    // Move slide and handle input image, brand_id, product_id
                    if ($(this).data("isswipe")) {
                        const name = $(this).data("name");
                        moveToSlide(name);
                    }
                    var activeIndex = swiper2.activeIndex;
                    var activeSlide = swiper2.slides[activeIndex];
                    var activeImage = $(activeSlide).find('img');
                    const imageURL = $(activeImage).data("imageurl");
                    var brandID = $(".product-information").data("brandid");
                    var vendorID = $(".product-information").data("vendorid");
                    var productDescription = $(".product-information").data("description");

                    allVarNames.push({
                        imageURL: imageURL
                    }, {
                        brand_id: brandID
                    }, {
                        product_id: id
                    }, {
                        vendor_id: vendorID
                    }, {
                        product_description: productDescription
                    });
                    // Add variant Price
                    if ($(this).data("price") > 0) {
                        let price = parseInt($(".price").html());
                        price += parseInt((this).data("price"));
                        $(".price").html(price);
                        $("input[name='price']").val(price);
                    }


                    // Handle active
                    $(".variant-" + variantId).removeClass("act border-sky-600");
                    $(this).addClass("act border-sky-600");

                    // Handle input id and input variants 


                    let newid = id;
                    $(".variant-item-button.act").each(function(i, v) {
                        newid += $(v).data("id");
                        $('input[name="id"]').val(newid);

                        const variantName = $(v).data('variantname')
                        let variantPair = {}
                        variantPair[variantName] = $(v).data("name")
                        allVarNames.push(variantPair)
                    });
                    console.log(newid);
                    const allVarNamesJson = JSON.stringify(allVarNames);
                    $('input[name="attributes"]').val(allVarNamesJson);
                });
                // Variant item handle --------------------------

                // Quantity item handle  ---------------------------
                $(".increase").on("click", function() {
                    let qty = parseInt($(".quantity").val());
                    let max = $(this).data("max");
                    console.log(max);
                    if (qty + 1 > max) return;
                    qty = qty + 1;
                    $(".quantity").val(qty);
                    $("input[name='quantity']").val(qty);
                })
                $(".decrease").on("click", function() {
                    let qty = parseInt($(".quantity").val());
                    if (qty <= 1) return;
                    qty = qty - 1;
                    $(".quantity").val(qty);
                    $("input[name='quantity']").val(qty);

                })
                $(".quantity").on("change", function() {
                    const max = $(".increase").data("max");
                    let qty = $(this).val();
                    console.log(qty);
                    if (qty <= 0) qty = 1;
                    else if (qty > max) qty = max;
                    $(this).val(qty);
                    $("input[name='quantity']").val(qty);

                })
                // Quantity item handle  ---------------------------

                // Add to cart 
                $(".add-to-cart").on("click", function(e) {
                    e.preventDefault();

                    // Check all variant selected
                    let flag = true;
                    $(".variant").each(function(i, v) {
                        if ($(v).find(".act").length == 0) flag = false;
                    })
                    if (flag == true) {
                        const data = $(this).closest("form").serialize();
                        // Send form by ajax 
                        $.ajax({
                            type: "POST",
                            url: "{{ route('user.add-to-cart') }}",
                            data: data,
                            dataType: "JSON",
                            beforeSend: function() {
                                $(".loading").removeClass("hidden");
                                $(".loading").addClass("flex");
                            },
                            success: function(response) {
                                if (response.status == 'success') {
                                    $(".loading").addClass("hidden");
                                    $(".loading").removeClass("flex");

                                    Swal.fire({
                                        icon: "success",
                                        text: response.message,
                                    });
                                    $(".cart-qty").html(response.cart);
                                    $(".cart-show").removeClass("hidden");
                                    $(".cart-hidden").addClass("hidden");
                                    // Append new item to mini cart 
                                    if (response.isShowInMiniCart) {
                                        const li = `
                                <li class="flex hover:bg-slate-100 p-2 justify-between leading-[80px] items-center">
                                    <span class="flex gap-2 items-center">
                                        <span><img width="50" src="${response.variants['imageURL']}" /></span>
                                        <span>${ response.name }</span>
                                    </span>
                                    <span class="text-sky-600">$${response.price }</span>
                                </li>
                        `
                                        $(".cart-mini").append(li);
                                    }
                                    $(".view-cart-button").removeClass("hidden").addClass("block");
                                    $(".empty-cart-message").removeClass("block").addClass(
                                        "hidden");

                                }
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                window.location.replace("/login");
                            }
                        });
                    } else {
                        $(".variant-select").removeClass("hidden");
                    }
                });
                $(".variant-select").on("click", function() {
                    $(this).addClass("hidden");
                })

                $(".product").on("click", function() {
                    const url = $(this).data("url");
                    window.location.replace(url);
                });
            });
        </script>
    @endpush
