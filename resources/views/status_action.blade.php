@extends('layouts.app')
@section('css')
<!-- third party css -->
@endsection
@section('js')
  <!-- third party js -->
    <script src="/js/status_action.js"></script>
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

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

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
                    ];
                                @endphp
                                @foreach ($status as $state)
                                <form class="mt-4 border-1 p-2" action="/status_action_update/{{$state->id}}" method="post" style="border:1px solid #00000050; border-radius: 3px;">
                                    @csrf
                                    <div class="d-flex justify-content-between mt-1">
                                        <h3>حالة {{$translateOrderStatus[$state->name]}} </h3>
                                        <input name="status" {{$state->status == "1" ? "checked" : ""}} type="checkbox" class="switch-checkbox" id="switch-{{$state->name}}" /><label class="switch-checkbox-label" for="switch-{{$state->name}}">Toggle</label>
                                    </div>
                                    <div class="d-flex justify-content-between mt-1">
                                        <h3>مع الفاتورة</h3>
                                        <input name="invoice" {{$state->invoice == "1" ? "checked" : ""}} type="checkbox" class="switch-checkbox" id="switch-2{{$state->name}}" /><label class="switch-checkbox-label" for="switch-2{{$state->name}}">Toggle</label>
                                    </div>
                                    <textarea name="message" class="form-control mt-1" placeholder="الرسال......" style="min-height:250px">{{$state->message}}</textarea>
                                    <button class="btn btn-info btn-block mt-1 w-100" style="display: block">حفظ</button>
                                </form>

                                    @endforeach


                            </div> <!-- end card body-->
                        </div> <!-- end card -->
                    </div><!-- end col-->
                </div>


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
