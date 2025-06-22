{{-- Order Canceled --}}
@extends('emails.order-notification.layout.order-notification-layout')
@section('order-notification-content')
    <p style="font-size: 15px; color: #555555; line-height: 1.6; margin: 0 0 20px;">
        We regret to inform you that your order with ID <strong>{{ $order->id }}</strong> has been canceled.
        If you have any questions or concerns regarding this cancellation, please do not hesitate to reach out to
        us.
    </p>
@endsection
