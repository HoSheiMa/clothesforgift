
@extends('layouts.app')

@section('js')
<script src="/js/bones.js"></script>
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
                @if (Auth::user()->role == "admin")
                <form action="">

                    <div class=" ">
                        <div class="col col-sm-12 col-lg-2 m-1">
                            <input type="number" name="target" required class="form-control  "  placeholder="الهدف"/>
                        </div>
                        <br>

                        <div class="col col-sm-12 col-lg-2 m-1">
                            <input type="number" name="bones" required class="form-control  "  placeholder="البونص"/>
                        </div>
                        <br>
                        <div class="col col-sm-12 col-lg-2 m-1">
                            <label for="">بونص ليدر</label>
                            <input type="checkbox" name="for_leader" required class="form-checkbox  "  placeholder="البونص"/>
                        </div>

                    </div>

                </form>

                <button onclick="add()" class="btn btn-info m-1">اضافة</button>
                <hr>
                <div class="table-responsive ">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>الهدف</th>
                                <th>البونص </th>
                                <th>بونص ليدر </th>
                                <th>عدد المحققين</th>
                                <th>اجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($Bones as $_Bones)
                            <tr>
                                <th scope="row">{{$_Bones->target}}</th>
                                <th scope="row">{{$_Bones->bones}}</th>
                                <th scope="row">{{$_Bones->type}}</th>
                                <th scope="row">{{sizeof(json_decode($_Bones->achievers))}}</th>
                                <td>
                                    <a  style="cursor: pointer;" onclick="remove('{{$_Bones->id}}')" class="far fa-trash-alt col text-danger"></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="row">

                    @php
                    $get_bonus = 0;
                    @endphp
                   @foreach ($Bones  as $_Bones)
                        @if(in_array(Auth::user()->id, json_decode($_Bones->achievers)   ))
                        @php $get_bonus++; @endphp

                        <div class="col-md-4">
                            <div class="card card-inverse text-white">
                                <img class="card-img img-fluid" src="../assets/images/small/img-7.jpg" alt="Card image">
                                <div class="card-img-overlay">
                                    <h5 class="card-title text-white text-center" >انجاز, كبست {{$_Bones->bones}}</h5>
                                    <p class="card-text text-center"> <img src="/assets/achieve.png" width="100" /></p>
                                    <p class="card-text text-center">
                                        <small class="">لقد حققت {{$_Bones->target}} من الاموال المسحوبة من الموقع</small>
                                    </p>
                                </div>
                            </div> <!-- end card-->
                        </div>
                        @endif
                   @endforeach
                   @if (sizeof($Bones) == 0  || $get_bonus == 0 )
                   <h1 class="text-center">
                       لا يوجد بيانات
                   </h1>                    @endif
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
