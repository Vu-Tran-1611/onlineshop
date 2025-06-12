@extends('frontend.layout.master', ['title' => $title])
@section('content')
    <div class="py-5 relative min-h-screen ">
        {{-- Account sidebar --}}
        <div id="tabs" class="flex flex-col md:flex-row gap-6 ">
            <ul class="rounded-xl">
                <li data-id="1"
                    class="w-[300px] tab-link-1 tab-link duration-100 hover:duration-100 hover:font-semibold hover:text-sky-700 text-lg h-[60px] bg-white border-b-2 border-slate-200">
                    <a class="w-[100%] block p-5" href="#tabs-1"><i class="fa-solid fa-circle-user"></i>&emsp;Account</a>
                </li>
                <li data-id="2"
                    class="w-[300px] tab-link-2 tab-link duration-100 hover:duration-100 hover:font-semibold hover:text-sky-700 text-lg h-[60px] bg-white border-b-2 border-slate-200">
                    <a class="w-[100%] block p-5" href="#tabs-2"><i class="fa-solid fa-location-dot"></i>&emsp;Addresses</a>
                </li>
                <li data-id="3"
                    class="w-[300px] tab-link-3 tab-link duration-100 hover:duratio100-100 hover:font-semibold hover:text-sky-700 text-lg h-[60px] bg-white border-b-2 border-slate-200">
                    <a class="w-[100%] block p-5" href="#tabs-3"><i class="fa-solid fa-lock"></i>&emsp;Password And
                        Security</a>
                </li>
                <li data-id="4"
                    class="w-[300px] tab-link-4 tab-link duration-100 hover:duratio100-100 hover:font-semibold hover:text-sky-700 text-lg h-[60px] bg-white border-b-2 border-slate-200">
                    <a class="w-[100%] block p-5" href="#tabs-4"><i class="fa-solid fa-cart-shopping"></i>&emsp;Your
                        Purchase</a>
                </li>
                <li data-id="5"
                    class="w-[300px] tab-link-5 tab-link duration-100 hover:duration-100 hover:font-semibold hover:text-sky-700 text-lg h-[60px] bg-white border-b-2 border-slate-200">
                    <a class="w-[100%] block p-5" href="#tabs-5"><i class="fa-solid fa-heart"></i>&emsp;Favorite Items</a>
                </li>
            </ul>
            <div class="w-[100vw]">
                {{-- Profile --}}
                <div id="tabs-1" class=" bg-white p-8">
                    <div>
                        {{-- Overview --}}
                        <h1 class="text-2xl pb-2 font-semibold border-b-2 text-sky-600 border-slate-300">Overview</h1>
                        <div class="my-5 flex flex-col md:flex-row justify-between flex-wrap gap-y-4 ">
                            <div class="md:w-[300px]">
                                <h1>Name</h1>
                                <p class="font-semibold">{{ $user->name }}</p>
                            </div>

                            <div class="w-[300px]">
                                <h1>Username</h1>
                                <p class="font-semibold">{{ $user->username }}</p>
                            </div>
                            <div class="w-[300px]">
                                <h1>Email</h1>
                                <p class="font-semibold">{{ $user->email }}</p>
                            </div>
                            <div class="w-[300px]">
                                <h1>Role</h1>
                                <p class="capitalize font-semibold">{{ $user->role }}</p>
                            </div>
                            <div class="w-[300px]">
                                <h1>Balance</h1>
                                <p class="font-semibold">0</p>
                            </div>
                            <div class="w-[300px]">
                                <h1>Date Join</h1>
                                <p class="font-semibold">{{ $user->created_at }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex md:flex-row flex-col md:items-center gap-10 my-5 border-t-2 border-slate-200">
                        <div class="flex flex-col md:flex-row md:items-center gap-3 gap-x-8">
                            <img class="rounded-full" src="{{ asset($user->image) }}" alt="avatar" width="200" />
                            <form enctype="multipart/form-data" method="POST" action="{{ route('user.update-profile') }}">
                                @csrf
                                <input name="image" type="file" class="file invisible" /></br>
                                <button class="upload-file button-outline">Edit Avatar</button>
                            </form>
                        </div>
                        <div class="md:h-[100%]  border-l-2 border-slate-200 md:px-8">
                            <p>Please select Image that have size less than 5KB</p>
                            <p>Do not select offensive image </p>
                        </div>
                    </div>
                    <div class="gap-y-10">
                        <h1 class="text-2xl pb-2 font-semibold border-b-2 border-slate-300">Modify Information</h1>
                        <div class="mt-5">
                            <form enctype="multipart/form-data" method="POST" action="{{ route('user.update-profile') }}"
                                class="flex flex-col gap-y-5">
                                @csrf
                                <input class="md:w-[50%] w-[100%] rounded-lg hover:border-blue-500 border-[2px]"
                                    type="text" name="name" placeholder="Name" />
                                <input class="md:w-[50%] w-[100%] rounded-lg hover:border-blue-500 border-[2px]"
                                    type="text" name="username" placeholder="Username" />
                                <input class="md:w-[50%] w-[100%] rounded-lg hover:border-blue-500 border-[2px]"
                                    type="text" name="phone" placeholder="Phone" />
                                <input class="md:w-[50%] w-[100%] rounded-lg hover:border-blue-500 border-[2px]"
                                    type="text" name="email" placeholder="Email" />
                                <button class="w-[100%] md:w-[15%] button-outline">Save Change</button>
                            </form>
                        </div>
                    </div>
                </div>





                {{-- Order  --}}
                <div id="tabs-4" class=" bg-white p-8 h-[100vh]">
                    <div class="overflow-x-auto">
                        <h1 class="text-2xl pb-2 font-semibold border-b-2 text-sky-600 border-slate-300 mb-5">Your Orders
                        </h1>
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead class="bg-sky-800 text-white">
                                <tr>
                                    <th class="py-3 px-4 text-left">Order ID</th>
                                    <th class="py-3 px-4 text-left">Date</th>
                                    <th class="py-3 px-4 text-left">Total</th>
                                    <th class="py-3 px-4 text-left">Status</th>
                                    <th class="py-3 px-4 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @foreach ($orders as $order)
                                    <tr class="border-t border-gray-200 hover:bg-gray-100">
                                        <td class="py-3 px-4">{{ $order->id }}</td>
                                        <td class="py-3 px-4">{{ $order->created_at->format('Y-m-d') }}</td>
                                        <td class="py-3 px-4">${{ number_format($order->total, 2) }}</td>
                                        <td class="py-3 px-4">
                                            @if ($order->order_status == 'pending')
                                                <span
                                                    class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full shadow-sm">
                                                    Pending
                                                </span>
                                            @elseif ($order->order_status == 'declined')
                                                <span
                                                    class="bg-red-100 text-red-800 text-xs font-semibold px-3 py-1 rounded-full shadow-sm">
                                                    Declined
                                                </span>
                                            @else
                                                <span
                                                    class="bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full shadow-sm">
                                                    Success
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4">
                                            <a href="{{ route('user.orders.show', $order->id) }}"
                                                class="inline-block bg-sky-600 hover:bg-sky-700 text-white text-sm font-medium py-1.5 px-4 rounded-full shadow-sm transition-all duration-200">
                                                View Details
                                            </a>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if ($orders->isEmpty())
                            <p class="text-center text-gray-500 py-10">You don't have any orders yet.</p>
                        @endif
                    </div>

                </div>



            </div>
        </div>







    </div>
@endsection
