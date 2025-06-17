@php
    \Cart::session('checked');
@endphp
@extends('frontend.layout.mastercart')
@section('content')
    <div class="relative min-h-screen bg-gradient-to-b from-white via-gray-50 to-sky-50 py-10 px-6 md:px-16">
        <a href="{{ route('user.cart') }}"
            class="inline-flex items-center gap-2 mb-6 text-sky-600 hover:text-sky-800 transition text-sm font-medium">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Previous Page
        </a>

        {{-- Freeze Screen --}}
        <div class="freeze-screen hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-40"></div>

        {{-- Loading Spinner --}}
        <div role="status"
            class="loading hidden fixed inset-0 z-50 flex items-center justify-center bg-white/50 backdrop-blur-sm">
            <div
                class="flex items-center gap-3 animate-pulse bg-white px-6 py-4 rounded-2xl shadow-xl border border-gray-200">
                <svg class="w-6 h-6 animate-spin text-sky-600" viewBox="0 0 100 101" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="50" cy="50" r="45" stroke="currentColor" stroke-width="10" fill="none" />
                    <path fill="currentColor"
                        d="M93.9 39c2.4-.6 3.9-3.1 3.1-5.4A45.1 45.1 0 0 0 50 5v10a35 35 0 0 1 33.9 24z" />
                </svg>
                <span class="text-gray-700 font-medium">Processing...</span>
            </div>
        </div>

        {{-- Delivery Info --}}
        <div class="bg-white/90 border-t-4 border-sky-600 shadow-xl rounded-xl px-8 py-6 mb-8">
            <h2 class="text-sky-700 text-xl font-bold mb-3 flex items-center gap-2">
                <i class="fa-solid fa-map-location-dot"></i> Delivery Address
            </h2>
            <div class="text-gray-700 space-x-4">
                <span class="font-semibold deliver-name">{{ $address->name . ' (+1) ' . $address->phone }}</span>
                <span class="deliver-address">{{ $address->address }}, {{ $address->city }}, {{ $address->state }},
                    {{ $address->zip }}</span>
                <button class="ml-4 text-sky-600 underline hover:text-sky-800 transition change-address">Change</button>
            </div>

            {{-- Address Selection Modal --}}
            <div
                class="select-address-panel hidden fixed z-50 top-[50%] left-[50%] -translate-x-1/2 -translate-y-1/2 w-[90%] md:w-[60%] bg-white rounded-2xl shadow-2xl p-8 animate-fade-in">
                <h3 class="text-2xl font-bold border-b pb-3 text-gray-800 mb-4">Select Shipping Address</h3>
                <form>
                    <div class="addresses space-y-4 max-h-[60vh] overflow-y-auto pr-2">
                        @foreach ($addresses as $addr)
                            <div
                                class="p-4 border rounded-xl flex justify-between items-start gap-4 bg-gray-50 hover:bg-sky-50 transition-all address address-{{ $addr->id }}">
                                <div>
                                    <p class="text-lg font-semibold name">{{ $addr->name }}</p>
                                    <p class="text-gray-600 phone">(+1) {{ $addr->phone }}</p>
                                    <p class="text-gray-500 address">{{ $addr->address }}</p>
                                    <p class="text-sm text-gray-400 country">{{ $addr->city }}, {{ $addr->state }},
                                        {{ $addr->country }}, {{ $addr->zip }}</p>
                                    <span
                                        class="default {{ $addr->is_default ? 'inline-block' : 'hidden' }} mt-2 inline-block text-xs bg-sky-100 text-sky-600 px-2 py-1 rounded">Default</span>
                                </div>
                                <input type="radio" name="id" value="{{ $addr->id }}"
                                    {{ $addr->is_default ? 'checked' : '' }} class="accent-sky-600 mt-2">
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6 flex justify-end gap-4">
                        <button type="button"
                            class="close-address-panel px-6 py-2 border border-sky-600 text-sky-600 rounded-lg hover:bg-sky-50 transition">Cancel</button>
                        <button type="submit"
                            class="confirm px-6 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition">Confirm</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Cart Items --}}
        @if (\Cart::getTotalQuantity() > 0)
            <div class="bg-white rounded-xl shadow-lg px-8 py-6 mb-10">
                <h2 class="text-2xl font-bold mb-6 text-slate-800">🏍️ Your Order</h2>
                <div class="space-y-6">
                    @foreach ($vendors as $vendor)
                        <div>
                            <h3 class="font-semibold text-xl text-sky-700 mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-shop"></i> {{ $vendor['name'] }}
                            </h3>
                            <div class="space-y-5">
                                @foreach (Cart::getContent() as $cartItem)
                                    @if ($cartItem->attributes['vendor_id'] == $vendor['id'])
                                        @php
                                            $product = App\Models\Product::findOrFail(
                                                $cartItem->attributes['product_id'],
                                            );
                                        @endphp
                                        <div class="flex flex-col sm:flex-row justify-between gap-6 border-b pb-5">
                                            <div class="flex gap-5 w-full sm:w-[60%]">
                                                <img src="{{ asset($cartItem->attributes['imageURL']) }}"
                                                    alt="Product Image"
                                                    class="w-24 h-24 object-cover rounded-md border bg-white shadow-sm" />
                                                <div class="flex-1">
                                                    <h4 class="text-l   g font-semibold text-gray-800">
                                                        {{ $cartItem->name }}
                                                    </h4>
                                                    <ul class="text-base text-gray-500 mt-1 space-y-1">
                                                        @foreach ($cartItem->attributes as $key => $val)
                                                            @if (!in_array($key, ['brand_id', 'product_id', 'vendor_id', 'imageURL']))
                                                                <b
                                                                    class="capitalize">{{ str_replace('_', ' ', $key) }}</b>:&emsp;{!! $val !!}
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                            <div
                                                class="flex justify-between sm:justify-end gap-6 text-base text-gray-700 sm:w-[40%]">
                                                <span>${{ $cartItem->price }}</span>
                                                <span>x {{ $cartItem->quantity }}</span>
                                                <span
                                                    class="text-sky-600 font-semibold">${{ $cartItem->getPriceSum() }}</span>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="flex items-center justify-center flex-col h-[40vh] text-center bg-white rounded-xl shadow-md">
                <p class="text-xl text-gray-500"><i class="fa-solid fa-circle-xmark text-red-400 text-2xl"></i> Your Cart is
                    Empty <i class="fa-regular fa-face-sad-tear text-blue-400 text-2xl"></i></p>
                <a href="/"
                    class="mt-6 px-6 py-3 bg-sky-600 text-white rounded-lg shadow hover:bg-sky-700 transition">Go
                    Shopping</a>
            </div>
        @endif

        {{-- Payment --}}
        <div class="bg-white rounded-xl shadow-lg px-8 py-6 mt-10">
            <h2 class="text-xl font-semibold text-slate-800 mb-4">💳 Payment Method</h2>
            <div class="flex flex-wrap gap-4 mb-6">
                <button class="tab border border-sky-600 text-sky-600 px-4 py-2 rounded-md active" data-method="cash">Cash
                    on Delivery</button>
                <button class="tab border border-sky-600 text-sky-600 px-4 py-2 rounded-md" data-method="card">Credit /
                    Debit Card (via Stripe)</button>
            </div>
            <div class="tabs-content space-y-6">
                <div id="tabs-1" class="tab-content block">
                    <div class="text-gray-700 space-x-4">
                        <span class="text-sky-600 font-semibold">Cash on Delivery</span>
                        <span>No additional fee for this method.</span>
                    </div>
                </div>
                <div id="tabs-2" class="tab-content hidden">
                    <div class="text-gray-700">
                        <span class="text-sky-600 font-semibold">Credit / Debit Card</span>
                        <span class="block">Pay securely using your card via Stripe.</span>
                        <p class="mt-2 text-sm text-gray-500">
                            Please note that you will be redirected to Stripe's secure payment page to complete your
                            transaction.
                        </p>
                    </div>
                </div>
            </div>
            <div class="border-t pt-6 space-y-3 text-right text-lg text-gray-700">
                <div><span class="font-medium">Subtotal:</span> ${{ \Cart::getSubTotal() }}</div>
                @foreach (\Cart::getConditions() as $condition)
                    <div>
                        <span class="capitalize">{{ $condition->getType() }}:</span>
                        <span class="text-red-500">{{ $condition->getValue() }}</span>
                    </div>
                @endforeach
                <div class="text-3xl text-sky-700 font-bold">Total: ${{ \Cart::getTotal() }}</div>
            </div>
            <div class="mt-6 flex justify-between items-center text-sm text-gray-500">
                {{-- Policy --}}
                <span>
                    By clicking "Place Order", you agree to our
                    <a href="" class="underline text-sky-600 hover:text-sky-800">Terms of Service</a> and
                    <a href= ""class="underline text-sky-600 hover:text-sky-800">Privacy Policy</a>.
                </span>

                <form action="{{ route('user.payment.make-payment') }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" id="payment_method" value="cash" />
                    <input type="hidden" name="order_address" value="{{ $address->id }}" />
                    <input type="hidden" name="payment_status" value="0" />
                    <button
                        class="bg-gradient-to-r from-sky-600 to-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-full shadow-md transition-all">
                        Place Order
                    </button>
                </form>
            </div>
        </div>


    </div>


@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            const tabs = document.querySelectorAll('.tab');
            const contents = document.querySelectorAll('.tab-content');
            const paymentInput = document.getElementById('payment_method');


            $(".tab").on("click", function() {
                $(".tab").removeClass("active text-white");
                $(this).addClass("active text-white");
                const method = $(this).data("method");
                $(".tab-content").addClass("hidden");
                $(`#tabs-${method === 'cash' ? '1' : '2'}`).removeClass("hidden");
                const paymentMethod = $(this).data("method");
                console.log(paymentMethod);
                $("#payment_method").val(paymentMethod);
            });

            $(".change-address").on("click", function() {
                $(".select-address-panel").show();
                $(".freeze-screen").show();
            });
            $(".close-address-panel").on("click", function(e) {
                e.preventDefault();
                $(".select-address-panel").hide();
                $(".freeze-screen").hide();
            });
            $(".confirm").on("click", function(e) {
                e.preventDefault();
                const id = $(this).closest('form').find('input[type="radio"]:checked').val();
                $('input[name="order_address"]').val(id);
                $(".select-address-panel").hide();
                $(".freeze-screen").hide();
                $.ajax({
                    type: "POST",
                    url: "{{ route('user.check-out.set-user-delivery-address') }}",
                    data: {
                        address_id: id
                    },
                    dataType: "JSON",
                    beforeSend: function() {
                        $(".loading").removeClass("hidden").addClass("flex");
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            const address = response.address;
                            $(".deliver-name").html(`
                            ${address.name} (+1) ${address.phone }
                            `)
                            $(".deliver-address").html(`
    ${address.address}, ${address.city} City, ${address.state} State, ${address.zip}
    `);
                            $(".loading").removeClass("flex").addClass("hidden");
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.table(jqXHR)
                    }
                });
            });
        });
    </script>
@endpush
