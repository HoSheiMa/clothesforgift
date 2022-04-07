@extends('layouts.app')

@section('js')
<script src="/js/Shipping.js"></script>
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
                            <input type="text" name="location" required class="form-control  "  placeholder="العنوان"/>
                        </div>
                        <div class="col col-sm-12 col-lg-1 m-1">
                            <input  type="number" min="1" onchange="$(`form input[type='number']`).val($(this).val())" required  class="form-control  "  placeholder="الجميع"/>
                        </div>
                        <div class="col col-sm-12 col-lg-1 m-1">
                            <input name="admin" type="number" min="1" required  class="form-control  "  placeholder="ادمن"/>
                        </div>
                        <div class="col col-sm-12 col-lg-1 m-1">
                            <input name="support" type="number" min="1" required  class="form-control  "  placeholder="دعم"/>
                        </div>
                        <div class="col col-sm-12 col-lg-1 m-1">
                            <input name="pagesCoordinator" type="number" min="1" required class="form-control  "  placeholder="منسق"/>
                        </div>
                        <div class="col col-sm-12 col-lg-1 m-1">
                            <input name="leader" type="number" min="1" required class="form-control  "  placeholder="ليدر"/>
                        </div><div class="col col-sm-12 col-lg-1 m-1">
                            <input name="marketer" type="number" min="1" required class="form-control  "  placeholder="مسوق"/>
                        </div><div class="col col-sm-12 col-lg-1 m-1">
                            <input name="seller" type="number" min="1" required class="form-control  "  placeholder="تاجر"/>
                        </div>
                    </div>

                </form>

                <button onclick="add()" class="btn btn-info m-1">اضافة</button>
                <hr>
                <div class="table-responsive ">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>العنوان</th>
                                <th>ادمن</th>
                                <th>دعم</th>
                                <th>منسق</th>
                                <th>ليدر</th>
                                <th>مسوق</th>
                                <th>تاجر</th>
                                <th>اجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($places as $place)
                            <tr>
                                <th scope="row">{{$place->location}}</th>
                                <td>{{$place->admin}}</td>
                                <td>{{$place->support}}</td>
                                <td>{{$place->pagesCoordinator}}</td>
                                <td>{{$place->leader}}</td>
                                <td>{{$place->marketer}}</td>
                                <td>{{$place->seller}}</td>
                                <td>
                                    <a  style="cursor: pointer;" onclick="remove('{{$place->id}}')" class="far fa-trash-alt col text-danger"></a>
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
