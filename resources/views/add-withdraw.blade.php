@extends('layouts.app')
@section('js')
<script src="/js/withdraw.js"></script>
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
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">طلب جديد</div>

                <div class="card-body">
                    <form method="POST" >
                        @csrf
                        <div class="alert alert-warning" role="alert">
                            لا يمكن طلب السحب لأقل من {{$withdraw_limit->value}} جنيه مصري
                          </div>
                          <div class="alert alert-success" role="alert">
                            أنت تمتلك{{Auth::user()->active_balance}} قابل للسحب
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">المبلغ</label>

                            <div class="col-md-6">
                                <input min="100" value="{{Auth::user()->active_balance}}" max="{{Auth::user()->active_balance}}" id="money_needed" type="number"   max="{{Auth::user()->active_balance}}"  class="form-control m-1 @error('name') is-invalid @enderror" name="money_needed" value="{{ old('name') }}" required  autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">نوع الطلب</label>

                            <div class="col-md-6">
                                <select id="email" type="type" class="form-control m-1 @error('email') is-invalid @enderror" name="type" value="{{ old('email') }}" required autocomplete="email">
                                    <option value="في المكتب">المكتب</option>
                                    <option value="موبيل كاش">موبيل كاش</option>
                                </select>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone" class="col-md-4 col-form-label text-md-right">تفاصيل الطلب/ رقم الهاتف</label>

                            <div class="col-md-6">
                                <textarea id="phone" type="text" class="form-control m-1 @error('phone') is-invalid @enderror" name="receiver_details" value="{{ old('phone') }}" required autocomplete="email">
                                </textarea>
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="button" @if(Auth::user()->active_balance < (int)$withdraw_limit->value) disabled @endif onclick="addWithdraw(this)" class="btn btn-primary m-1">
                                    اضافة
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
