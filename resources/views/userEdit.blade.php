@extends('layouts.app')

@section('js')

<script src="/js/users.js"></script>@endsection
@section('content')
        <!-- Begin page -->
        <div id="wrapper">
            @include('layouts.components.admin.navbar')

            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">
                <div class="container py-4">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">تعديل</div>

                                <div class="card-body">
                                    <form >
                                        @csrf

                                        <div class="form-group row">
                                            <label for="name"  class="col-md-4 col-form-label text-md-right">الاسم</label>

                                            <div class="col-md-6">
                                                <input id="name" value='{{$user->name}}' type="text" class="form-control m-1 @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                                @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="phone"  class="col-md-4 col-form-label text-md-right">رقم الهاتف</label>

                                            <div class="col-md-6">
                                                <input id="phone" value='{{$user->phone}}' type="text" class="form-control m-1 @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="name" autofocus>

                                                @error('phone')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="email" class="col-md-4 col-form-label text-md-right">البريد الاكتروني</label>

                                            <div class="col-md-6">
                                                <input disabled id="email" value='{{$user->email}}' type="email" class="form-control m-1 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="password" class="col-md-4 col-form-label text-md-right"> كلمة السر</label>

                                            <div class="col-md-6">
                                                <input id="password" type="password" class="form-control m-1 @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">تأكيد كلمة السر</label>

                                            <div class="col-md-6">
                                                <input id="password-confirm" type="password" class="form-control m-1" name="password_confirmation" required autocomplete="new-password">
                                            </div>
                                        </div>
                                        <input type="hidden" name="id" value="{{$user->id}}">
                                        <div class="form-group row">
                                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">نوع الحساب</label>
                                            <div class="col-md-6">
                                                <select {{Auth::user()->role !== "admin"  ? "disabled" : ""}} name="role" class="form-select m-1" aria-label="Default select example">

                                                    <option {{$user->role === "marketer"? 'selected' : ''}} value="marketer">مسوق</option>
                                                    <option {{$user->role === "seller"? 'selected' : ''}}  value="seller">تاجر</option>
                                                    <option  {{$user->role === "leader"? 'selected' : ''}}  value="leader">ليدر</option>
                                                    <option  {{$user->role === "admin"? 'selected' : ''}}  value="admin">ادمن</option>
                                                    <option  {{$user->role === "pagesCoordinator"? 'selected' : ''}}  value="pagesCoordinator">منسق صفحات</option>
                                                    <option  {{$user->role === "Shippingcompany"? 'selected' : ''}}  value="Shippingcompany">شركة شحن</option>
                                                    <option  {{$user->role === "support"? 'selected' : ''}}  value="support">دعم فني</option>
                                                  </select>                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">الحالة</label>

                                            <div class="col-md-6">
                                                <select {{Auth::user()->role !== "admin" && Auth::user()->role !== "support" ? "disabled" : ""}}  name="blocked" class="form-select m-1" aria-label="Default select example">

                                                    <option {{$user->blocked === "true"? 'selected' : ''}} value="true">محظور</option>
                                                    <option  {{$user->blocked === "false"? 'selected' : ''}}  value="false">نشط</option>
                                                  </select>                            </div>
                                        </div>
                                        @if(Auth::user()->role == "leader" ||Auth::user()->role == "admin")

                                        <div class="form-group row">
                                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">ليدر</label>
                                            <div class="col-md-6">
                                                <input id="" @if(Auth::user()->role == "leader") checked disabled @endif type="checkbox" class="form-check-input m-1" name="isleader"  >
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="customRange3" class="col-md-4 col-form-label text-md-right">حصة الليدر</label>
                                        <div class="col-md-6">
                                            <input disabled type="range" onchange="$('#ratio').text(`النسبة : ${$(this).val()}%`)" class="form-range" min="0" max="100" step="1" value="{{$user->leader_ratio}}" name="leader_ratio" id="customRange3">
                                        </div>
                                        <span id="ratio">النسبة : {{$user->leader_ratio}}%</span>
                                    </div>
                                        @endif

                                        <div class="form-group row mb-0">
                                            <div class="col-md-6 offset-md-4">
                                                <button type="button" onclick="updateUser()" class="btn btn-primary m-1">
                                                    تعديل
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
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
