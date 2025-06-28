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
                <h1 class="text-3xl font-semibold text-gray-900 mb-6 tracking-tight leading-snug">
                    {{ $product->name }}
                </h1>

                <div class="flex items-center gap-4 p-5 rounded-xl  text-3xl text-sky-700">
                    @if (checkSale($product))
                        {{-- Original Price (crossed out) --}}
                        <span class="text-gray-400 text-2xl line-through">
                            ${{ $product->price }}
                        </span>

                        {{-- Discounted Price --}}
                        <span class="font-bold text-sky-700">
                            ${{ $product->offer_price }}
                        </span>

                        {{-- Discount Badge --}}
                        <span class="text-xs font-bold text-white bg-sky-600 px-3 py-1 rounded-full shadow">
                            -{{ calculateSalePercent($product) }}%
                        </span>
                    @else
                        {{-- Regular Price --}}
                        <span class="font-bold text-sky-700">
                            ${{ $product->price }}
                        </span>
                    @endif
                </div>


                <div class="">


                    {{-- Guarantee Section --}}
                    <div class="flex my-6 items-start">
                        <span class="text-slate-500 min-w-[100px]">Guarantee</span>
                        <span class="flex-1">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-shield-halved text-green-500 text-xl"></i>
                                <span class="font-semibold text-slate-700">Buy now, worry-free!</span>
                            </div>
                            <div class="text-slate-600 text-sm mt-1">
                                Trust in us – your satisfaction is our priority. Enjoy <span
                                    class="font-bold text-sky-600">free
                                    return within 15 days</span> if you're not happy with your purchase.
                            </div>
                            <details class="mt-2 group">
                                <summary class="cursor-pointer text-sky-600 hover:underline flex items-center gap-1">
                                    <i class="fa-solid fa-circle-info"></i>
                                    <span>Return & Guarantee Policy Details</span>
                                </summary>
                                <div class="mt-2 text-slate-500 text-sm bg-slate-50 rounded-lg p-3 border border-sky-100">
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>Free returns accepted within 15 days of delivery.</li>
                                        <li>Product must be unused and in original packaging.</li>
                                        <li>Contact our support for a hassle-free return process.</li>
                                        <li>Full refund or replacement available as per your preference.</li>
                                    </ul>
                                </div>
                            </details>
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
                            <i class="fa-solid fa-shop"></i>
                            <a href="{{ route('shop', ['shop' => $shop->slug]) }}">
                                Visit
                            </a>
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
                {{-- Product detail --}}
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
                {{-- Product Description --}}
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
            {{-- Top Pick from shop --}}
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
        {{-- Review Section --}}
        <div class="my-12 reviews-section">
            <div class="flex items-center gap-3 mb-6">
                <span class="block w-10 h-1 bg-gradient-to-r from-pink-400 to-sky-700 rounded"></span>
                <h1 class="text-slate-700 uppercase text-2xl font-bold tracking-wide">Customer Reviews</h1>
                <span class="block flex-1 h-1 bg-gradient-to-l from-pink-400 to-sky-700 rounded"></span>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-8 start">
                {{-- Overall Rating --}}
                <div class="flex items-center gap-6 mb-8">
                    <div class="flex items-center gap-2">
                        <span class="text-4xl font-bold text-yellow-400">{{ $averageRating }}</span>
                        <div class="flex gap-1">
                            @for ($i = 0; $i < 5; $i++)
                                <i
                                    class="fa-star {{ $i < $averageRating ? 'fa-solid text-yellow-400' : 'fa-regular text-slate-300' }}"></i>
                            @endfor
                        </div>
                    </div>
                    <span class="text-slate-500 text-lg">({{ $numberOfReviews }} Reviews)</span>
                </div>
                <div class="divide-y divide-slate-100 ">
                    {{-- No review --}}
                    @if ($otherReviews->isEmpty() && !$userReview)
                        <div class="py-8 text-center text-slate-400 text-lg">
                            <i class="fa-regular fa-face-smile-beam text-3xl mb-2"></i>
                            <div>No reviews yet. Be the first to review this product!</div>
                        </div>
                    @endif
                    @if (Auth::check() && $userReview)
                        {{-- Authenticated User Review --}}
                        <div class="py-6 flex gap-5">
                            <div class="flex-shrink-0">
                                <img src="{{ asset($userReview->user->image) }}" alt="User"
                                    class="w-14 h-14 rounded-full border-2 border-sky-200 shadow">
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-slate-800">{{ $userReview->user->username }}</span>
                                    <span
                                        class="text-xs text-slate-400">{{ $userReview->created_at->format('M d, Y') }}</span>
                                </div>
                                <div class="flex gap-1 my-1">
                                    @php
                                        $rating = intval($userReview->rating);
                                    @endphp
                                    @for ($i = 0; $i < 5; $i++)
                                        <i
                                            class="fa-star {{ $i < $rating ? 'fa-solid text-yellow-400' : 'fa-regular text-slate-300' }}"></i>
                                    @endfor
                                </div>
                                <p class="text-slate-700 mt-2">
                                    {{ $userReview->review }}
                                </p>
                                <div class="flex gap-2 mt-3">
                                    @if ($userReview->images)
                                        @foreach ($userReview->images as $image)
                                            <img src="{{ asset($image) }}"
                                                class="w-20 h-20 object-cover rounded-lg border cursor-pointer review-image"
                                                alt="Review Image" tabindex="0" data-full="{{ asset($image) }}" />
                                        @endforeach
                                    @endif

                                </div>
                            </div>
                        </div>
                    @endif
                    @foreach ($otherReviews as $otherReview)
                        <div class="py-6 flex gap-5">
                            <div class="flex-shrink-0">
                                <img src="{{ asset($otherReview->user->image) }}" alt="User"
                                    class="w-14 h-14 rounded-full border-2 border-sky-200 shadow">
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-slate-800">{{ $otherReview->user->username }}</span>
                                    <span
                                        class="text-xs text-slate-400">{{ $otherReview->created_at->format('M d, Y') }}</span>
                                </div>
                                <div class="flex gap-1 my-1">
                                    @php
                                        $rating = intval($otherReview->rating);
                                    @endphp
                                    @for ($i = 0; $i < 5; $i++)
                                        <i
                                            class="fa-star {{ $i < $rating ? 'fa-solid text-yellow-400' : 'fa-regular text-slate-300' }}"></i>
                                    @endfor
                                </div>
                                <p class="text-slate-700 mt-2">
                                    {{ $otherReview->review }}
                                </p>
                                <div class="flex gap-2 mt-3">
                                    @if ($otherReview->images)
                                        @foreach ($otherReview->images as $image)
                                            <img src="{{ asset($image) }}"
                                                class="w-20 h-20 object-cover rounded-lg border cursor-pointer review-image"
                                                alt="Review Image" tabindex="0" data-full="{{ asset($image) }}" />
                                        @endforeach
                                    @endif

                                </div>
                            </div>
                        </div>
                    @endforeach
                    {!! $otherReviews->withQueryString()->links() !!}




                    <!-- Modal for enlarged image -->
                    <div class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 review-image-modal">
                        <div class="relative">
                            <img src="" alt="Enlarged Review Image"
                                class="max-w-[100vw] max-h-[100vh] rounded-lg shadow-2xl modal-img" />
                            <button type="button"
                                class="absolute top-2 right-2 text-white text-2xl font-bold close-modal"
                                aria-label="Close">&times;</button>
                        </div>
                    </div>
                </div>

                {{-- Write a Review --}}
                <div class="mt-10 border-t pt-6">
                    <h3 class="text-lg font-semibold text-slate-700 mb-3">Write a Review</h3>
                    <form class="space-y-4 submit-review">
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        @csrf
                        <div class="flex gap-2 items-center">
                            <span class="text-slate-600">Your Rating:</span>
                            <div class="flex gap-1 rating-stars" data-selected="0">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fa-star fa-regular text-yellow-400 cursor-pointer hover:scale-110 transition"
                                        data-value="{{ $i }}"></i>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" value="0">
                        </div>

                        <textarea name="review" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-sky-400 resize-none"
                            rows="3" placeholder="Share your thoughts about the product..."></textarea>
                        {{-- Icon Picker --}}
                        <div class="flex gap-2 items-center my-2">
                            <button type="button" class="icon-picker-btn text-xl p-2 rounded hover:bg-sky-100"
                                title="😊"><span>😊</span></button>
                            <button type="button" class="icon-picker-btn text-xl p-2 rounded hover:bg-sky-100"
                                title="😍"><span>😍</span></button>
                            <button type="button" class="icon-picker-btn text-xl p-2 rounded hover:bg-sky-100"
                                title="👍"><span>👍</span></button>
                            <button type="button" class="icon-picker-btn text-xl p-2 rounded hover:bg-sky-100"
                                title="🎉"><span>🎉</span></button>
                            <button type="button" class="icon-picker-btn text-xl p-2 rounded hover:bg-sky-100"
                                title="😢"><span>😢</span></button>
                        </div>
                        <div class="flex gap-3 items-center">
                            <input type="file" name="images[]" accept="image/*"
                                class="block text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100"
                                multiple>
                            <button type="submit"
                                class= "bg-sky-600 hover:bg-sky-700 text-white font-semibold px-6 py-2 rounded-lg shadow transition">Submit
                                Review</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        {{-- From the same shop --}}
        <div>
            <div class="flex items-center gap-3 my-8">
                <span class="block w-10 h-1 bg-gradient-to-r from-sky-400 to-sky-700 rounded"></span>
                <h1 class="text-slate-700 uppercase text-2xl font-bold tracking-wide">From the same shop</h1>
                <span class="block flex-1 h-1 bg-gradient-to-l from-sky-400 to-sky-700 rounded"></span>
            </div>
            <div class="grid grid-cols-5 gap-4 pt-5">
                @include('frontend.partials.filtered-product-list', ['products' => $productsBelongsToShop])
            </div>
        </div>

        {{-- You may also like   --}}
        <div>
            <div class="flex items-center gap-3 my-8">
                <span class="block w-10 h-1 bg-gradient-to-r from-sky-400 to-sky-700 rounded"></span>
                <h1 class="text-slate-700 uppercase text-2xl font-bold tracking-wide">You may also like</h1>
                <span class="block flex-1 h-1 bg-gradient-to-l from-sky-400 to-sky-700 rounded"></span>
            </div>
            <div class="grid gap-4 pt-5 grid-cols-5">
                @include('frontend.partials.filtered-product-list', [
                    'products' => $productsBelongsToSameCategory,
                ])
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
                // Submit Review
                $(".submit-review").on("submit", function(e) {
                    e.preventDefault();
                    let formData = new FormData(this);
                    console.log(formData);
                    $.ajax({
                        type: "POST",
                        url: "{{ route('user.review.create') }}",
                        data: formData,
                        dataType: "json",
                        processData: false,
                        contentType: false,
                        success: function(response, textStatus, jqXHR) {
                            console.log(response);
                            // Not Review Yet
                            if (jqXHR.status == 200) {
                                // Reset formdata
                                $(".submit-review")[0].reset();
                                $(".rating-stars").data("selected", 0);
                                location.reload();
                            }
                            // Already Reviewed
                            else {
                                Toastify({
                                    text: response.message,
                                    backgroundColor: "linear-gradient(to right, #ef4444, #b91c1c)", // red/danger
                                }).showToast();
                            }
                        },
                        error: function(response, textStatus, jqXHR) {
                            Toastify({
                                text: "You need to login first",
                                backgroundColor: "linear-gradient(to right, #f59e42, #fbbf24)", // orange/yellow
                            }).showToast();
                        }
                    });
                });

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
                            Toastify({
                                text: "You need to login first",
                                backgroundColor: "linear-gradient(to right, #f59e42, #fbbf24)", // orange/yellow
                            }).showToast();
                        }
                    });
                });

                function init() {
                    let allVarNames = [];
                    const imageURL = $(".product-information").data("imageurl");
                    const brandID = $(".product-information").data("brandid");
                    const vendorID = $(".product-information").data("vendorid");
                    // const productDescription = $(".product-information").data("description");
                    const id = $('input[name="temp_id"]').val();
                    allVarNames.push({
                        imageURL: imageURL
                    }, {
                        brand_id: brandID
                    }, {
                        product_id: id
                    }, {
                        vendor_id: vendorID
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
                    // var productDescription = $(".product-information").data("description");

                    allVarNames.push({
                        imageURL: imageURL
                    }, {
                        brand_id: brandID
                    }, {
                        product_id: id
                    }, {
                        vendor_id: vendorID
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
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.icon-picker-btn').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        const icon = this.querySelector('span').textContent;
                        const textarea = this.closest('form').querySelector('textarea');
                        textarea.value += icon;
                        textarea.focus();
                    });
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const stars = document.querySelectorAll('.rating-stars i');
                const ratingInput = document.querySelector('input[name="rating"]');
                stars.forEach(function(star, idx) {
                    star.addEventListener('mouseenter', function() {
                        for (let i = 0; i <= idx; i++) {
                            stars[i].classList.remove('fa-regular');
                            stars[i].classList.add('fa-solid');
                        }
                        for (let i = idx + 1; i < stars.length; i++) {
                            stars[i].classList.remove('fa-solid');
                            stars[i].classList.add('fa-regular');
                        }
                    });
                    star.addEventListener('mouseleave', function() {
                        const selected = parseInt(document.querySelector('.rating-stars').dataset
                            .selected);
                        stars.forEach(function(s, i) {
                            if (i < selected) {
                                s.classList.remove('fa-regular');
                                s.classList.add('fa-solid');
                            } else {
                                s.classList.remove('fa-solid');
                                s.classList.add('fa-regular');
                            }
                        });
                    });
                    star.addEventListener('click', function() {
                        document.querySelector('.rating-stars').dataset.selected = idx + 1;
                        ratingInput.value = idx + 1;
                        stars.forEach(function(s, i) {
                            if (i <= idx) {
                                s.classList.remove('fa-regular');
                                s.classList.add('fa-solid');
                            } else {
                                s.classList.remove('fa-solid');
                                s.classList.add('fa-regular');
                            }
                        });
                    });
                });
                // Reset stars on mouseleave from the whole container
                document.querySelector('.rating-stars').addEventListener('mouseleave', function() {
                    const selected = parseInt(this.dataset.selected);
                    stars.forEach(function(s, i) {
                        if (i < selected) {
                            s.classList.remove('fa-regular');
                            s.classList.add('fa-solid');
                        } else {
                            s.classList.remove('fa-solid');
                            s.classList.add('fa-regular');
                        }
                    });
                });
            });
        </script>


        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Only add modal logic once
                if (!window.__reviewImageModalBound) {
                    window.__reviewImageModalBound = true;
                    document.body.addEventListener('click', function(e) {
                        if (e.target.classList.contains('review-image')) {
                            const modal = document.querySelector('.review-image-modal');
                            const modalImg = modal.querySelector('.modal-img');
                            modalImg.src = e.target.dataset.full;
                            modal.classList.remove('hidden');
                            modal.classList.add('flex');
                        }
                        if (e.target.classList.contains('close-modal') || e.target.classList.contains(
                                'review-image-modal')) {
                            const modal = document.querySelector('.review-image-modal');
                            modal.classList.add('hidden');
                            modal.classList.remove('flex');
                        }
                    });
                    // Optional: close modal with ESC key
                    document.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape') {
                            const modal = document.querySelector('.review-image-modal');
                            if (modal && !modal.classList.contains('hidden')) {
                                modal.classList.add('hidden');
                                modal.classList.remove('flex');
                            }
                        }
                    });
                }
            });
        </script>

        {{-- Scroll to review section --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // If the URL contains #reviews, scroll to the review section
                if (window.location.hash === '#reviews') {
                    const reviewSection = document.querySelector('.reviews-section');
                    if (reviewSection) {
                        reviewSection.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }

                // Add #reviews to pagination links
                document.querySelectorAll('.reviews-section nav[role="navigation"] a').forEach(function(link) {
                    link.addEventListener('click', function(e) {
                        // Append #reviews to the link's href
                        link.href = link.href.split('#')[0] + '#reviews';
                    });
                });
            });
        </script>
    @endpush
