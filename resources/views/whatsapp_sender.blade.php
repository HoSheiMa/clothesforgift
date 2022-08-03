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
                                @if(Session::has('success'))
                                <div class="alert alert-success" role="alert">
                                    تم الارسال
                                  </div>
                                @endif
                                <form class="mt-4 border-1 p-2" action="/whatsapp_sender" method="post" enctype="multipart/form-data" style="border:1px solid #00000050; border-radius: 3px;">
                                    @csrf
                                    <textarea name="message" class="form-control mt-1" placeholder="الرسال......" style="min-height:250px"></textarea>
                                    ملف مرفق مع الرسالة
                                    <input name="attach" type="file" class="form-control" />
                                    صورة مرفقة مع الرسالة
                                    <input name="image" type="file" class="form-control" />
                                    ملف الارقام
                                    <input name="file" type="file" class="form-control" />
                                <button  class="btn btn-info btn-block mt-1 w-100" style="display: block">ارسال</button>

                                </form>



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

        <script>
            Loading = (el) => {
                el.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
  <span class="sr-only">Loading...</span>`
  el.disabled = true
            }
        </script>
        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

@endsection
