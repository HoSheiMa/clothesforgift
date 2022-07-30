@extends('layouts.app')

@section('css')
@endsection

@section('js')
<script src="/js/cart.js"></script>
@endsection

@section('content')
<script>
    window.role = @php echo json_encode($role); @endphp
</script>
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
                                            <li class="breadcrumb-item active"></li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">التسوق</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <div class="table-responsive">
                                                    <table class="table table-borderless table-nowrap table-centered mb-0">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>المنتج</th>
                                                                <th>السعر</th>
                                                                <th>الكمية</th>
                                                                <th>اللون</th>
                                                                <th>المقاس</th>
                                                                <th>الاجمالي</th>
                                                                <th>المكسب</th>
                                                                <th style="width: 50px;"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                    </table>
                                                </div> <!-- end table-responsive-->
                                                <div class="d-none mt-3 copy">
                                                    <label for="example-textarea" class="form-label">العنوان:</label>
                                                    <div class="row">
                                                        <div class="col-9">
                                                            <input name="address" class="form-control" id="example-textarea" rows="3" placeholder="" /></div>
                                                        <div class="col-3">
                                                            <button type="button" class="btn" onclick="$(this).closest('.mt-3').after($(this).closest('.mt-3').clone())">المزيد</button>
                                    <button type="button" class="btn" onclick="$(this).closest('.mt-3').remove()">مسح</button>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-3 copy-2 d-none" >
                                                    <label for="example-textarea" class="form-label">رقم الهاتف:</label>
                                                    <div class="row">
                                                        <div class="col-9">

                                                        <input type="tel" maxlength="11" placeholder="01234567891" pattern="[0-9]{11}" name="phone" class="form-control" id="example-textarea" rows="3" placeholder="" />

                                                        </div><div class="col-3">
                                                            <button type="button" class="btn" onclick="$(this).closest('.mt-3').after($(this).closest('.mt-3').clone())">المزيد</button>
                                    <button type="button" class="btn" onclick="$(this).closest('.mt-3').remove()">مسح</button>

                                                        </div>
                                                    </div>
                                                </div>
                                                <form action="">
                                           
                                                <div class="mt-3">
                                                    <label for="example-textarea" class="form-label">الاسم:</label>
                                                    <input name="name" class="form-control" id="example-textarea" rows="3" placeholder="" />
                                                </div>
                                                @if (session('unlocked_orders'))
                                                <div class="mt-3">
                                                    <label for="example-textarea" class="form-label">اضافة الي اوردر:</label>
                                                    <select name="add_for" class="form-control" id="example-textarea" rows="3" placeholder="">
                                                        @foreach (json_decode(session('unlocked_orders')) as $order_id)
                                                        <option value="{{$order_id}}" selected>{{$order_id}}</option>
                                                        @endforeach
                                                        <option value="">طلب جديد</option>
                                                    </select>
                                                </div>

                                                @endif

                                                <div class="mt-3">
                                                    <label for="example-textarea" class="form-label">العنوان:</label>
                                                    <div class="row">
                                                        <div class="col-9">
                                                            <input name="address" class="form-control" id="example-textarea" rows="3" placeholder="" /></div>
                                                        <div class="col-3">
                                                            <button type="button" class="btn" onclick="$(this).closest('.mt-3').after($('.copy').clone());$(this).closest('.mt-3').parent().find('.d-none').removeClass('d-none')">المزيد</button>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3 mt-2">
                                                    <label for="example-select" class="form-label">الشحن الي</label>
                                                    <select onchange="updateInvoice()" id="Shipping-dropdown" name="Shipping_to"class="form-select" >
                                                        @foreach ($Shippings as $Shipping)
                                                            <option price="{{ $Shipping->{$role} }}" value="{{$Shipping->id}}">{{$Shipping->location}} - {{ $Shipping->{$role} }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mt-3">
                                                    <label for="example-textarea" class="form-label">رقم الهاتف:</label>
                                                    <div class="row">
                                                        <div class="col-9">

                                                        <input type="tel" maxlength="11" placeholder="01234567891" pattern="[0-9]{11}" name="phone" class="form-control" id="example-textarea" rows="3" placeholder="" />

                                                        </div><div class="col-3">
                                                            <button type="button" class="btn" onclick="$(this).closest('.mt-3').after($('.copy-2').clone());$(this).closest('.mt-3').parent().find('.d-none').removeClass('d-none')">المزيد</button>

                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Add note input-->
                                                <div class="mt-3">
                                                    <label for="example-textarea" class="form-label">اضافة ملاحظة:</label>
                                                    <textarea name="note" class="form-control" id="example-textarea" rows="3" placeholder=""></textarea>
                                                </div>

                                            </form>
                                                <!-- action buttons-->
                                                <div class="row mt-4">
                                                    <div class="col-sm-6">
                                                        <a href="/show-products" class="btn text-muted d-none d-sm-inline-block btn-link fw-semibold">
                                                            <i class="mdi mdi-arrow-left"></i> اكمال التسوق </a>
                                                    </div> <!-- end col -->
                                                    <div class="col-sm-6">
                                                        <div class="text-sm-end">
                                                            <a href="#" onclick="checkout(this);$(this).attr('disabled')" class="btn btn-danger"><i class="mdi mdi-cart-plus me-1"></i> طلب </a>
                                                        </div>
                                                    </div> <!-- end col -->
                                                </div> <!-- end row-->
                                            </div>
                                            <!-- end col -->

                                            <div class="col-lg-4">
                                                <div class="border p-3 mt-4 mt-lg-0 rounded">
                                                    <h4 class="header-title mb-3">ملخص الطلب</h4>

                                                    <div class="table-responsive">
                                                        <table class="table mb-0">
                                                            <tbody>
                                                                <tr>
                                                                    <td>المكسب :</td>
                                                                    <td id="benefit">0.00</td>
                                                                </tr>
                                                                <tr>
                                                                    <td >سعر الشحن : </td>
                                                                    <td id="Shipping">0.00</td>
                                                                </tr>
                                                                <tr>
                                                                    <th >الاجمالي :</th>
                                                                    <th id="total">0.00</th>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <!-- end table-responsive -->
                                                </div>



                                            </div> <!-- end col -->

                                        </div> <!-- end row -->
                                    </div> <!-- end card-body-->
                                </div> <!-- end card-->
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->

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
