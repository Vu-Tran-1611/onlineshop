@extends('frontend.layout.master')

@section('title', 'Order Details')

@section('content')
    <div class="container mx-auto max-w-5xl py-12 px-4">
        <a href="{{ route('user.profile.orders') }}"
            class="inline-block mb-6 text-sm font-semibold text-slate-900 hover:underline">
            <i class="fa-solid fa-arrow-left mr-1"></i> Back to previous page
        </a>

        <div class="flex justify-center items-center mb-8">
            @php
                $stages = config('order.stages');
                $currentStatus = $order->order_status;
                $statusOrder = config('order.statuses');
                $currentIndex = array_search($currentStatus, $statusOrder);
            @endphp
            @foreach ($stages as $index => $stage)
                @php
                    $isActive = false;
                    if ($currentStatus === 'cancelled') {
                        $isActive = $stage['key'] === 'cancelled';
                    } else {
                        $isActive = $index <= $currentIndex && $stage['key'] !== 'cancelled';
                    }
                    $circleColors = config('order.circle_colors');
                    $borderColors = config('order.border_colors');
                @endphp
                <div class="flex flex-col items-center">
                    <div class="relative">
                        <div
                            class="w-14 h-14 flex items-center justify-center rounded-full border-4 {{ $isActive ? $borderColors[$stage['color']] ?? '' : 'border-gray-300' }} {{ $isActive ? $circleColors[$stage['color']] ?? '' : 'bg-gray-200 text-gray-400' }} shadow-lg text-2xl transition-all duration-300">
                            <i class="fa-solid {{ $stage['icon'] }}"></i>
                        </div>
                        @if ($isActive)
                            <span
                                class="absolute -top-2 -right-2 bg-white rounded-full border border-gray-200 p-1
                            w-7 h-7 flex items-center justify-center shadow-lg">
                                <i class="fa-solid fa-check text-green-500"></i>
                            </span>
                        @endif
                    </div>
                    <span class="mt-2 text-sm font-semibold {{ $isActive ? 'text-slate-900' : 'text-gray-400' }}">
                        {{ $stage['label'] }}
                    </span>
                </div>
                @if ($index < count($stages) - 1)
                    <div
                        class="flex-1 h-1 mx-2 {{ $currentStatus === 'cancelled' && $index < 2 ? 'bg-gray-200' : ($index < $currentIndex && $currentStatus !== 'cancelled' ? 'bg-blue-400' : 'bg-gray-200') }} rounded">
                    </div>
                @endif
            @endforeach
        </div>
        <p class="text-center text-sm text-gray-500 mb-8">Placed on {{ $order->created_at->format('F j, Y, g:i A') }}</p>

        <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-200">
            <div class="bg-[#1E293B] py-6 px-8 flex justify-between items-center">
                <div>
                    <h2 class="text-white text-2xl font-semibold">
                        <i class="fa-solid"></i> Order #{{ $order->id }}
                    </h2>
                </div>

                <span title="This order is currently '{{ $order->order_status }}'"
                    class= "animate-pulse px-4 py-1.5 rounded-full font-semibold text-sm shadow-md">
                    @if ($order->order_status === 'pending')
                        <span
                            class="bg-yellow-100 text-yellow-700 text-sm font-semibold px-3 py-1 rounded-full shadow-sm">Pending</span>
                    @elseif ($order->order_status === 'processing' || $order->order_status === 'confirmed')
                        <span
                            class="bg-blue-100 text-blue-700 text-sm font-semibold px-3 py-1 rounded-full shadow-sm">Processing</span>
                    @elseif ($order->order_status === 'completed' || $order->order_status === 'delivered')
                        <span
                            class="bg-green-100 text-green-700 text-sm font-semibold px-3 py-1 rounded-full shadow-sm ">Completed</span>
                    @elseif ($order->order_status === 'cancelled')
                        <span
                            class="bg-red-100 text-red-700 text-sm font-semibold px-3 py-1 rounded-full shadow-sm">Cancelled</span>
                    @endif
                </span>
            </div>

            <div class="p-8 bg-gray-50">

                @if ($order->invoice_id)
                    <div
                        class="flex items-center justify-between bg-white border border-gray-200 rounded-lg p-4 shadow mb-6">
                        <div class="flex items-center gap-3">
                            <div class="bg-slate-100 p-2 rounded-full">
                                <i class="fa-solid fa-barcode text-slate-700"></i>
                            </div>
                            <div class="text-sm text-slate-700">
                                <div class="font-semibold">Invoice ID</div>
                                <div class="text-xs text-gray-500">Used for tracking and invoice reference</div>
                            </div>
                        </div>
                        <div class="text-sm font-bold text-slate-900">{{ $order->invoice_id }}</div>
                    </div>
                @endif
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">

                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 mb-3">
                            <i class="fa-solid fa-user mr-2"></i>Customer Information
                        </h3>
                        <div class="text-gray-700 space-y-2">
                            <div><i class="fa-solid fa-user-tag mr-2"></i>{{ $user->name }}</div>
                            <div><i class="fa-solid fa-envelope mr-2"></i>{{ $user->email }}</div>
                            <div><i class="fa-solid fa-phone mr-2"></i>{{ $user->phone }}</div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 mb-3">
                            <i class="fa-solid fa-truck mr-2"></i>Shipping Address
                        </h3>
                        <div class="text-gray-700">
                            <i class="fa-solid fa-location-dot mr-2"></i>{{ $order->userAddress->address }}
                        </div>
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-slate-900 mb-1">
                    <i class="fa-solid fa-box-open mr-2"></i>Order Items
                </h3>
                <p class="text-sm text-gray-500 mb-4">
                    Total Products: <span class="font-semibold text-slate-900">{{ $order->quantity }}</span>
                </p>

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full bg-white">
                        <thead class="bg-[#1E293B] text-white">
                            <tr>
                                <th class="py-3 px-4 text-center">Image</th>
                                <th class="py-3 px-4 text-left">Product</th>
                                <th class="py-3 px-4 text-left">Variant</th>
                                <th class="py-3 px-4 text-center">Quantity</th>
                                <th class="py-3 px-4 text-right">Price</th>
                                <th class="py-3 px-4 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderProducts as $item)
                                <tr class="border-t hover:bg-gray-100">
                                    <td class="py-4 px-4 text-center">
                                        <img class="w-12 h-12 rounded-lg object-cover inline-block border border-gray-200"
                                            src="{{ asset($item->product->thumb_image) }}"
                                            alt="{{ $item->product->name }}">
                                    </td>
                                    <td class="py-4 px-4 text-gray-700">
                                        <div class="font-semibold">{{ $item->product->name }}</div>
                                        @if ($item->product->short_description)
                                            <div class="text-xs text-gray-500 mt-1">
                                                {!! Str::limit($item->product->short_description, 60) !!}</div>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-gray-700">
                                        @if ($item->variants)
                                            <div class="text-sm text-gray-500">
                                                @foreach (json_decode($item->variants, true) as $key => $value)
                                                    <div>
                                                        <b> {{ $key }}: &emsp;</b>
                                                        {{ $value }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-500">No variant selected</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-center font-semibold">{{ $item->qty }}</td>
                                    <td class="py-4 px-4 text-right">${{ number_format($item->unit_price, 2) }}</td>
                                    <td class="py-4 px-4 text-right font-semibold">
                                        ${{ number_format($item->qty * $item->unit_price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex">
                    <div class="w-full mt-8 space-y-4">
                        <h3 class="text-lg font-semibold text-slate-900 mb-2">
                            <i class="fa-solid fa-credit-card mr-2"></i>Payment Method
                        </h3>
                        <p class="text-gray-700 mb-2 flex items-center gap-2">
                            @if ($order->payment_method === 'card')
                                <img src="https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_111x69.jpg"
                                    alt="PayPal" class="h-6">
                            @elseif($order->payment_method === 'cash')
                                <i class="fa-brands fa-cc-visa text-xl text-indigo-700"></i>
                            @endif
                            {{ ucfirst($order->payment_method ?? 'N/A') }}
                        </p>

                        <h3 class="text-lg font-semibold text-slate-900 mb-2">
                            <i class="fa-solid fa-money-check-dollar mr-2"></i>Payment Status
                        </h3>
                        <p class="mb-4">
                            @if ($order->payment_status)
                                <span
                                    class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-sm font-semibold bg-green-100 text-green-700">
                                    <i class="fa-solid fa-check-circle text-green-500"></i> Paid
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-sm font-semibold bg-red-300 text-red-700 animate-pulse">
                                    <i class="fa-solid fa-triangle-exclamation text-red-700"></i> Unpaid
                                </span>
                            @endif
                        </p>


                    </div>
                    <div class="mt-8 space-y-4 w-full">
                        <div class="flex justify-between text-sm text-gray-700">
                            <span>Subtotal:</span>
                            <span>${{ number_format($order->sub_total, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-700">
                            <span>Tax:</span>
                            <span>${{ number_format($order->tax ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-700">
                            <span>Discount:</span>
                            <span>-${{ number_format($order->tax ?? 0, 2) }}</span>
                        </div>
                        <div class="bg-[#1E293B] text-white px-8 py-4 rounded-xl font-semibold shadow">
                            <i class="fa-solid fa-wallet mr-2"></i>Total Amount:
                            <span class="text-xl ml-2 font-bold">${{ number_format($order->total, 2) }}</span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
