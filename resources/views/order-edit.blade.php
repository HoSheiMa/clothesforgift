@extends('layouts.app')

@section('js')
<script src="/js/order-edit.js"></script>
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
                                            <li class="breadcrumb-item active">بيانات الطلب</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">بيانات الطلب</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title mb-3">توصيل الطلب</h4>

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-4">
                                                    <h5 class="mt-0">رقم الطلب</h5>
                                                    <p>#{{$order->id}}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="track-order-list">
                                            <ul class="list-unstyled">
                                                <li class="completed">
                                                    <h5 class="mt-0 mb-1">في المخزن</h5>
                                                    <p class="text-muted">{{$order->created_at}} </p>
                                                </li>
                                                <li class="{{ in_array($order->Shipping_status ,["delivery","delivered", "Partially delivered", "Refused to receive", "Delayed", "returned product"] ) ? "completed" : "d-none"}}">
                                                    <h5 class="mt-0 mb-1">جاري التوصيل</h5>
                                                    <p class="text-muted">{{$order->Shipping_company}}</p>
                                                </li>
                                                <li>
                                                    <span class="{{ in_array($order->Shipping_status ,["delivered", "Partially delivered", "Refused to receive", "Delayed", "returned product"] ) ? "completed" : "active-dot dot"}}"></span>
                                                    <h5 class="mt-0 mb-1">@php
                                                        $color = '';
                                                        $text = "";
                                                        switch ($order->Shipping_status) {
                                                            case "awaiting":
                            $color = "bg-soft-warning text-warning";
                            $text = "لا يوجد شركة شحن";
                            break;
                        case "delivery":
                        $color = "bg-soft-warning text-warning";
                        $text = "قيد التوصيل ";
                            break;
                        case "delivered":
                        $color = "bg-soft-success text-success";
                        $text = "تم التسليم ";
                            break;
                        case "Partially delivered":
                        $color = "bg-soft-warning text-warning";
                        $text = "مسلم جزئي ";
                            break;
                        case "Refused to receive":
                        $color = "bg-soft-danger text-danger";
                        $text = "رفض الاستلام  ";
                            break;
                        case "Delayed":
                        $color = "bg-soft-warning text-warning";
                        $text = "مؤجل  ";
                            break;
                        case "returned product":
                        $color = "bg-soft-danger text-danger";
                        $text = "مرتجع  ";
                            break;
                                                        }
                                                        echo $text;

                                                        @endphp</h5>
                                                    <p class="text-muted"></p>
                                                </li>
                                            </ul>
                                            @if($order->Shipping_note)
                                            <div class="alert alert-warning bg-warning text-white border-0" role="alert">
                                                {{$order->Shipping_note}}
                                            </div>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-body">

                                        <div class="table-responsive">
                                            <table class="table table-bordered table-centered mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>المنتج</th>
                                                        <th>الصورة</th>
                                                        <th>السعر</th>
                                                        <th>الكمية</th>
                                                        <th>المقاس</th>
                                                        <th>اللون</th>
                                                        <th>الاجمالي</th>
                                                        <th>المكسب</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($order->items as $item)
                                                    <tr>
                                                        

                                                        <th scope="row">{{($item->product_name) }}
                                                            @if ((Auth::user()->id == $order->created_by && $order->status == "new") || Auth::user()->role == "admin" || Auth::user()->role == "support")
                                                            <a href="#"  onclick="remove(this, {{$item->id}});javascript:void(0);" class="action-icon"> <i class="mdi mdi-delete"></i></a>

                                                                @if(App\Models\colors::find($item->color_id))
                                                                <a href="#"  onclick="edit(this, {{$item->id}});javascript:void(0);" class="action-icon"> <i class="fas fa-pen  "></i></a>
                                                                @endif
                                                            @endif

                                                        </th>
                                                        <td><img src="{{($item->product_image)}}" alt="product-img" height="32"></td>
                                                        <td>{{$item->needed_price}}</td>
                                                        <td>{{$item->needed}}</td>
                                                        <td>{{($item->size) }}</td>
                                                        <td>{{($item->color)}}</td>
                                                        <td>{{$item->needed * $item->needed_price }}</td>
                                                        <td>{{$item->needed * $item->needed_price - $item->needed *$item->min_price }}</td>
                                                    </tr>
                                                    @endforeach
                                                    <tr>
                                                        @php
                                                            $benefits = 0;
                                                            $total_without_fees = 0;
                                                            $total = 0;

                                                            foreach ($order->items as $key => $_item) {
                                                                $benefits += $_item->benefits;
                                                                $total_without_fees += $_item->needed * $_item->needed_price;
                                                                $total += ($_item->needed * $_item->needed_price) ;
                                                            }
                                                            $total += $order->Shipping_fees;
                                                            if (+$order->discount) {
                                                                $total -= $order->discount;
                                                            }
                                                        @endphp
                                                        <th scope="row" colspan="7" class="text-end">المكسب الاجمالي</th>
                                                        <td><div class="fw-bold">
{{$benefits}}


                                                        </div></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row" colspan="7" class="text-end">سعر الشحن</th>
                                                        <td>{{$order->Shipping_fees}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row" colspan="7" class="text-end">الخصم</th>
                                                        <td>{{$order->discount}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row" colspan="7" class="text-end">السعر الاجمالي بدون الشحن والخصم</th>
                                                        <td>{{$total_without_fees}}</td>
                                                    </tr>

                                                    <tr>
                                                        <th scope="row" colspan="7" class="text-end">السعر الاجمالي</th>
                                                        <td><div class="fw-bold">{{$total}}</div></td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->
                        <div class="btn btn-info" onclick="$('.timeline').toggleClass('d-none')">الملاحظات</div>
                        @if ( Auth::user()->role == "admin" || Auth::user()->role == "support")

                        <div class="btn btn-info" onclick="print('{{$order->id}}')">طباعة</div>
                        <div class="btn btn-info" onclick="updateStatus(this, '{{$order->id}}', '{{$order->status}}', '{{$role}}')">تغير الحالة</div>
                        <div class="btn btn-info" onclick="updateShippingStatus(this, '{{$order->id}}', '{{$order->status}}', '{{$role}}')">  تغير حالة الشحن</div>
                        <div class="btn btn-info" onclick="updateShippingStatus(this, '{{$order->id}}', '{{$order->status}}', '{{$role}}')">  تغير حالة الشحن</div>
                        @endif
                        @if ((Auth::user()->id == $order->created_by && $order->status == "new") || Auth::user()->role == "admin" || Auth::user()->role == "support")
                        <div class="btn btn-info" onclick="add_unlock_Order(this, '{{$order->id}}')">  اضافة منتج</div>

                        @endif
                            <div class="col">
                            <div class="timeline d-none ">

                                @foreach ($order->notes as $key => $note)
                                @if ($key % 2)
                                <article class="timeline-item timeline-item-left">
                                    <div class="timeline-desk">
                                        <div class="timeline-box">
                                            <span class="arrow-alt"></span>
                                            <span class="timeline-icon"><i class="mdi mdi-adjust"></i></span>
                                            <h4 class="mt-0 font-16">{{$note->sender}}</h4>
                                            <p class="text-muted"><small>{{$note->created_at}}</small></p>
                                            <p  style="min-width: 450px;" class="mb-0">{{$note->note}}</p>
                                        </div>
                                    </div>
                                </article>
                                @else
                                <article class="timeline-item">
                                    <div class="timeline-desk">
                                        <div class="timeline-box">
                                            <span class="arrow"></span>
                                            <span class="timeline-icon"><i class="mdi mdi-adjust"></i></span>
                                            <h4 class="mt-0 font-16">{{$note->sender}}</h4>
                                            <p class="text-muted"><small>{{$note->created_at}}</small></p>
                                            <p style="min-width: 450px;" class="mb-0">{{$note->note}}</p>  </div>
                                    </div>
                                </article>

                                @endif

                                @endforeach




                            </div>
                        </div>
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
                        <div class="mt-3 copy-2 d-none">
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
                        <form>
                            <div class="form-group mt-2 mb-2">
                              <label for="exampleInputEmail1">الاسم</label>
                              <input value="{{$order->name}}" type="text" style="text-align: right" name="name" class="form-control"  placeholder="الاسم">
                            </div>
                            @foreach (json_decode($order->address) as $index => $address)

                            <div class="form-group mt-2 mb-2">
                                <label for="exampleInputEmail1">العنوان</label>
                                <div class="row">
                                    <div class="col-9">
                                <input value="{{$address}}" type="text" style="text-align: right" name="address" class="form-control"  placeholder="العنوان">
                                    </div>
                                <div class="col-3">
                                    <button type="button" class="btn" onclick="$(this).closest('.mt-2').after($('.copy').clone());$(this).closest('.mt-2').parent().find('.d-none').removeClass('d-none')">المزيد</button>
                                    @if($index != 0)
                                    <button type="button" class="btn" onclick="$(this).closest('.mt-2').remove()">مسح</button>
                                    @endif
                                </div>
                            </div>
                            </div>
                            @endforeach
                              <div class="form-group mt-2 mb-2">
                                <label for="exampleInputEmail1">الشحن الي</label>
                                <select name="Shipping_to" class="form-control" id="">
                                    <option value="">اختر</option>
                                    @foreach ($Shippings as $Shipping)
                                    <option price="{{ $Shipping->{$role} }}" {{$Shipping->id == $order->Shipping_to ? "selected" : ' '}}  value="{{$Shipping->id}}">{{$Shipping->location}} - {{ $Shipping->{$role} }}</option>
                                @endforeach
                                    </select>
                                </div>
                                @foreach (json_decode($order->phone) as $index => $phone)
                                <div class="form-group mt-2 mb-2">
                                  <label for="exampleInputEmail1">رقم الهاتف </label>
                                  <div class="row">
                                    <div class="col-9">

                                  <input value="{{$phone}}" type="text" style="text-align: right" name="phone" class="form-control"  placeholder="رقم الهاتف ">

                                </div>
                                <div class="col-3">
                                    <button type="button" class="btn" onclick="$(this).closest('.mt-2').after($('.copy-2').clone());$(this).closest('.mt-2').parent().find('.d-none').removeClass('d-none')">المزيد</button>
                                    @if($index != 0)

                                    <button type="button" class="btn" onclick="$(this).closest('.mt-2').remove()">مسح</button>
                                    @endif
                                </div>
                            </div>
                        </div>

                                @endforeach
                        @if (Auth::user()->role == "admin" || Auth::user()->role == "support")

                              <div class="row">

                                <div class="col-sm-12 col-lg-6">
                                    <div class="form-group mt-2 mb-2">
                                    <label for="exampleInputEmail1">قيمة الخصم</label>

                                      <input value="{{$order->discount}}" type="text" style="text-align: right" name="discount" class="form-control"  placeholder="قيمة الخصم">
                                    </div></div>
                                  <div class="col-sm-12 col-lg-6"><div class="form-group mt-2 mb-2">
                                    <label for="exampleInputEmail1">نوع الخصم</label>
                                    <select class="form-select" name="discount_type" id="">
                                        <option value="">لا يوجد خصم</option>
                                        <option {{$order->discount_type == "marketer" ? "selected" : ''}} value="marketer">منسق</option>
                                    </select>
                                  </div></div>
                              </div>
                              @endif
                               <div class="form-group mt-2 mb-2">
                                <label for="exampleInputEmail1">ملاحظة جديدة </label>
                                <textarea type="text" style="text-align: right" name="note" class="form-control"  placeholder="الملاحظات "></textarea>
                              </div>
                              @if ((Auth::user()->id == $order->created_by && $order->status == "new") || Auth::user()->role == "admin" || Auth::user()->role == "support")
                            <button type="button" onclick="updateInfo('{{$role}}', '{{$order->id}}')" type="submit" class="btn btn-primary">حفظ</button>
                              @endif
                        </form>



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
