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
                            @if ($order->order_status == 'pending')
                                <span
                                    class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full shadow-sm">
                                    Pending
                                </span>
                            @elseif ($order->order_status == 'declined')
                                <span
                                    class="bg-red-100 text-red-800 text-xs font-semibold px-3 py-1 rounded-full shadow-sm">
                                    Declined
                                </span>
                            @else
                                <span
                                    class="bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full shadow-sm">
                                    Success
                                </span>
                            @endif
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
