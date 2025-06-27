@extends('frontend.layout.profile')
@section('profile-content')
    {{-- Wishlist --}}
    <div class="overflow-x-auto">
        <h1 class="text-2xl pb-2 font-semibold border-b-2 text-sky-600 border-slate-300 mb-5">Wishlist
        </h1>
        @if ($wishlists->isEmpty())
            <p class="text-center text-gray-500 py-10">
                Your wishlist is empty.
            </p>
        @else
            <ul class="grid  grid-cols-4 py-5 gap-5 ccursor-pointer ">
                @include('frontend.partials.filtered-product-list', [
                    'products' => $wishlists,
                    'isWishlist' => true,
                ])
                {{-- @foreach ($wishlists as $p)
                    <li data-id="{{ $p->id }}"
                        class=" cursor-pointer group bg-white rounded-xl overflow-hidden shadow-lg relative hover:shadow-2xl hover:-translate-y-2 transition-all hover:border-sky-600 flex flex-col justify-between leading-6 border border-slate-100">
                        <div class="relative">
                            <img class="min-h-[180px] w-full object-cover group-hover:scale-105 transition-transform duration-300"
                                src="{{ asset($p->product->thumb_image) }}" />
                            <div class="absolute top-2 left-2 flex gap-2 w-full pr-2 justify-between">
                                <span class="bg-sky-600 rounded-full text-white px-3 py-1 text-xs shadow">
                                    {{ getProductType($p->product) }}
                                </span>
                                @if (checkSale($p))
                                    <span
                                        class="bg-pink-600 rounded-full text-white px-3 py-1 text-xs shadow animate-bounce">
                                        -{{ calculateSalePercent($p->product) . '%' }}
                                    </span>
                                @endif
                            </div>
                            <div class="absolute top-2 right-2">
                                <button data-id="{{ $p->product->id }}"
                                    class="bg-white/80 hover:bg-pink-200 rounded-full p-2 shadow transition remove-from-wishlist">
                                    <i class="fa-regular fa-heart text-pink-600"></i>
                                </button>
                            </div>
                        </div>
                        <div class="p-4 flex flex-col gap-2">
                            <h1 class="font-semibold text-base text-slate-800 group-hover:text-sky-700 truncate"
                                title="{{ $p->product->name }}">{{ $p->product->name }}</h1>
                            <p class="font-semibold text-base text-slate-800 group-hover:text-sky-700 truncate">
                                {!! $p->product->short_description !!}</p>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-orange-700 font-bold text-lg">${{ $p->product->price }}</span>
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
                            <button data-url="{{ route('product', ['product' => $p->product->slug]) }}"
                                class="product mt-3 w-full bg-sky-600 hover:bg-sky-700 text-white rounded-lg py-1.5 font-medium transition-all opacity-0 group-hover:opacity-100 group-hover:translate-y-0 translate-y-2 duration-200">
                                View Details
                            </button>
                        </div>
                    </li>
                @endforeach --}}
            </ul>
            <div class="mt-8 col-span-2">
                {{ $wishlists->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    </div>
@endsection


@push('scripts')
    <script>
        $(".remove-from-wishlist").on("click", function() {
            const productId = $(this).data("id");
            const productCard = $(this).closest('li');
            console.log(productCard);
            $.ajax({
                type: "DELETE",
                url: "{{ route('user.profile.wishlist.remove-from-wishlist') }}",
                data: {
                    product_id: productId
                },
                dataType: "json",
                success: (response, textStatus, jqXHR) => {
                    console.log(textStatus);
                    if (response.success == true) {
                        productCard.remove();
                        Toastify({
                            text: response.message,
                            duration: 3000,
                            backgroundColor: "linear-gradient(to right, #22c55e, #16a34a)", // green
                        }).showToast();
                    } else {
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
        $(".product").on("click", function() {
            const url = $(this).data("url");
            window.location.replace(url);
        });
    </script>
@endpush
