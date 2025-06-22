{{-- Order Delivered --}}
@extends('emails.order-notification.layout.order-notification-layout')
@section('order-notification-content')
    <p style="font-size: 15px; color: #555555; line-height: 1.6; margin: 0 0 20px;">
        We are pleased to inform you that your order with ID <strong>{{ $order->id }}</strong> has been
        successfully delivered.
        We hope you are satisfied with your purchase and that it meets your expectations.
    </p>
@endsection
