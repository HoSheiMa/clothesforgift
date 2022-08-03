@extends('layouts.app')
@section('js')
<script src="/js/users.js"></script>
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

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">تسجيل جديد</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">الاسم</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control m-1 @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">البريد الاكتروني</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control m-1 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone" class="col-md-4 col-form-label text-md-right">رقم الهاتف</label>

                            <div class="col-md-6">
                                <input id="phone" type="phone" class="form-control m-1 @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required autocomplete="email">

                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">كلمة السر</label>

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
                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">نوع الحساب</label>

                            <div class="col-md-6">
                                <select name="role" class="form-select m-1" aria-label="Default select example">
                                    @if(Auth::user()->role == "leader")
                                    <option value="marketer">مسوق</option>
                                    @endif
                                    @if(Auth::user()->role == "support")
                                    <option value="marketer">مسوق</option>
                                    <option value="seller">تاجر</option>
                                    <option value="pagesCoordinator">منسق صفحات</option>
                                    <option value="Shippingcompany">شركة شحن</option>
                                    <option value="leader">ليدر</option>
                                    @endif
                                    @if(Auth::user()->role == "admin")
                                    <option value="marketer">مسوق</option>
                                    <option value="seller">تاجر</option>
                                    <option value="admin">ادمن</option>
                                    <option value="support">دعم فني</option>
                                    <option value="pagesCoordinator">منسق صفحات</option>
                                    <option value="Shippingcompany">شركة شحن</option>
                                    <option value="leader">ليدر</option>
                                    @endif
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
                            <input type="range" onchange="$('#ratio').text(`النسبه : ${$(this).val()}%`)" class="form-range" min="0" max="100" step="1" value="50" name="leader_ratio" id="customRange3">
                        </div>
                        <span id="ratio">النسبة : 50%</span>
                    </div>
                        @endif


                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="button" onclick="addNewUser(this)" class="btn btn-primary m-1">
                                    تسجيل
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
