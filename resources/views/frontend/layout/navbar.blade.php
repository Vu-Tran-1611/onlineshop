@php
    if (Auth::check()) {
        Cart::Session(Auth::user()->id);
    }
@endphp
<div class=" bg-slate-800  w-screen z-[100] ">
    <div class=" bg-slate-900 ">
        <div class="md:w-[1200px] text-white p-5 mx-auto hidden md:flex justify-between">
            <p> <i class="fa-solid fa-rectangle-ad"></i> &ensp; <a class="hover:underline hover:underline-offset-4"
                    href="">Best Sweater For Winter</a></p>
            <ul class="flex justify-end gap-x-5">
                <li><i class="fa-solid fa-book"></i>&ensp; <a class="hover:underline hover:underline-offset-4  "
                        href="">How To Buy Our Product?</a></li>
                <li><i class="fa-solid fa-address-book"></i>&ensp;<a class="hover:underline hover:underline-offset-4  "
                        href="">Contact Us</a></li>
            </ul>
        </div>
    </div>
    <div class="md:w-[1200px] p-3 mx-auto">

        <nav class=" flex justify-between md:gap-5 lg:gap-0 lg:justify-between text-white  items-center">
            <h1 class="text-2xl">
                <a href="/" class="hidden md:block">
                    <svg viewBox="0 0 316 316" xmlns="http://www.w3.org/2000/svg"
                        class="w-20 h-20 fill-current text-gray-500">
                        <path
                            d="M305.8 81.125C305.77 80.995 305.69 80.885 305.65 80.755C305.56 80.525 305.49 80.285 305.37 80.075C305.29 79.935 305.17 79.815 305.07 79.685C304.94 79.515 304.83 79.325 304.68 79.175C304.55 79.045 304.39 78.955 304.25 78.845C304.09 78.715 303.95 78.575 303.77 78.475L251.32 48.275C249.97 47.495 248.31 47.495 246.96 48.275L194.51 78.475C194.33 78.575 194.19 78.725 194.03 78.845C193.89 78.955 193.73 79.045 193.6 79.175C193.45 79.325 193.34 79.515 193.21 79.685C193.11 79.815 192.99 79.935 192.91 80.075C192.79 80.285 192.71 80.525 192.63 80.755C192.58 80.875 192.51 80.995 192.48 81.125C192.38 81.495 192.33 81.875 192.33 82.265V139.625L148.62 164.795V52.575C148.62 52.185 148.57 51.805 148.47 51.435C148.44 51.305 148.36 51.195 148.32 51.065C148.23 50.835 148.16 50.595 148.04 50.385C147.96 50.245 147.84 50.125 147.74 49.995C147.61 49.825 147.5 49.635 147.35 49.485C147.22 49.355 147.06 49.265 146.92 49.155C146.76 49.025 146.62 48.885 146.44 48.785L93.99 18.585C92.64 17.805 90.98 17.805 89.63 18.585L37.18 48.785C37 48.885 36.86 49.035 36.7 49.155C36.56 49.265 36.4 49.355 36.27 49.485C36.12 49.635 36.01 49.825 35.88 49.995C35.78 50.125 35.66 50.245 35.58 50.385C35.46 50.595 35.38 50.835 35.3 51.065C35.25 51.185 35.18 51.305 35.15 51.435C35.05 51.805 35 52.185 35 52.575V232.235C35 233.795 35.84 235.245 37.19 236.025L142.1 296.425C142.33 296.555 142.58 296.635 142.82 296.725C142.93 296.765 143.04 296.835 143.16 296.865C143.53 296.965 143.9 297.015 144.28 297.015C144.66 297.015 145.03 296.965 145.4 296.865C145.5 296.835 145.59 296.775 145.69 296.745C145.95 296.655 146.21 296.565 146.45 296.435L251.36 236.035C252.72 235.255 253.55 233.815 253.55 232.245V174.885L303.81 145.945C305.17 145.165 306 143.725 306 142.155V82.265C305.95 81.875 305.89 81.495 305.8 81.125ZM144.2 227.205L100.57 202.515L146.39 176.135L196.66 147.195L240.33 172.335L208.29 190.625L144.2 227.205ZM244.75 114.995V164.795L226.39 154.225L201.03 139.625V89.825L219.39 100.395L244.75 114.995ZM249.12 57.105L292.81 82.265L249.12 107.425L205.43 82.265L249.12 57.105ZM114.49 184.425L96.13 194.995V85.305L121.49 70.705L139.85 60.135V169.815L114.49 184.425ZM91.76 27.425L135.45 52.585L91.76 77.745L48.07 52.585L91.76 27.425ZM43.67 60.135L62.03 70.705L87.39 85.305V202.545V202.555V202.565C87.39 202.735 87.44 202.895 87.46 203.055C87.49 203.265 87.49 203.485 87.55 203.695V203.705C87.6 203.875 87.69 204.035 87.76 204.195C87.84 204.375 87.89 204.575 87.99 204.745C87.99 204.745 87.99 204.755 88 204.755C88.09 204.905 88.22 205.035 88.33 205.175C88.45 205.335 88.55 205.495 88.69 205.635L88.7 205.645C88.82 205.765 88.98 205.855 89.12 205.965C89.28 206.085 89.42 206.225 89.59 206.325C89.6 206.325 89.6 206.325 89.61 206.335C89.62 206.335 89.62 206.345 89.63 206.345L139.87 234.775V285.065L43.67 229.705V60.135ZM244.75 229.705L148.58 285.075V234.775L219.8 194.115L244.75 179.875V229.705ZM297.2 139.625L253.49 164.795V114.995L278.85 100.395L297.21 89.825V139.625H297.2Z">
                        </path>
                    </svg>
                </a>
                <div class="sm:block md:hidden relative">
                    <button class="fa-solid fa-bars show-sidebar"></button>
                    {{-- Phone --}}
                    <div
                        class="hidden text-black sidebar  md:hidden p-5 fixed left-[0px]  top-[0px] z-10 bg-white h-[100vh] w-[300px]">
                        <button
                            class="text-4xl text-red-700 close-sidebar absolute right-3 top-3 fa-regular fa-circle-xmark"></button>
                        @if (Auth::check())
                            @php
                                $user = Auth::user();
                            @endphp
                            <div class="mt-5  flex gap-x-3">
                                @if ($user->image)
                                    <img class="cursor-pointer object-cover" alt="avatar" width="70"
                                        src="{{ asset($user->image) }}" />
                                @else
                                    <img class="cursor-pointer object-cover" alt="avatar" width="70"
                                        src="{{ asset('uploads/user-avatar.png') }}" />
                                @endif

                                <h1 class="cursor-pointer text-xl p-2 overflow-hidden">{{ $user->name }}
                                    {{ $user->email }}</h1>
                            </div>
                            <ul
                                class="mt-5 border-t-2 border-slate-200 group-hover:block text-base gap-y-6   text-black ">
                                <a href="{{ route('user.profile') }}"
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
                            <a href="{{ route('login') }}" class="group/a relative">
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
                        <ul class="text-black pt-5 border-slate-300 text-base border-t-2 flex flex-col gap-y-6 mt-5">

                            <li><a href="#" class="hover:underline hover:underline-offset-8"><i
                                        class="fa-solid fa-list"></i>
                                    Category</a>

                            </li>
                            <li><a href="#" class="hover:underline hover:underline-offset-8"><i
                                        class="fa-solid fa-eye"></i>
                                    Recently
                                    Viewd</a></li>
                            <li><a href="#" class="hover:underline hover:underline-offset-8"><i
                                        class="fa-solid fa-fire-flame-curved"></i> Best
                                    Sellers</a></li>
                            <li><a href="#" class="hover:underline hover:underline-offset-8"><i
                                        class="fa-solid fa-percent"></i>
                                    Promotions</a></li>
                            <li><a href="#" class="hover:underline hover:underline-offset-8"><i
                                        class="fa-regular fa-credit-card"></i> Payment
                                    Options</a></li>
                        </ul>
                        <ul class="flex flex-col pt-5 border-slate-300 text-base border-t-2 gap-y-6 mt-5">

                            <li> <i class="fa-solid fa-rectangle-ad"></i> <a
                                    class="hover:underline hover:underline-offset-4" href="">Best Sweater For
                                    Winter</a></li>
                            <li><i class="fa-solid fa-book"></i> <a class="hover:underline hover:underline-offset-4  "
                                    href="">How To Buy Our
                                    Product?</a></li>
                            <li><i class="fa-solid fa-address-book"></i> <a
                                    class="hover:underline hover:underline-offset-4  " href="">Contact Us</a>
                            </li>

                        </ul>
                    </div>
                    {{-- Phone --}}
                </div>
            </h1>
            <div class="md:w-[20%] lg:w-[40%] w-[100%] ml-8 flex items-center">
                <input class="text-black border-none w-[100%] rounded-sm" type="search"
                    placeholder="We have everything" />
                <button class="px-5 py-[6px] bg-slate-700 text-white z-5 translate-x-[-60px]"><i
                        class="fa-solid fa-magnifying-glass"></i></button>
            </div>

            <div class="hidden md:flex  items-center  group gap-2 relative">
                @if (Auth::check())
                    @php
                        $user = Auth::user();
                    @endphp
                    @if ($user->image)
                        <img class=" rounded-full cursor-pointer" alt="avatar" width="50"
                            src="{{ asset($user->image) }}" />
                    @else
                        <img class=" rounded-full cursor-pointer" alt="avatar" width="50"
                            src="{{ asset('uploads/user-avatar.png') }}" />
                    @endif
                    <h1 class="cursor-pointer text-base">{{ $user->name }} / {{ $user->email }}</h1>
                    <ul
                        class="group-hover:block hidden leading-8 shadow-2xl rounded-lg absolute top-[50px] z-10 bg-slate-100 text-black p-3  w-[180px]">
                        <a href="{{ route('user.profile') }}"
                            class="hover:bg-slate-300 cursor-pointer block rounded-lg p-2">Manage Account</a>
                        <a href="" class="hover:bg-slate-300 cursor-pointer block rounded-lg p-2">Historic
                            Order</a>
                        <a href="" class="hover:bg-slate-300 cursor-pointer block rounded-lg p-2">Favorite
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
            <div class="relative group/minicart border-2 border-white py-2 px-4 md:px-6 rounded-lg cursor-pointer">
                <a class="flex items-center gap-x-2" href="{{ route('user.cart') }}">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <p class="hidden md:block">Cart</p>
                    <div class="cart-qty text-black bg-white p-1 border-1 rounded-sm border-black">
                        @if (Auth::check())
                            {{ \Cart::getTotalQuantity() }}
                        @else
                            0
                        @endif
                    </div>
                </a>

                {{-- Cart  --}}
                <div
                    class="absolute z-[100]  rounded-xl group-hover/minicart:block hidden border-2 border-slate-300 shadow-2xl p-3 right-0 top-[50px] bg-white text-black w-[400px]">

                    <div class="cart-show {{ \Cart::getTotalQuantity() <= 0 ? 'hidden' : 'block' }}">
                        <h1 class="font-light py-3 text-xl text-sky-700">New Added Item</h1>
                        <ul class="cart-mini">
                            @if (Auth::check())
                                @if (!\Cart::isEmpty())
                                    @foreach (\Cart::getContent() as $item)
                                        <li data-url="{{ route('user.cart') }}"
                                            class="flex cart-item hover:bg-slate-100 p-2 justify-between leading-[80px] items-center">
                                            <span class="flex gap-2 items-center">
                                                <span><img width="50"
                                                        src="{{ asset($item->attributes['imageURL']) }}" /></span>
                                                <span>{{ $item->name }}</span>
                                            </span>
                                            {{-- |
                                                @foreach ($item->attributes as $key => $v)
                                                    @if ($key != 'imageURL' && $key != 'brand_id' && $key != 'product_id' && $key != 'vendor_id')
                                                        <span>{{ $v }}</span>
                                                    @endif
                                                @endforeach| --}}
                                            <span class="text-sky-600">${{ $item->price }}</span>
                                        </li>
                                    @endforeach
                                @endif
                            @else
                                <div class="text-center">Please <a href="{{ route('login') }}"
                                        class="text-sky-600 underline">login</a> to see your
                                    cart</div>
                            @endif

                        </ul>
                    </div>

                    <div style="background-image: url('{{ asset('uploads/no_product.png') }}')"
                        class=" text-sky-800 min-h-[100px] bg-center bg-contain bg-no-repeat cart-hidden  font-thin {{ \Cart::getTotalQuantity() <= 0 ? 'block' : 'hidden' }}">
                    </div>
                    <p
                        class="text-xl text-center text-slate-500 empty-cart-message {{ \Cart::getTotalQuantity() > 0 ? 'hidden' : 'block' }}">
                        Your Cart Is Empty
                    </p>
                    <div
                        class="view-cart-button mt-5 border-slate-200 text-right {{ \Cart::getTotalQuantity() <= 0 ? 'hidden' : 'block' }}">
                        <a href="{{ route('user.cart') }}"
                            class="bg-sky-600 rounded-sm hover:bg-sky-700  text-white py-2 px-4">View Cart</a>
                    </div>
                </div>
            </div>

        </nav>
        <ul class="text-white hidden
         md:flex justify-between mt-5">
            <li class="relative group/category "><a href="#"
                    class="hover:underline py-8 pr-[30px] hover:underline-offset-8"><i class="fa-solid fa-list"></i>
                    Category</a>
                {{-- Category --}}
                <ul
                    class=" absolute hidden group-hover/category:block shadow-2xl bg-slate-800   z-[100] top-[50px] w-[280px] leading-10">
                    @foreach ($categories as $cate)
                        <li class="group/subcategory p-3 px-3 text-lg hover:bg-sky-600 relative flex justify-between">
                            <span class="flex items-center w-full">
                                <i class="{{ $cate->icon }}"></i>&ensp;<a
                                    href="{{ route('product', ['category' => $cate->slug]) }}"
                                    class="block w-full">{{ $cate->name }}</a>
                            </span>
                            <span><a href=""><i class="fa-solid fa-caret-right"></i></a></span>
                            {{-- Sub Category --}}
                            <ul
                                class=" absolute hidden group-hover/subcategory:block top-0 left-[280px] shadow-2xl bg-slate-800   z-[100]  w-[350px] leading-10">
                                @foreach ($cate->subCategories as $sub)
                                    <li class=" p-3 px-3 text-lg hover:bg-sky-600  ">
                                        <a href="{{ route('product', ['category' => $cate->slug, 'subcategory' => $sub->slug]) }}"
                                            class="block w-full">{{ $sub->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach

                </ul>
            </li>
            <li><a href="#" class="hover:underline hover:underline-offset-8"><i class="fa-solid fa-eye"></i>
                    Recently
                    View</a></li>
            <li><a href="#" class="hover:underline hover:underline-offset-8"><i
                        class="fa-solid fa-fire-flame-curved"></i> Best
                    Sellers</a></li>
            <li><a href="#" class="hover:underline hover:underline-offset-8"><i
                        class="fa-solid fa-percent"></i>
                    Promotions</a></li>
            <li><a href="#" class="hover:underline hover:underline-offset-8"><i
                        class="fa-regular fa-credit-card"></i> Payment
                    Options</a></li>
        </ul>
    </div>
</div>
@push('scripts')
    <script>
        $(document).ready(function() {
            $(".show-sidebar,.close-sidebar").on("click", function() {
                $(".sidebar").toggleClass("hidden");
            });
            $(".cart-item").on("click", function() {
                const url = $(this).data("url");
                window.location.replace(url);
            });
        });
    </script>
@endpush
