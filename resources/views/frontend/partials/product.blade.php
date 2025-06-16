<div class="bg-white rounded-2xl shadow-lg border border-sky-100 p-4 flex flex-col group hover:shadow-2xl transition">
    <a href="{{ route('product.details', $product->slug) }}" class="block relative">
        <img src="{{ $product->image_url ?? asset('images/no-image.png') }}" alt="{{ $product->name }}"
            class="w-full h-48 object-cover rounded-xl mb-4 transition-transform duration-300 group-hover:scale-105">
        @if ($product->is_new)
            <span
                class="absolute top-3 left-3 bg-sky-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow">New</span>
        @elseif($product->is_featured)
            <span
                class="absolute top-3 left-3 bg-yellow-400 text-white text-xs font-bold px-3 py-1 rounded-full shadow">Featured</span>
        @elseif($product->is_best_seller)
            <span
                class="absolute top-3 left-3 bg-pink-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow">Best
                Seller</span>
        @endif
        <button class="absolute top-3 right-3 wishlist-btn text-gray-400 hover:text-pink-500 transition"
            data-product-id="{{ $product->id }}">
            <i class="fa-regular fa-heart text-xl"></i>
        </button>
    </a>
    <div class="flex-1 flex flex-col justify-between">
        <div>
            <h3 class="text-lg font-bold text-sky-900 mb-1 truncate">{{ $product->name }}</h3>
            <div class="text-sm text-gray-500 mb-2 truncate">{{ $product->brand->name ?? '' }}</div>
            <div class="flex items-center gap-2 mb-2">
                <span class="text-sky-700 font-semibold text-lg">${{ number_format($product->price, 2) }}</span>
                @if ($product->old_price && $product->old_price > $product->price)
                    <span class="text-gray-400 line-through text-sm">${{ number_format($product->old_price, 2) }}</span>
                @endif
            </div>
            <div class="flex items-center gap-2 text-xs text-gray-500">
                <i class="fa-solid fa-box"></i> Stock: {{ $product->stock }}
                <span class="mx-2">|</span>
                <i class="fa-solid fa-fire text-orange-500"></i> Sold: {{ $product->soldCount() }}
            </div>
        </div>
        <a href="{{ route('product.details', $product->slug) }}"
            class="mt-4 block w-full text-center py-2 rounded-lg bg-gradient-to-r from-sky-700 to-blue-500 text-white font-semibold hover:from-sky-800 hover:to-blue-700 transition">
            View Details
        </a>
    </div>
</div>

@push('scripts')
    <script>
        $(function() {
            $('.wishlist-btn').on('click', function(e) {
                e.preventDefault();
                var btn = $(this);
                var productId = btn.data('product-id');
                $.post("{{ route('wishlist.add') }}", {
                    product_id: productId,
                    _token: '{{ csrf_token() }}'
                }, function(response) {
                    if (response.status === 'success') {
                        btn.find('i').removeClass('fa-regular').addClass('fa-solid text-pink-500');
                    }
                });
            });
        });
    </script>
@endpush
