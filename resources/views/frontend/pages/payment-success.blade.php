@extends('frontend.layout.mastercart')
@section('content')
    <div style="background-image: url('{{ asset('uploads/payment-approve.png') }}')"
        class="bg-[length:600px] bg-no-repeat bg-top shadow-xl bg-gray-50 min-h-screen p-10 gap-y-3 flex flex-col justify-end items-center">
        <h1 class="text-5xl text-green-600 font-semibold">
            <i class="fa-regular fa-circle-check"></i>
            Payment Successful !
        </h1>
        <p class="text-center  text-green-600 ">Thank You For Shopping Us !</p>
        <div class="flex my-5 gap-x-8 ">
            <a href="{{ route('user.profile.orders.show', $order->id) }}" class="py-3 px-5 rounded-sm border-sky-600 border-2 text-sky-600">
                Go To Your Purchase
            </a>
            <a href="/" class=" py-3 px-5 rounded-sm bg-sky-600 text-white hover:bg-sky-700">
                Go Shopping
            </a>
        </div>
    </div>
@endsection
@push('scripts')
    <script></script>
@endpush
