<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    {{-- Toastify --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    @stack('styles')
    <title>{{ $title ?? 'Home' }}</title>
</head>

<body>
    <div style="font-family:Roboto, sans-serif;">

        {{-- Navbar --}}
        <div class=" bg-slate-800  w-screen z-[100] ">

            <div class="md:w-[1200px] p-3 mx-auto">
                <nav class=" flex justify-between md:gap-5 lg:gap-0 lg:justify-between text-white  items-center">
                    <h1 class="text-2xl">
                        <a href="/" class="hidden md:block">{{ env('APP_NAME', 'OShop') }} |
                            {{ $title }}</a>

                    </h1>
                    <div class="hidden md:flex  items-center  group gap-2 relative">
                        @if (Auth::check())
                            @php
                                $user = Auth::user();
                            @endphp
                            @if ($user->image)
                                <div class="w-10 h-10 rounded-full overflow-hidden">
                                    <img src="{{ asset($user->image) }}" class="w-full h-full object-cover"
                                        alt="Avatar" />
                                </div>
                            @else
                                <div class="w-10 h-10 rounded-full overflow-hidden">
                                    <img src="{{ asset('uploads/user-avatar.png') }}" class="w-full h-full object-cover"
                                        alt="Avatar" />
                                </div>
                            @endif
                            <h1 class="cursor-pointer text-base">{{ $user->name }} / {{ $user->email }}</h1>
                            <ul
                                class="group-hover:block hidden leading-8 shadow-2xl rounded-lg absolute top-[40px] z-10 bg-slate-100 text-black p-3  w-[180px]">
                                <a href="{{ route('user.profile.account') }}"
                                    class="hover:bg-slate-300 cursor-pointer block rounded-lg p-2">Manage Account</a>
                                <a href=""
                                    class="hover:bg-slate-300 cursor-pointer block rounded-lg p-2">Historic
                                    Order</a>
                                <a href=""
                                    class="hover:bg-slate-300 cursor-pointer block rounded-lg p-2">Favorite
                                    Items</a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                        class="hover:bg-red-700 hover:text-white block cursor-pointer rounded-lg p-2">
                                        Logout
                                    </a>
                                </form>
                            </ul>
                        @else
                            <i class="text-3xl fa-regular fa-circle-user"></i>
                            <a href="{{ route('login') }}" class="group/login relative">
                                Login
                                <p
                                    class="absolute duration-300  group-hover/login:duration-300 group-hover/login:w-[105%] w-0 left-[0px] bg-white h-[2px] bottom-[-10px]">
                                </p>
                            </a>/
                            <a href="{{ route('register') }}" class="group/signup relative">
                                Sign Up
                                <p
                                    class="absolute duration-300  group-hover/signup:duration-300 group-hover/signup:w-[100%] w-0 left-[0px] bg-white h-[2px] bottom-[-10px]">
                                </p>
                            </a>
                        @endif
                    </div>
                </nav>
            </div>
        </div>
        <!-- Main Content -->
        <div class=" bg-slate-300">
            <div class="main-content lg:w-[1200px] mx-auto">
                @yield('content')
            </div>
        </div>

        {{-- Footer --}}
        @include('frontend.layout.footer')

    </div>
    {{-- Sweetalert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Jquery UI --}}
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    {{-- Font Awesome --}}
    <script src="https://kit.fontawesome.com/1027857984.js" crossorigin="anonymous"></script>
    {{-- Swiper --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    {{-- Toastify --}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        @if ($errors->any())
            @foreach ($errors->all() as $err)

                Toastify({
                    text: "{{ $err }}",
                    duration: 3000,
                    className: "info",
                    style: {
                        background: "linear-gradient(to right, #00b09b, #96c93d)",
                    }
                }).showToast();
            @endforeach
        @endif
        @if (Session::has('status'))
            Toastify({
                text: "{{ session('status') }}",
                duration: 3000,
                className: "info",
                style: {
                    background: "linear-gradient(to right, #00b09b, #96c93d)",
                }
            }).showToast();
        @endif
    </script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
