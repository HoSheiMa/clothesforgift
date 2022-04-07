@extends('layouts.app')

@section('js')
<script src="/js/show-products.js"></script>
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
                <div class="content">

                    <!-- Start Content-->
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item active">المنتجات</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">المنتجات</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row justify-content-between">
                                            <div class="col-auto">
                                                <form class="d-flex flex-wrap align-items-center">

                                                    <label for="status-select" class="me-2">التصنيف</label>
                                                    <div class="me-sm-3">
                                                        <select onchange="recycleView(this.value)" class="form-select my-1 my-lg-0" id="status-select">
                                                            <option value="*">الكل</option>
                                                            <option value="loved">مفضل</option>
                                                            <option value="رجالي">رجالي</option>
                                                            <option value="حريمي">حريمي</option>
                                                            <option value="اطفالي">اطفالي</option>
                                                        </select>
                                                    </div>
                                                </form>
                                            </div>
                                        </div> <!-- end row -->
                                    </div>
                                </div> <!-- end card -->
                            </div> <!-- end col-->
                        </div>
                        <!-- end row-->

                        <div class="row" id="contents">


                        </div>
                        <!-- end row-->

                    </div> <!-- container -->

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
