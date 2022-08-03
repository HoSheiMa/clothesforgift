<div class="navbar-custom">
    <div class="container-fluid">
        <ul class="list-unstyled topnav-menu float-end mb-0">
            @php
            use App\Models\Notif;
                $dots = 0;
            @endphp
            @foreach (Notif::all() as $notif)
                        @if (($notif->for == "All" || $notif->for == Auth::user()->role) && ($notif->type == "notice" && $notif->status == 1))
                       @php $dots = $dots + 1 @endphp
                       @endif
            @endforeach
            <li class="dropdown notification-list topbar-dropdown">
                <a class="nav-link dropdown-toggle waves-effect waves-light " data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="true">
                    <i class="fe-bell noti-icon"></i>
                    <span class="badge bg-danger rounded-circle noti-icon-badge">{{$dots}}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-lg " data-popper-placement="bottom-end" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(-269px, 72px);">

                    <!-- item-->
                    <div class="dropdown-item noti-title">
                        <h5 class="m-0">
                            <span class="float-end">
                            </span>الاشعارات
                        </h5>
                    </div>

                    <div class="noti-scroll" data-simplebar="init"><div class="simplebar-wrapper" style="margin: 0px;"><div class="simplebar-height-auto-observer-wrapper"><div class="simplebar-height-auto-observer"></div></div><div class="simplebar-mask"><div class="simplebar-offset" style="right: 0px; bottom: 0px;"><div class="simplebar-content-wrapper" style="height: auto; overflow: hidden scroll;"><div class="simplebar-content" style="padding: 0px;">

                        <!-- item-->

                        @foreach (Notif::all() as $notif)
                        @if (($notif->for == "All" || $notif->for == Auth::user()->role) && ($notif->type == "notice" && $notif->status == 1))
                       @php $dots = $dots + 1 @endphp
                        <a href="javascript:void(0);" class="dropdown-item notify-item ">
                                <div class="notify-icon">
                                    <img src="../assets/images/users/user1.png" class="img-fluid rounded-circle" alt=""> </div>
                                <p class="notify-details">الادارة</p>
                                <p class="text-muted mb-0 user-msg">
                                    <small> {{$notif->message}}</small>
                                </p>
                            </a>
                        @endif
                        @endforeach

                    </div></div></div></div><div class="simplebar-placeholder" style="width: auto; height: 435px;"></div></div><div class="simplebar-track simplebar-horizontal" style="visibility: hidden;"><div class="simplebar-scrollbar" style="width: 0px; display: none;"></div></div><div class="simplebar-track simplebar-vertical" style="visibility: visible;"><div class="simplebar-scrollbar" style="height: 121px; display: block; transform: translate3d(0px, 0px, 0px);"></div></div></div>


                </div>
            </li>

            <li class="dropdown notification-list topbar-dropdown">
                <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <img src="/assets/images/users/user1.png" alt="user-image" class="rounded-circle">
                    <span class="pro-user-name ms-1">
                        {{Auth::user()->name}} <i class="mdi mdi-chevron-down"></i>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                    <!-- item-->
                    <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">مرحبا !</h6>
                    </div>

                    <!-- item-->
                    <a href="/profile/{{Auth::user()->id}}" class="dropdown-item notify-item">
                        <i class="fe-user"></i>
                        <span>الملف الشخصي</span>
                    </a>

                    <!-- item-->
                    <a href="/user/{{Auth::user()->id}}/edit" class="dropdown-item notify-item">
                        <i class="fe-settings"></i>
                        <span>الاعدادات</span>
                    </a>



                    <div class="dropdown-divider"></div>

                    <!-- item-->
                    <a onclick="localStorage['cart'] = '[]'" href="{{ route('logout') }}" class="dropdown-item notify-item">
                        <i class="fe-log-out"></i>
                        <span>الخروج</span>
                    </a>

                </div>
            </li>


        </ul>

        <!-- LOGO -->
        <div class="logo-box">
            <a href="/dashboard" class="logo logo-dark text-center">
                <span class="logo-sm">
                    <h2 class="text-white">clothesforgift</h2>
                    <!-- <span class="logo-lg-text-light">UBold</span> -->
                </span>
                <span class="logo-lg">
                    <h2>clothesforgift</h2>

                    <!-- <span class="logo-lg-text-light">U</span> -->
                </span>
            </a>

            <a href="/" class="logo logo-light text-center">
                <span class="logo-sm m-auto">
                    <h2 class="text-white">CG</h2>

                </span>
                <span class="logo-lg">
                    <h2 class="text-white">clothesforgift</h2>

                </span>
            </a>
        </div>

        <ul class="list-unstyled topnav-menu topnav-menu-left m-0">

            <li>
                <button class="button-menu-mobile waves-effect waves-light">
                    <i class="fe-menu"></i>
                </button>
            </li>
            <li>
                <button onclick="window.location.assign('/cart')" style="margin-top: 16px !important" class="btn mt-2 waves-effect waves-light">
                    <i style="font-size: 18px;
                    color: white;" class=" fas fa-shopping-cart
                    "></i>
                    <span id="cart-number" class="badge bg-warning text-dark ms-1">0</span>
                </button>
            </li>
            <li class="d-none d-lg-block">
                <form class="app-search">
                    <div class="app-search-box dropdown">
                        <div class="input-group">
                            <input onkeydown="search(event, this)" type="search" class="form-control" placeholder="Search..." id="top-search">
                            <button class="btn input-group-text" type="submit">
                                <i class="fe-search"></i>
                            </button>
                        </div>
                        <div class="dropdown-menu dropdown-lg" id="search-dropdown">
                            <!-- item-->


                            <div class="notification-list products-list">

                            </div>

                        </div>
                    </div>
                </form>
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
</div>
<!-- ========== Left Sidebar Start ========== -->
<div class="left-side-menu">

    <div class="h-100" data-simplebar>

        <!-- User box -->
        <div class="user-box text-center">
            <img src="../assets/images/users/user-6.jpg" alt="user-img" title="Mat Helme"
                class="rounded-circle avatar-md">
            <div class="dropdown">
                <a href="javascript: void(0);" class="text-dark dropdown-toggle h5 mt-2 mb-1 d-block"
                    data-bs-toggle="dropdown">Stanley Parker</a>
                <div class="dropdown-menu user-pro-dropdown">

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-user me-1"></i>
                        <span>My Account</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-settings me-1"></i>
                        <span>Settings</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-lock me-1"></i>
                        <span>Lock Screen</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-log-out me-1"></i>
                        <span>Logout</span>
                    </a>

                </div>
            </div>
            <p class="text-muted">Admin Head</p>
        </div>

        <!--- Sidemenu -->
        @include('layouts.components.admin.sidemenu')

        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
