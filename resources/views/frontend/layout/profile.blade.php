@extends('frontend.layout.master', ['title' => $title])

@section('content')
    <div class="py-5 relative min-h-screen flex flex-col md:flex-row gap-6">
        {{-- Sidebar tabs --}}
        <ul class="rounded-xl w-[300px] bg-white border border-slate-200">
            @php
                $routes = [
                    ['route' => 'user.profile.account', 'icon' => 'fa-circle-user', 'label' => 'Account'],
                    ['route' => 'user.profile.address', 'icon' => 'fa-location-dot', 'label' => 'Addresses'],
                    ['route' => 'user.profile.orders', 'icon' => 'fa-cart-shopping', 'label' => 'Your Purchase'],
                    ['route' => 'user.profile.favorite', 'icon' => 'fa-heart', 'label' => 'Favorite Items'],
                ];
            @endphp

            @foreach ($routes as $item)
                <li>
                    <a href="{{ route($item['route']) }}"
                        class="flex items-center gap-3 text-lg h-[60px] px-5 py-3 border-b border-slate-200 duration-150 
                        {{ request()->routeIs($item['route'])
                            ? 'font-semibold text-sky-700 bg-slate-100'
                            : 'hover:font-semibold hover:text-sky-700 hover:bg-slate-50' }}">
                        <i class="fa-solid {{ $item['icon'] }}"></i>
                        {{ $item['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>

        {{-- Main content --}}
        <div class="w-full bg-white p-5 rounded-xl shadow">
            @yield('profile-content')
        </div>
    </div>
@endsection
