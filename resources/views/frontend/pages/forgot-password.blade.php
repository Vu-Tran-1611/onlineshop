<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('frontend/login/style.css') }}">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <title>Forgot Password</title>
</head>

<body>

    <div class="container" id="container">

        <div class="form-container sign-in" style="position: relative;margin:0 auto">
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <h1>Forgot Password</h1>

                <input name="email" type="email" placeholder="Email">
                @if ($errors->any())
                    @foreach ($errors->all() as $err)
                        <h4 class="error_alert">{{ $err }}</h4>
                    @endforeach
                @endif
                @if (session('status'))
                    <h4 class="success_notify" style="color: rgb(14, 172, 29);">
                        {{ session('status') }}
                    </h4>
                @endif
                <button>Reset Password</button>
                <a href="/login">Back To Login</a>
            </form>
        </div>

    </div>

    <script src="{{ asset('frontend/login/script.js') }}"></script>
</body>

</html>
