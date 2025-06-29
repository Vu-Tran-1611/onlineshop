       <div class="main-sidebar sidebar-style-2">
           <aside id="sidebar-wrapper">
               <div class="sidebar-brand">
                   <a href="index.html">{{ env('APP_NAME', 'OShop') }}</a>
               </div>
               <div class="sidebar-brand sidebar-brand-sm">
                   <a href="index.html">OS</a>
               </div>
               <ul class="sidebar-menu">
                   <li class="menu-header ">Shop
                       Profile</li>
                   <li class="{{ setActive(['vendor.shop-profile.index']) }}"><a class=" nav-link"
                           href="{{ route('vendor.shop-profile.index') }}">Shop
                           Profile</a>
                   </li>
                   <li class="menu-header">Dashboard</li>
                   <li class="{{ setActive(['vendor.dashboard']) }}">
                       <a href="{{ route('vendor.dashboard') }}" class="nav-link "><span>Dashboard</span></a>
                   </li>
                   <li class="menu-header">Starter</li>

                   <li class="{{ setActive(['vendor.product.*']) }}"><a class=" nav-link"
                           href="{{ route('vendor.product.index') }}">Products</a>
                   </li>
                   {{-- <li class="{{ setActive(['vendor.order.*']) }}"><a class=" nav-link"
                           href="{{ route('vendor.order.index') }}">Orders </a>
                   </li> --}}

                   <li class="{{ setActive(['vendor.orders.*']) }}"><a class=" nav-link"
                           href="{{ route('vendor.orders.index') }}">Orders</a>
                   </li>
               </ul>
           </aside>
       </div>
