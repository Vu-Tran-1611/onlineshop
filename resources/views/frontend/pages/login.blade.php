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
    <title>Login & Sign up Form</title>
</head>

<body>
    <div class="container {{ $action == 'register' ? 'active' : '' }}" id="container" style="min-height: 600px">
        <div class="form-container sign-up ">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <h1>Create Account</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>or use your email for registeration</span>
                <input name="name" type="text" placeholder="Username">
                <input name="email" type="email" placeholder="Email">
                <input name="password" type="password" placeholder="Password">
                <input name="password_confirmation" type="password" placeholder="Password">
                <div class="error-show">
                    @if ($errors->any())
                        @foreach ($errors->all() as $err)
                            <h4 class="error_alert">{{ $err }}</h4>
                        @endforeach
                    @endif
                </div>
                <button>Sign Up</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <h1>Log In</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>or use your email password</span>
                <input name="email" type="email" placeholder="Email">
                <input name="password" type="password" placeholder="Password">
                <div class="error-show">
                    @if ($errors->any())
                        @foreach ($errors->all() as $err)
                            <h4 class="error_alert">{{ $err }}</h4>
                        @endforeach
                    @endif
                </div>
                <a href="/forgot-password">Forget Your Password?</a>
                <button>Log In</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all of site features</p>
                    <button class="hidden toggle-button" id="login">Log In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Hi! New Customer?</h1>
                    <p>Register with your personal details to use all of site features</p>
                    <button class="hidden toggle-button" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>


    {{-- Jquery UI --}}
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="{{ asset('frontend/login/script.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(".toggle-button").on("click", function() {
                id = $(this).attr('id');
                console.log(id)
                $(".error-show").remove()
                history.replaceState({}, '', id);
            });
        });
    </script>
</body>

</html>
