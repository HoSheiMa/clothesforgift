@extends('layouts.app')

@section('js')
<script src="/js/sizes.js"></script>
@endsection
@section('content')
        <!-- Begin page -->
        <div id="wrapper">
            @include('layouts.components.admin.navbar')

            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page p-3">

                <form action="">

                    <div class="row ">
                        <div class="col col-sm-12 col-lg-2 m-1">
                            <input type="text" name="size" required class="form-control  "  placeholder="المقاس"/>
                        </div>
                    </div>

                </form>
                <button onclick="add()" class="btn btn-info m-1">اضافة</button>
                <hr>
                <div class="table-responsive ">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>المقاس</th>
                                <th>اجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sizes as $size)
                            <tr>
                                <th scope="row">{{$size->id}}</th>
                                <td>{{$size->size}}</td>
                                <td>
                                    <a  style="cursor: pointer;" onclick="remove('{{$size->id}}')" class="far fa-trash-alt col text-danger"></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
