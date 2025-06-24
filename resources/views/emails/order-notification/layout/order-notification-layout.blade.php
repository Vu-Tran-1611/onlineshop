{{-- Order Canceled --}}
@extends('emails.layout')
@section('content')
    <!-- Body -->
    <tr>
        <td style="padding: 40px 30px;">
            <p style="font-size: 18px; font-weight: 600; color: #333333; margin: 0 0 15px;">
                Hello {{ $user->name ?? 'Valued Customer' }},
            </p>


            {{-- Content  --}}
            <p style="font-size: 15px; color: #555555; line-height: 1.6; margin: 0 0 20px;">
                {{-- Content --}}
                @yield('order-notification-content')
            </p>


            <p style="font-size: 15px; color: #555555; line-height: 1.6; margin: 0 0 20px;">
                If you have any questions or need assistance, please feel free to contact our support team.
            </p>

            <p style="font-size: 15px; color: #555555; margin: 0 0 30px;">
                Thank you for choosing us!
            </p>

            <div style="text-align: center; margin-bottom: 40px;">
                <a href="{{ route('home') }}"
                    style="background-color: #0077B6; color: white; padding: 14px 28px; border-radius: 6px; text-decoration: none; font-size: 15px; font-weight: bold;">
                    Visit Our Store
                </a>
            </div>

            <p style="font-size: 14px; color: #999999; text-align: center;">
                — The {{ env('APP_NAME', 'OnlineShop') }} Team
            </p>
        </td>
    </tr>
@endsection
