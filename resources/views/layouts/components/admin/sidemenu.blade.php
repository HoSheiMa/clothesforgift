
@php
    function is() {
        $arg_list = func_get_args();
        $access = false;
        for ($i = 0; $i < sizeof($arg_list); $i++) {
        if ($arg_list[$i] == Auth::user()->role) {
            $access = true;
            break;
        }
    }

        return $access;
    }
@endphp

<div id="sidebar-menu">
    <li class="d-block d-lg-none">
        <form class="app-search">
            <div class="app-search-box dropdown">
                <div class="input-group">
                    <input onkeydown="search(event, this, 'products-list-2')" type="search" class="form-control" placeholder="Search..." id="top-search-2">
                    <button class="btn input-group-text" type="submit">
                        <i class="fe-search"></i>
                    </button>
                </div>
                    <!-- item-->


                    <div class="notification-list products-list-2">

                    </div>

            </div>
        </form>
    </li>
    <ul id="side-menu">
        <li>
            <a href="/dashboard" >
                <i data-feather="airplay"></i>
                <span> الصفحة الرئيسية </span>
            </a>
    </li>

        <li>
            <a href="#sidebarEcommerce" data-bs-toggle="collapse">
                <i class="fe-box"></i>
                <span> منتجات </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="sidebarEcommerce">
                <ul class="nav-second-level">
                    <li>
                        <a href="/show-products">قائمة المنتجات</a>
                    </li>
                    @if (is('admin', 'seller'))

                    <li>
                        <a href="/products">كل المنتجات</a>
                    </li>
                    <li>
                        <a href="/add-product">اضافة منتج</a>
                    </li>
                    @endif
                </ul>
            </div>
        </li>
        <li>
            <a href="#sidebarEcommerce2" data-bs-toggle="collapse">
                <i class=" fas fa-database
                "></i>
                <span> الطلبات </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="sidebarEcommerce2">
                <ul class="nav-second-level">
                    <li>
                        <a href="/orders?filter=1">

                            @php
                            $r = new Illuminate\Http\Request();
                            $r->filter = '1';
                            $r->size = true;
                            $data = App\Http\Controllers\OrderController::orders($r)['data'];
                            @endphp
                            <span class="badge bg-warning text-dark">
                            {{sizeof($data)}}
                            </span>
                            كل الطلبات
                        </a>
                    </li>

                    @php
                       $translateOrderStatus = [
                            "new" => "جديد",
                            "pending" => "معلق",
                            "confirmed" => "مؤكد",
                            "delay" => "مؤجل",
                            "prepared" =>"تم التجهيز",
                            "delivery" => "قيد التوصيل ",
                            "delivered" => "تم التسليم ",
                            "cancelled" => "ملغي ",
                            "unavailable" => "غير متاح"
                    ];
                        if (Auth::user()->role != "Shippingcompany") {
                            $orderStatus = ["new", "pending",'delay',"confirmed","prepared","delivery","delivered","cancelled", "unavailable"];


                        } else {
                            $orderStatus = ["prepared","delivery","delivered","cancelled"];

                        }
                    @endphp
                    @foreach ($orderStatus as $status)
                    <li>
                        <a href="/orders?filter={{$status}}">
                            @php
                            $r = new Illuminate\Http\Request();
                            $r->filter = $status;
                            $r->size = true;

                            $data = App\Http\Controllers\OrderController::orders($r)['data'];
                            @endphp
                            <span class="badge bg-warning text-dark">
                            {{sizeof($data)}}
                            </span>

                            {{$translateOrderStatus[$status]}}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </li>
        @if (is('admin', 'leader'))
        <li>
            <a href="#sidebarEcommerce3" data-bs-toggle="collapse">
                <i class="fe-user"></i>
                <span> الأعضاء </span>
                <span class="menu-arrow"></span>
            </a>

            <div class="collapse" id="sidebarEcommerce3">
                <ul class="nav-second-level">

                    <li>
                        <a href="/users">جميع الأعضاء </a>
                    </li>
                    @if (is('admin', 'support'))
                    <li>
                        <a href="/users?filter=pending">الأعضاء المعلقة</a>
                    </li>
                    @endif
                    <li>
                        <a href="/add-user">اضافة عضو</a>
                    </li>
                </ul>
            </div>

        </li>
        @endif
        <li>
            <a href="#sidebarEcommerce4" data-bs-toggle="collapse">
                <i class="fas fa-dollar-sign"></i>                <span> طلبات السحب </span>
                <span class="menu-arrow"></span>
            </a>
        <div class="collapse" id="sidebarEcommerce4">
            <ul class="nav-second-level">

                <li>
                    <a href="/withdraw">طلبات السحب</a>
                </li>
                <li>
                    <a href="/add-withdraw">طلب سحب</a>
                </li>
                @if (is('admin'))
                <li>
                    <a href="/withdraw?filter=pending">طلبات السحب المعلقة</a>
                </li>
                <li>
                    <a href="/withdraw/setting">اعدادات طلبات السحب</a>
                </li>
                @endif

            </ul>
        </div>
        </li>
        @if (is('admin'))

        <li class="menu-title mt-2">أخري</li>

        <li>
            <a href="/Shipping">
                <i class="  fas fa-luggage-cart                "></i>
                <span> أماكن الشحن </span>
            </a>
        </li>
        <li>
            <a href="/colors">
                <i class="   fas fa-adjust
                "></i>
                <span> الألوان </span>
            </a>
        </li>
        <li>
            <a href="/sizes">
                <i class="   fas fa-ad
                "></i>
                <span> المقاسات </span>
            </a>
        </li>



        <li>
            <a href="/notif">
                <i class=" fas fa-bell
                " data-feather="message-square"></i>
                <span> الاشعارات </span>
            </a>
        </li>

        <li>
            <a href="/status_action">
                <i class=" fas fa-bell
                " data-feather="message-square"></i>
                <span> وظائف الحالات </span>
            </a>
        </li>
        <li>
            <a href="/export">
                <i class="fa-solid fa-file-export"></i>
                <span> التصدير المتقدم </span>
            </a>
        </li>
        
        @endif

        @if(is('admin', 'support', 'pagesCoordinator'))
        <li>
            <a href="/whatsapp_sender">
                <i class="fa-brands fa-whatsapp"></i>
                <span> رسائل واتساب </span>
            </a>
        </li>
        @endif
        <li>
            <a href="/chats">
                <i class=" fas fa-envelope
                " data-feather="message-square"></i>
                <span> الرسائل </span>
            </a>
        </li>
        <li>
            <a href="/bones">
                <i class="  fas fa-medal
                " data-feather="message-square"></i>
                <span> البونص  </span>
            </a>
        </li>
    </ul>

</div>
