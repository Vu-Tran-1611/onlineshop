{{-- Order Verified --}}
@extends('emails.order-notification.layout.order-notification-layout')
@section('order-notification-content')
    <p style="font-size: 15px; color: #555555; line-height: 1.6; margin: 0 0 20px;">
        Your order with ID <strong>{{ $order->id }}</strong> has been successfully verified. We are now preparing
        your order for shipment and will notify you once it is on its way.
    </p>
@endsection
