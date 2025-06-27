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


    <title>{{ $title ?? 'Home' }}</title>
    <script>
        const USER = {
            id: "{{ auth()->check() ? auth()->user()->id : ' ' }}"
        }
    </script>

    @vite(['resources/js/app.js', 'resources/js/frontend.js'])
</head>

<body>
    <div style="font-family:Roboto, sans-serif;">
        {{-- Navbar --}}
        @include('frontend.layout.navbar')

        {{-- Sidebar --}}
        @include('frontend.layout.sidebar')

        <!-- Main Content -->
        <div class=" bg-slate-100">
            <div class="main-content lg:w-[1200px] mx-auto">
                @yield('content')
            </div>
        </div>
        {{-- Chat --}}
        @if (auth()->check())
            @include('frontend.sections.chat')
        @endif

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
    {{-- Product Card Scripts --}}
    <script>
        // Add To Wishlist
        $(".add-to-wishlist").on("click", function() {
            const productId = $(this).data("id");
            const productCard = $(this).closest('li');
            $.ajax({
                type: "POST",
                url: "{{ route('user.profile.wishlist.add-to-wishlist') }}",
                data: {
                    product_id: productId
                },
                dataType: "json",
                success: (response, textStatus, jqXHR) => {
                    console.log(textStatus);
                    if (response.success == true) {
                        productCard.remove();
                        Toastify({
                            text: response.message,
                            duration: 3000,
                            backgroundColor: "linear-gradient(to right, #22c55e, #16a34a)", // green
                        }).showToast();
                    } else {
                        Toastify({
                            text: response.message,
                            backgroundColor: "linear-gradient(to right, #ef4444, #b91c1c)", // red/danger
                        }).showToast();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.table(jqXHR)
                }
            });
        });
        // Remove from wishlist 
        $(".remove-from-wishlist").on("click", function() {
            const productId = $(this).data("id");
            const productCard = $(this).closest('li');
            console.log(productCard);
            $.ajax({
                type: "DELETE",
                url: "{{ route('user.profile.wishlist.remove-from-wishlist') }}",
                data: {
                    product_id: productId
                },
                dataType: "json",
                success: (response, textStatus, jqXHR) => {
                    console.log(textStatus);
                    if (response.success == true) {
                        productCard.remove();
                        Toastify({
                            text: response.message,
                            duration: 3000,
                            backgroundColor: "linear-gradient(to right, #22c55e, #16a34a)", // green
                        }).showToast();
                    } else {
                        Toastify({
                            text: response.message,
                            backgroundColor: "linear-gradient(to right, #ef4444, #b91c1c)", // red/danger
                        }).showToast();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.table(jqXHR)
                }
            });
        });
        $(".product").on("click", function() {
            const url = $(this).data("url");
            window.location.replace(url);
        });
    </script>
    @stack('scripts')
</body>

</html>
