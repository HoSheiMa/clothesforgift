@extends('layouts.app')

@section('content')
        <!-- Begin page -->
        <div id="wrapper">
            @include('layouts.components.admin.navbar')

            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page py-3">

                @foreach ($notifs as $notif)
                @if (($notif->for == "All" || $notif->for == Auth::user()->role) && ($notif->type == "alert" && $notif->status == 1))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-alert-outline me-2"></i>
                        {{$notif->message}}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @endforeach

                    <div class="row justify-content-center">
                        <div class="col-md-6 col-xl-3">
                            <div class="widget-rounded-circle card">
                                <div class="card-body">
                                    <div class="row" style="    height: 100px;">
                                        <div class="col-6">
                                            <div class="avatar-lg rounded-circle bg-soft-success border-success border">
                                                <i class="fe-Shipping-cart font-22 avatar-title text-success"></i>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-end">
                                                <h3 class="mt-1"><span data-plugin="counterup">{{Auth::user()->active_balance}}</span></h3>
                                                <p class="text-muted mb-1 text-truncate"> ???????? ??????????</p>
                                            </div>
                                        </div>
                                    </div> <!-- end row-->
                                </div>
                            </div> <!-- end widget-rounded-circle-->
                        </div><div class="col-md-6 col-xl-3">
                            <div class="widget-rounded-circle card">
                                <div class="card-body">
                                    <div class="row" style="    height: 100px;">
                                        <div class="col-6">
                                            <div class="avatar-lg rounded-circle bg-soft-danger border-danger border">
                                                <i class="fe-Shipping-cart font-22 avatar-title text-danger"></i>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-end">
                                                <h3 class="mt-1"><span data-plugin="counterup">{{Auth::user()->pending_balance}}</span></h3>
                                                <p class="text-muted mb-1 text-truncate">????????</p>
                                            </div>
                                        </div>
                                    </div> <!-- end row-->
                                </div>
                            </div> <!-- end widget-rounded-circle-->
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <div class="widget-rounded-circle card">
                                <div class="card-body">
                                    <div class="row" style="    height: 100px;">
                                        <div class="col-6">
                                            <div class="avatar-lg rounded-circle bg-soft-warning border-warning border">
                                                <i class="fe-Shipping-cart font-22 avatar-title text-warning"></i>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-end">
                                                <h3 class="mt-1"><span data-plugin="counterup">{{Auth::user()->withdraw_balance}}</span></h3>
                                                <p class="text-muted mb-1 text-truncate"> ???? ???????????? ????????????????</p>
                                            </div>
                                        </div>
                                    </div> <!-- end row-->
                                </div>
                            </div> <!-- end widget-rounded-circle-->
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <div class="widget-rounded-circle card">
                                <div class="card-body">
                                    <div class="row" style="    height: 100px;">
                                        <div class="col-6">
                                            <div class="avatar-lg rounded-circle bg-soft-info border-info border">
                                                <i class="fe-Shipping-cart font-22 avatar-title text-info"></i>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-end">
                                                <h3 class="mt-1"><span data-plugin="counterup">{{Auth::user()->withdraw_done_balance}}</span></h3>
                                                <p class="text-muted mb-1 text-truncate">???? ?????????? ??????????</p>
                                            </div>
                                        </div>
                                    </div> <!-- end row-->
                                </div>
                            </div> <!-- end widget-rounded-circle-->
                        </div>
                    </div>

                    <hr class="my-4">
                    @php
                        use App\Models\User;
                        use App\Models\Withdraw;
                        use App\Models\Order;
                    @endphp

                    @if(Auth::user()->role == "admin")
                    <div class="row">

                    <div class="col-lg-3">
                        <div class="card border-primary border mb-3">
                            <div class="card-header">
                                <h3>?????? ????????????????
                                </h3>
                            </div>
                            <div class="card-body">
                                <h4 class="card-title text-primary">{{sizeof(User::where('role', 'marketer')->get())}}</h4>
                                <p class="card-text">?????? ????????????????
                                    </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card border-primary border mb-3">
                            <div class="card-header">
                                <h3>
                                    ???????????????? ????????????????



                                </h3>
                            </div>
                            <div class="card-body">
                                <h4 class="card-title text-primary">{{sizeof(Withdraw::where('status', 'delivered')->get())}}</h4>
                                <p class="card-text">
                                    ???????????????? ????????????????


                                    </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card border-primary border mb-3">
                            <div class="card-header">
                                <h3>
                                    ???? ???????????? ????????????????





                                </h3>
                            </div>
                            <div class="card-body">
                                <h4 class="card-title text-primary">{{sizeof(Withdraw::where('status', 'await')->get())}}</h4>
                                <p class="card-text">
                                    ???? ???????????? ????????????????




                                    </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card border-primary border mb-3">
                            <div class="card-header">
                                <h3>
                                    ???????? ????????????????
                                </h3>
                            </div>
                            <div class="card-body">
                                <h4 class="card-title text-primary">{{sizeof(Withdraw::all())}}</h4>
                                <p class="card-text">
                                    ???????? ????????????????
                                    </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card border-primary border mb-3">
                            <div class="card-header">
                                <h3>
                                    ???????? ???????????????? (?????? ????????????)
                                </h3>
                            </div>
                            <div class="card-body">
                                <h4 class="card-title text-primary">{{(Auth::user()->leader_balance)}}</h4>
                                <p class="card-text">
                                    ???????? ???????????????? (?????? ????????????)
                                    </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="card border-primary border mb-3">
                            <div class="card-header">
                                <h3>
                                    ?????????? ??????????


                                </h3>
                            </div>
                            <div class="card-body">
                                <h4 class="card-title text-primary">{{sizeof(Order::where('status', 'new')->get(['id']))}}</h4>
                                <p class="card-text">
                                    ?????????? ??????????


                                    </p>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-3">
                        <div class="card border-primary border mb-3">
                            <div class="card-header">
                                <h3>
                                    ?????????? ??????????
                                </h3>
                            </div>
                            <div class="card-body">
                                <h4 class="card-title text-primary">{{sizeof(Order::where('status', 'pending')->get(['id']))}}</h4>
                                <p class="card-text">
                                    ?????????? ??????????
                                    </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="card border-primary border mb-3">
                            <div class="card-header">
                                <h3>
                                    ?????????? ??????????

                                </h3>
                            </div>
                            <div class="card-body">
                                <h4 class="card-title text-primary">{{sizeof(Order::where('status', 'confirmed')->get(['id']))}}</h4>
                                <p class="card-text">
                                    ?????????? ??????????


                                    </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="card border-primary border mb-3">
                            <div class="card-header">
                                <h3>
                                    ?????????? ?????? ??????????????                                </h3>
                            </div>
                            <div class="card-body">
                                <h4 class="card-title text-primary">{{sizeof(Order::where('status', 'delivery')->get(['id']))}}</h4>
                                <p class="card-text">
                                    ?????????? ?????? ??????????????                                    </p>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-3">
                        <div class="card border-primary border mb-3">
                            <div class="card-header">
                                <h3>
                                    ???? ??????????????

                                </h3>
                            </div>
                            <div class="card-body">
                                <h4 class="card-title text-primary">{{sizeof(Order::where('status', 'delivered')->get(['id']))}}</h4>
                                <p class="card-text">
                                    ???? ??????????????

                                    </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="card border-primary border mb-3">
                            <div class="card-header">
                                <h3>
                                    ?????????? ??????????                                </h3>
                            </div>
                            <div class="card-body">
                                <h4 class="card-title text-primary">{{sizeof(Order::where('status', 'cancelled')->get(['id']))}}</h4>
                                <p class="card-text">
                                    ?????????? ??????????                                    </p>
                            </div>
                        </div>
                    </div>


                </div>
                    @endif


                <!-- Footer Start -->
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <script>document.write(new Date().getFullYear())</script> &copy; Powered by clothesforgift
                            </div>

                        </div>
                    </div>
                </footer>
                <!-- end Footer -->

            </div>

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->


        </div>


        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

@endsection
