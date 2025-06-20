@extends('emails.layout')
@section('content')
    <!-- Body -->
    <tr>
        <td style="padding: 40px 30px;">
            <p style="font-size: 18px; font-weight: 600; color: #333333; margin: 0 0 15px;">
                Hello {{ $user->name ?? 'Valued Customer' }},
            </p>
            <p style="font-size: 15px; color: #555555; line-height: 1.6; margin: 0 0 20px;">
                Welcome to <strong>{{ env('APP_NAME', 'OnlineShop') }}</strong>! We're thrilled to have
                you join our community. Your account is now ready — you can browse exclusive
                collections, enjoy personalized deals, and easily manage your orders.
            </p>

            <p style="font-size: 15px; color: #555555; line-height: 1.6; margin: 0 0 20px;">
                Should you have any questions, don’t hesitate to reach out. We’re here to help!
            </p>

            <p style="font-size: 15px; color: #555555; margin: 0 0 30px;">
                Let’s get started!
            </p>

            <div style="text-align: center; margin-bottom: 40px;">
                <a href="{{ url('/') }}"
                    style="background-color: #0077B6; color: white; padding: 14px 28px; border-radius: 6px; text-decoration: none; font-size: 15px; font-weight: bold;">
                    Visit OnlineShop
                </a>
            </div>

            <p style="font-size: 14px; color: #999999; text-align: center;">
                — The {{ env('APP_NAME', 'OnlineShop') }} Team
            </p>
        </td>
    </tr>
@endsection
