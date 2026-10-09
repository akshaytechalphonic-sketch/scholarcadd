<header class="header header-container sticky-bar">
    <div class="container">
        <div class="main-header">
            <div class="header-left">
                <div class="header-logo">
                    <a href="{{ url('/') }}" class="block expanded-logo text-center">
                        @if ($system?->small_logo)
                            <img src="{{ route('secure-documents', ['path' => $system?->small_logo]) }}"
                                >
                        @else
                            <img src="{{ asset('assets/image/logo/logo-black.png') }}" />
                        @endif
                    </a>
                </div>
                <div class="header-search">
                    <div class="box-header-search">
                        <form class="form-search" method="post" action="#">
                            <div class="box-category">
                                <select class="select-active select2-hidden-accessible" data-select2-id="1"
                                    tabindex="-1" aria-hidden="true">
                                    <option>All Courses</option>
                                    @foreach ($categories as $categoriesName)
                                        <option value="{{ $categoriesName->name }}">{{ $categoriesName->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="box-keysearch">
                                <input class="form-control font-xs" type="text" value=""
                                    placeholder="Search for items">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="header-nav text-start">
                    <nav class="nav-main-menu d-none d-xl-block">
                        <ul class="main-menu">
                            <li><a class="active" href="#">Flash Deals</a></li>
                            <li><a href="#">Special</a></li>
                            <li><a href="#">Top Sellers</a></li>
                        </ul>
                    </nav>
                    <div class="burger-icon burger-icon-white"><span class="burger-icon-top"></span><span
                            class="burger-icon-mid"></span><span class="burger-icon-bottom"></span></div>
                </div>
                <div class="header-shop">
                    <div class="d-inline-block box-dropdown-cart"><span
                            class="font-lg icon-list icon-account"><span>Account</span></span>
                        <div class="dropdown-account">
                            <ul>
                                <li><a href="{{ route('userprofile-form') }}">My Account</a></li>
                                {{-- <li><a href="page-account.html">My Account</a></li> --}}
                                <li><a href="page-account.html">Order Tracking</a></li>
                                <li><a href="page-account.html">My Orders</a></li>
                                <li><a href="page-account.html">My Wishlist</a></li>
                                <li><a href="page-account.html">Setting</a></li>
                                <li>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <a href="javascript:void();" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <div
                                                class="h-6 w-6 rounded-full bg-[#edecf3]  text-primary    flex justify-center items-center">
                                                <i class="ri-logout-box-line"></i>
                                            </div>
                                            {{ __('common.logout') }}
                                        </a>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    {{-- <a class="font-lg icon-list icon-wishlist"
                        href="shop-wishlist.html"><span>Wishlist</span><span class="number-item font-xs">5</span></a>
                    <div class="d-inline-block box-dropdown-cart"><span
                            class="font-lg icon-list icon-cart"><span>Cart</span><span
                                class="number-item font-xs">2</span></span>
                        <div class="dropdown-cart">
                            <div class="item-cart mb-20">
                                <div class="cart-image"><img src="{{ asset('assets/imgs/page/homepage1/imgsp5.png')}}" alt="Ecom">
                                </div>
                                <div class="cart-info"><a class="font-sm-bold color-brand-3"
                                        href="shop-single-product.html">2022 Apple iMac with Retina 5K Display 8GB RAM,
                                        256GB SSD</a>
                                    <p><span class="color-brand-2 font-sm-bold">1 x $2856.4</span></p>
                                </div>
                            </div>
                            <div class="item-cart mb-20">
                                <div class="cart-image"><img src="{{ asset('assets/imgs/page/homepage1/imgsp4.png')}}" alt="Ecom">
                                </div>
                                <div class="cart-info"><a class="font-sm-bold color-brand-3"
                                        href="shop-single-product-2.html">2022 Apple iMac with Retina 5K Display 8GB
                                        RAM, 256GB SSD</a>
                                    <p><span class="color-brand-2 font-sm-bold">1 x $2856.4</span></p>
                                </div>
                            </div>
                            <div class="border-bottom pt-0 mb-15"></div>
                            <div class="cart-total">
                                <div class="row">
                                    <div class="col-6 text-start"><span class="font-md-bold color-brand-3">Total</span>
                                    </div>
                                    <div class="col-6"><span class="font-md-bold color-brand-1">$2586.3</span></div>
                                </div>
                                <div class="row mt-15">
                                    <div class="col-6 text-start"><a class="btn btn-cart w-auto"
                                            href="shop-cart.html">View cart</a></div>
                                    <div class="col-6"><a class="btn btn-buy w-auto"
                                            href="shop-checkout.html">Checkout</a></div>
                                </div>
                            </div>
                        </div>
                    </div><a class="font-lg icon-list icon-compare" href="shop-compare.html"><span>Compare</span></a> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="header-bottom">
        <div class="container">
            <div class="dropdown d-inline-block">
                <button class="btn dropdown-toggle btn-category" id="dropdownCategory" type="button"
                    data-bs-toggle="dropdown" aria-expanded="true" data-bs-display="static"><span
                        class="dropdown-right font-sm-bold color-white">Courses</span></button>
                <div class="sidebar-left dropdown-menu dropdown-menu-light" aria-labelledby="dropdownCategory"
                    data-bs-popper="static">
                    <ul class="menu-texts menu-close">
                        @foreach ($categories as $category)
                            <li class="has-children">
                                <a href="javascript:;">
                                    <span class="img-link">
                                        <img src="{{ asset('assets/imgs/template/monitor.svg')}}" alt="Ecom">
                                    </span>
                                    <span class="text-link">{{ $category->name }}</span>
                                </a>

                                @if ($category->masterChild && $category->masterChild->count())
                                    <ul class="sub-menu">
                                        @foreach ($category->masterChild as $child)
                                            <li>
                                                <a href="#">{{ $child->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach

                        {{-- <li class="has-children">
                    <a href="javascript:;"><span class="img-link"><img src="assets/imgs/template/monitor.svg" alt="Ecom"></span><span class="text-link">Computers &amp; Accessories</span></a>
                  <ul class="sub-menu">
                    <li><a href="#">Computer Accessories</a></li>
                    <li><a href="#">Computer Cases</a></li>
                    <li><a href="#">Laptop</a></li>
                    <li><a href="#">HDD</a></li>
                    <li><a href="#">RAM</a></li>
                    <li><a href="#">Headphone</a></li>
                  </ul>
                </li>
                <li class="has-children"><a class="active" href="javascript:;"><span class="img-link"><img src="assets/imgs/template/mobile.svg" alt="Ecom"></span><span class="text-link">Cell Phones</span></a>
                  <ul class="sub-menu">
                    <li><a href="#">Phone Accessories</a></li>
                    <li><a href="#">Phone Cases</a></li>
                    <li><a href="#">Postpaid Phones</a></li>
                    <li><a href="#">Unlocked Phones</a></li>
                    <li><a href="#">Prepaid Phones</a></li>
                    <li><a href="#">Prepaid Plans</a></li>
                    <li><a href="#">Refurbished Phones</a></li>
                    <li><a href="#">Straight Talk</a></li>
                    <li><a href="#">iPhone</a></li>
                    <li><a href="#">Samsung Galaxy</a></li>
                    <li><a href="#">Samsung Galaxy</a></li>
                    <li><a href="#">Samsung Galaxy</a></li>
                    <li><a href="#">Samsung Galaxy</a></li>
                    <li><a href="#">Samsung Galaxy</a></li>
                  </ul>
                </li>
                <li><a href="#"><span class="img-link"><img src="assets/imgs/template/game.svg" alt="Ecom"></span><span class="text-link">Gaming Gatgets</span></a></li>
                <li><a href="#"><span class="img-link"><img src="assets/imgs/template/clock.svg" alt="Ecom"></span><span class="text-link">Smart watches</span></a></li>
                <li><a href="#"><span class="img-link"><img src="assets/imgs/template/airpod.svg" alt="Ecom"></span><span class="text-link">Airpod</span></a></li>
                <li><a href="#"><span class="img-link"><img src="assets/imgs/template/airpods.svg" alt="Ecom"></span><span class="text-link">Wired Headphone</span></a></li>
                <li><a href="#"><span class="img-link"><img src="assets/imgs/template/mouse.svg" alt="Ecom"></span><span class="text-link">Mouse &amp; Keyboard</span></a></li>
                <li><a href="#"><span class="img-link"><img src="assets/imgs/template/music-play.svg" alt="Ecom"></span><span class="text-link">Headphone</span></a></li>
                <li><a href="#"><span class="img-link"><img src="assets/imgs/template/bluetooth.svg" alt="Ecom"></span><span class="text-link">Bluetooth devices</span></a></li>
                <li><a href="#"><span class="img-link"><img src="assets/imgs/template/clound.svg" alt="Ecom"></span><span class="text-link">Cloud Software</span></a></li>
                <li><a href="#"><span class="img-link"><img src="assets/imgs/template/electricity.svg" alt="Ecom"></span><span class="text-link">Electric accessories</span></a></li>
                <li><a href="#"><span class="img-link"><img src="assets/imgs/template/cpu.svg" alt="Ecom"></span><span class="text-link">Mainboard &amp; CPU</span></a></li>
                <li><a href="#"><span class="img-link"><img src="assets/imgs/template/devices.svg" alt="Ecom"></span><span class="text-link">Desktop</span></a></li>
                <li><a href="#"><span class="img-link"><img src="assets/imgs/template/driver.svg" alt="Ecom"></span><span class="text-link">Speaker</span></a></li>
                <li><a href="#"><span class="img-link"><img src="assets/imgs/template/lamp.svg" alt="Ecom"></span><span class="text-link">Computer Decor</span></a></li> --}}
                    </ul>
                </div>
            </div>
            <div class="header-nav d-inline-block">
                <nav class="nav-main-menu d-none d-xl-block">
                    <ul class="main-menu">
                        <li class="has-children"><a class="active" href="index.html">Home</a>
                            <ul class="sub-menu two-col">
                                <li><a href="index.html">Homepage - 1</a></li>
                                <li><a href="index-2.html">Homepage - 2</a></li>
                                <li><a href="index-3.html">Homepage - 3</a></li>
                                <li><a href="index-4.html">Homepage - 4</a></li>
                                <li><a href="index-5.html">Homepage - 5</a></li>
                                <li><a href="index-6.html">Homepage - 6</a></li>
                                <li><a href="index-7.html">Homepage - 7</a></li>
                                <li><a href="index-8.html">Homepage - 8</a></li>
                                <li><a href="index-9.html">Homepage - 9</a></li>
                                <li><a href="index-10.html">Homepage - 10</a></li>
                            </ul>
                        </li>
                        <li class="has-children"><a href="shop-grid.html">Shop</a>
                            <ul class="sub-menu two-col">
                                <li><a href="shop-grid.html">Shop Grid</a></li>
                                <li><a href="shop-grid-2.html">Shop Grid 2</a></li>
                                <li><a href="shop-list.html">Shop list - Left sidebar</a></li>
                                <li><a href="shop-list-2.html">Shop list - Right sidebar</a></li>
                                <li><a href="shop-fullwidth.html">Shop Fullwidth</a></li>
                                <li><a href="shop-single-product.html">Single Product</a></li>
                                <li><a href="shop-single-product-2.html">Single Product 2</a></li>
                                <li><a href="shop-single-product-3.html">Single Product 3</a></li>
                                <li><a href="shop-single-product-4.html">Single Product 4</a></li>
                                <li><a href="shop-cart.html">Shop Cart</a></li>
                                <li><a href="shop-checkout.html">Shop Checkout</a></li>
                                <li><a href="shop-compare.html">Shop Compare</a></li>
                                <li><a href="shop-wishlist.html">Shop Wishlist</a></li>
                            </ul>
                        </li>
                        <li class="has-children"><a href="shop-vendor-list.html">Vendors</a>
                            <ul class="sub-menu">
                                <li><a href="shop-vendor-list.html">Vendors Listing</a></li>
                                <li><a href="shop-vendor-single.html">Vendor Single</a></li>
                            </ul>
                        </li>
                        <li class="has-children"><a href="#">Pages</a>
                            <ul class="sub-menu">
                                <li><a href="page-about-us.html">About Us</a></li>
                                <li><a href="page-contact.html">Contact Us</a></li>
                                <li><a href="page-careers.html">Careers</a></li>
                                <li><a href="page-term.html">Term and Condition</a></li>
                                <li><a href="page-register.html">Register</a></li>
                                <li><a href="page-login.html">Login</a></li>
                                <li><a href="page-404.html">Error 404</a></li>
                            </ul>
                        </li>
                        <li class="has-children"><a href="blog.html">Blog</a>
                            <ul class="sub-menu">
                                <li><a href="blog.html">Blog - No Sidebar</a></li>
                                <li><a href="blog-2.html">Blog - Right Sidebar</a></li>
                                <li><a href="blog-list.html">Blog List</a></li>
                                <li><a href="blog-big.html">Blog category big</a></li>
                                <li><a href="blog-single.html">Blog Single - Left sidebar</a></li>
                                <li><a href="blog-single-2.html">Blog Single - Right sidebar</a></li>
                                <li><a href="blog-single-3.html">Blog Single - No sidebar</a></li>
                            </ul>
                        </li>
                        <li><a href="page-contact.html">Contact Us</a></li>
                    </ul>
                </nav>
            </div>
            <div class="discount font-16 font-bold">SPECIAL OFFER</div>
        </div>
    </div>
</header>
<div class="mobile-header-active mobile-header-wrapper-style perfect-scrollbar">
    <div class="mobile-header-wrapper-inner">
        <div class="mobile-header-content-area">
            <div class="mobile-logo"><a class="d-flex" href="index.html"><img alt="Ecom"
                        src="{{ asset('assets/imgs/template/logo.svg')}}"></a></div>
            <div class="perfect-scroll">
                <div class="mobile-menu-wrap mobile-header-border">
                    <nav class="mt-15">
                        <ul class="mobile-menu font-heading">
                            <li class="has-children"><a class="active" href="index.html">Home</a>
                                <ul class="sub-menu">
                                    <li><a href="index.html">Homepage - 1</a></li>
                                    <li><a href="index-2.html">Homepage - 2</a></li>
                                    <li><a href="index-3.html">Homepage - 3</a></li>
                                    <li><a href="index-4.html">Homepage - 4</a></li>
                                    <li><a href="index-5.html">Homepage - 5</a></li>
                                    <li><a href="index-6.html">Homepage - 6</a></li>
                                    <li><a href="index-7.html">Homepage - 7</a></li>
                                    <li><a href="index-8.html">Homepage - 8</a></li>
                                    <li><a href="index-9.html">Homepage - 9</a></li>
                                    <li><a href="index-10.html">Homepage - 10</a></li>
                                </ul>
                            </li>
                            <li class="has-children"><a href="shop-grid.html">Shop</a>
                                <ul class="sub-menu">
                                    <li><a href="shop-grid.html">Shop Grid</a></li>
                                    <li><a href="shop-grid-2.html">Shop Grid 2</a></li>
                                    <li><a href="shop-list.html">Shop List</a></li>
                                    <li><a href="shop-list-2.html">Shop List 2</a></li>
                                    <li><a href="shop-fullwidth.html">Shop Fullwidth</a></li>
                                    <li><a href="shop-single-product.html">Single Product</a></li>
                                    <li><a href="shop-single-product-2.html">Single Product 2</a></li>
                                    <li><a href="shop-single-product-3.html">Single Product 3</a></li>
                                    <li><a href="shop-single-product-4.html">Single Product 4</a></li>
                                    <li><a href="shop-cart.html">Shop Cart</a></li>
                                    <li><a href="shop-checkout.html">Shop Checkout</a></li>
                                    <li><a href="shop-compare.html">Shop Compare</a></li>
                                    <li><a href="shop-wishlist.html">Shop Wishlist</a></li>
                                </ul>
                            </li>
                            <li class="has-children"><a href="shop-vendor-list.html">Vendors</a>
                                <ul class="sub-menu">
                                    <li><a href="shop-vendor-list.html">Vendors Listing</a></li>
                                    <li><a href="shop-vendor-single.html">Vendor Single</a></li>
                                </ul>
                            </li>
                            <li class="has-children"><a href="#">Pages</a>
                                <ul class="sub-menu">
                                    <li><a href="page-about-us.html">About Us</a></li>
                                    <li><a href="page-contact.html">Contact Us</a></li>
                                    <li><a href="page-careers.html">Careers</a></li>
                                    <li><a href="page-term.html">Term and Condition</a></li>
                                    <li><a href="page-register.html">Register</a></li>
                                    <li><a href="page-login.html">Login</a></li>
                                    <li><a href="page-404.html">Error 404</a></li>
                                </ul>
                            </li>
                            <li class="has-children"><a href="blog.html">Blog</a>
                                <ul class="sub-menu">
                                    <li><a href="blog.html">Blog Grid</a></li>
                                    <li><a href="blog-2.html">Blog Grid 2</a></li>
                                    <li><a href="blog-list.html">Blog List</a></li>
                                    <li><a href="blog-big.html">Blog Big</a></li>
                                    <li><a href="blog-single.html">Blog Single - Left sidebar</a></li>
                                    <li><a href="blog-single-2.html">Blog Single - Right sidebar</a></li>
                                    <li><a href="blog-single-3.html">Blog Single - No sidebar</a></li>
                                </ul>
                            </li>
                            <li><a href="page-contact.html">Contact</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="mobile-account">
                    <div class="mobile-header-top">
                        <div class="user-account"><a href="page-account.html"><img
                                    src="{{ asset('assets/imgs/template/ava_1.png')}}" alt="Ecom"></a>
                            <div class="content">
                                <h6 class="user-name">Hello<span class="text-brand"> Steven !</span></h6>
                                <p class="font-xs text-muted">You have 3 new messages</p>
                            </div>
                        </div>
                    </div>
                    <ul class="mobile-menu">
                        <li><a href="{{ route('userprofile-form') }}">My Account</a></li>
                        <li><a href="page-account.html">Order Tracking</a></li>
                        <li><a href="page-account.html">My Orders</a></li>
                        <li><a href="page-account.html">My Wishlist</a></li>
                        <li><a href="page-account.html">Setting</a></li>
                        <li><a href="page-login.html">
                                Sign out llalla</a></li>
                    </ul>
                </div>
                <div class="mobile-banner">
                    <div class="bg-5 block-iphone"><span class="color-brand-3 font-sm-lh32">Starting from $899</span>
                        <h3 class="font-xl mb-10">iPhone 12 Pro 128Gb</h3>
                        <p class="font-base color-brand-3 mb-10">Special Sale</p><a class="btn btn-arrow"
                            href="shop-grid.html">learn more</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
