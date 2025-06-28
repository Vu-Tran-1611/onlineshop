<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html">{{ env('APP_NAME', 'OShop') }}</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">OS</a>
        </div>
        <ul class="sidebar-menu">
            {{-- <li class="menu-header">Dashboard</li>
            <li class="dropdown ">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Dashboard</span></a>
                <ul class="dropdown-menu">
                    <li class=><a class="nav-link" href="index-0.html">General Dashboard</a></li>
                    <li><a class="nav-link" href="index.html">Ecommerce Dashboard</a></li>
                </ul>
            </li> --}}
            <li
                class="dropdown {{ setActive(['admin.coupons.*', 'admin.top-banner.*', 'admin.flash_sell.*', 'admin.product.*', 'admin.slider.*', 'admin.brand.*', 'admin.category.*', 'admin.product_from_vendor.*']) }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Website
                        Management</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.slider.index', 'admin.slider.create']) }}"><a class="nav-link"
                            href="{{ route('admin.slider.index') }}">Slider</a></li>
                    <li class="{{ setActive(['admin.brand.index', 'admin.brand.create']) }}"><a class="nav-link"
                            href="{{ route('admin.brand.index') }}">Brand</a></li>
                    <li class="{{ setActive(['admin.product_from_vendor.*', 'admin.product.*']) }}"><a class=" nav-link"
                            href="{{ route('admin.product_from_vendor.index') }}">Products from vendors</a>
                    </li>
                    <li class="{{ setActive(['admin.flash_sell.*']) }}"><a class=" nav-link"
                            href="{{ route('admin.flash_sell.index') }}">Flash Sell Products </a>
                    </li>
                    <li class="{{ setActive(['admin.coupons.*']) }}"><a class=" nav-link"
                            href="{{ route('admin.coupons.index') }}">Coupon</a>
                    </li>
                    <li class="{{ setActive(['admin.top-banner.index', 'admin.top-banner.create']) }}"><a
                            class="nav-link" href="{{ route('admin.top-banner.index') }}">Top Banner</a></li>
                </ul>
            </li>
            <li class="{{ setActive(['admin.category.*', 'admin.sub-category.*']) }} dropdown">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i>
                    <span>Category Management</span></a>
                <ul class="dropdown-menu">
                    <li
                        class="{{ setActive(['admin.category.index', 'admin.category.create', 'admin.category.edit']) }}">
                        <a class="nav-link" href="{{ route('admin.category.index') }}">Main Categories</a>
                    </li>
                    <li class="{{ setActive(['admin.sub-category.index']) }}"><a class=" nav-link"
                            href="{{ route('admin.sub-category.index') }}">Sub Categories</a>
                    </li>
                </ul>
            </li>
            <li class="dropdown {{ setActive(['admin.user.*']) }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Member
                        Management</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.user.index']) }}"><a class="nav-link"
                            href="{{ route('admin.user.index') }}">User</a></li>
                </ul>
            </li>

        </ul>

    </aside>
</div>
