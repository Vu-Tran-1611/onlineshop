@extends('emails.layout')
@section('content')
    <!-- Body -->
    <tr>
        {{-- Password Updated Notification --}}
        <td style="padding: 40px 30px;">
            <h1> Hi {{ $user->name }},</h1>
            <p>Your password has been successfully updated.</p>
            <p>If you did not make this change, please contact support immediately.</p>
        </td>
    </tr>
@endsection
