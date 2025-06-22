@extends('frontend.layout.mastercart')
@section('content')
    {{-- Payment Cancel --}}
    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
        <div class="bg-white shadow-lg rounded-2xl p-8 max-w-xl w-full text-center">
            <div class="flex justify-center mb-4">
                <svg class="w-16 h-16 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v2m0 4h.01M12 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Payment Cancelled</h2>
            <p class="text-gray-600 mb-2">Your payment was not completed or was cancelled.</p>
            <p class="text-gray-600 mb-6">If you believe this is a mistake, please try again or contact our support team.
            </p>
            <a href="{{ route('user.cart') }}"
                class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition">
                Return to Cart
            </a>
        </div>
    </div>
@endsection
