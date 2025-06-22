{{-- Order Pending --}}
@extends('emails.order-notification.layout.order-notification-layout')
@section('order-notification-content')
    <p style="font-size: 15px; color: #555555; line-height: 1.6; margin: 0 0 20px;">
        Your order with ID <strong>{{ $order->id }}</strong> is currently pending. We are processing your order
        and will notify you once it is ready for shipment.
    </p>
@endsection
