@extends('frontend.layout.profile')
@section('profile-content')
    {{-- Profile Orders --}}
    <div class="overflow-x-auto">
        <h1 class="text-2xl pb-2 font-semibold border-b-2 text-sky-600 border-slate-300 mb-5">Your Orders
        </h1>
        <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-[#1E293B] text-white">
                <tr>
                    <th class="py-3 px-4 text-left">Order ID</th>
                    <th class="py-3 px-4 text-left">Date</th>
                    <th class="py-3 px-4 text-left">Total</th>
                    <th class="py-3 px-4 text-left">Status</th>
                    <th class="py-3 px-4 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @foreach ($orders as $order)
                    <tr class="border-t border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-4">{{ $order->id }}</td>
                        <td class="py-3 px-4">{{ $order->created_at->format('Y-m-d') }}</td>
                        <td class="py-3 px-4">${{ number_format($order->total, 2) }}</td>
                        <td class="py-3 px-4">
                            <span title="This order is currently '{{ $order->order_status }}'"
                                class= "animate-pulse  rounded-full font-semibold text-sm shadow-md">
                                @if ($order->order_status === 'pending')
                                    <span
                                        class="bg-yellow-100 text-yellow-700 text-xs  px-3 py-1 font-semibold  rounded-full shadow-sm">Pending</span>
                                @elseif ($order->order_status === 'processing' || $order->order_status === 'confirmed')
                                    <span
                                        class="bg-blue-100 text-blue-700 text-xs  px-3 py-1 font-semibold  rounded-full shadow-sm">Processing</span>
                                @elseif ($order->order_status === 'completed' || $order->order_status === 'delivered')
                                    <span
                                        class="bg-green-100 text-green-700 text-xs  px-3 py-1 font-semibold  rounded-full shadow-sm">Completed</span>
                                @elseif ($order->order_status === 'cancelled')
                                    <span
                                        class="bg-red-100 text-red-700 text-xs px-3 py-1  font-semibold  rounded-full shadow-sm">Cancelled</span>
                                @endif
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <a href="{{ route('user.profile.orders.show', $order->id) }}"
                                class="inline-block bg-sky-600 hover:bg-sky-700 text-white text-sm font-medium py-1.5 px-4 rounded-full shadow-sm transition-all duration-200">
                                View Details
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-8 col-span-2">
            {{ $orders->links('vendor.pagination.tailwind') }}
        </div>

        @if ($orders->isEmpty())
            <p class="text-center text-gray-500 py-10">You don't have any orders yet.</p>
        @endif
    </div>
@endsection
