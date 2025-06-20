<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
</head>

<body
    style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f4;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">

                    <!-- Header -->
                    <tr>
                        <td style="background-color: #0077B6; padding: 30px; text-align: center;">
                            <a href="{{ url('/') }}" style="text-decoration: none;">
                                <span style="font-size: 26px; font-weight: bold; color: white;">OShop</span>
                            </a>
                        </td>
                    </tr>
                    <!-- Body -->
                    @yield('content')
                    <!-- Footer -->
                    <tr>
                        <td
                            style="background-color: #eeeeee; padding: 20px; text-align: center; font-size: 12px; color: #888888;">
                            © {{ date('Y') }} {{ env('APP_NAME', 'OnlineShop') }}. All rights reserved.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>

</html>
