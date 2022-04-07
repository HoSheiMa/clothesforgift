@extends('layouts.app')

@section('js')
<script src="/js/profile.js"></script>
@endsection
@section('content')
        <!-- Begin page -->
        <div id="wrapper">
            @include('layouts.components.admin.navbar')

            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">

                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box">
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item active">الملف الشخصي</li>
                                    </ol>
                                </div>
                                <h4 class="page-title">الملف الشخصي</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-lg-4 col-xl-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <img src="../assets/images/users/user1.png" class="rounded-circle avatar-lg img-thumbnail" alt="profile-image">

                                    <h4 class="mb-0">{{$user->name}}</h4>
                                    <p class="text-muted">@ {{$user->id}}</p>

                                    <button type="button" onclick="sendUserMessage('{{$user->email}}')" class="btn btn-danger btn-xs waves-effect mb-2 waves-light">ارسال رسالة</button>

                                    <div class="text-start mt-3">
                                        <h4 class="font-13 text-uppercase">عن الملف الشخصي :</h4>
                                        <p class="text-muted font-13 mb-3">
                                            هذا المستخدم يمتلك صلاحيات
                                            {{$user->role}}
                                        </p>
                                        <p class="text-muted mb-2 font-13"><strong>الاسم :</strong> <span class="ms-2">{{$user->name}}</span></p>

                                        <p class="text-muted mb-2 font-13"><strong>رقم الهاتف :</strong><span class="ms-2">{{$user->phone}}</span></p>

                                        <p class="text-muted mb-2 font-13"><strong>البريد الالكتروني :</strong> <span class="ms-2">{{$user->email}}</span></p>

                                    </div>


                                </div>
                            </div> <!-- end card -->


                        </div> <!-- end col-->

                        <div class="col-lg-8 col-xl-8">
                            <div class="card">
                                <div class="card-body">
                                    <ul class="nav nav-pills nav-fill navtab-bg">


                                        @if( $user && Auth::user()->id == $user->id)
                                        <li class="nav-item">
                                            <a href="/user/{{$user->id}}/edit"  class="nav-link">
                                                الاعدادات
                                            </a>
                                        </li>
                                        @endif
                                        </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="aboutme">

                                            <h5 class="mb-4 text-uppercase"><i class="mdi mdi-briefcase me-1"></i>
                                            تم انشاء هذا الحساب {{$user->created_at}}
                                            </h5>


                                           </div>

                                        </div> <!-- end tab-pane -->
                                        <!-- end about me section content -->


                                        <!-- end settings content-->

                                    </div> <!-- end tab-content -->
                                </div>
                            </div> <!-- end card-->

                        </div> <!-- end col -->
                    </div>
                    <!-- end row-->

                </div>


                <!-- Footer Start -->
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <script>document.write(new Date().getFullYear())</script> &copy; Powered by SMSCLOTHES
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
