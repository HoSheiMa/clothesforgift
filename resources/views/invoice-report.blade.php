

<html dir="rtl">
    <link href="{{ url('/') }}/assets/css/config/modern/bootstrap-rtl.min.css" rel="stylesheet" type="text/css" id="bs-default-stylesheet">
<style>
    * {
        shadow: 0 !important;
    }
    table, th, td, tr {
      border-width: 1px !important;
      text-align: center !important;
    }
    .breaker { box-decoration-break: slice; }

    </style>
@foreach ($orders as $order)

@foreach ($order->items->chunk(10) as $items)
<div class="  row p-1 breaker " dir="rtl"    >
    <div class="col-12">
        <div class="">
            <div class="">
                <!-- Logo & title -->
                <div class="clearfix">
                    <div class="float-start">
                        <div class="auth-logo">
                            <div class="logo logo-dark">
                                <span class="logo-lg">
                                    <h3>clothesforgift</h3>

                                </span>
                            </div>

                        </div>
                    </div>
                    <div class="float-end">
                        <h4 class="m-0 d-print-none">Invoice</h4>
                    </div>
                </div>
                <div class="row">


                <!-- end row -->

                <div class="col row ">
                    <div class="col-sm-6">
                        <h6>بيانات العميل</h6>
                        <address>
                            {{$order->name}}<br>
                            {{implode(',', json_decode($order->phone))}}
                        </address>
                    </div> <!-- end col -->

                    <div class="col-sm-6">
                        <h6>بيانات التوصيل</h6>
                        <address>
                            {{$order->Shipping_to}}<br>
                            {{implode(',', json_decode($order->address))}}<br>
                        </address>
                    </div> <!-- end col -->
                </div><div class="col row ">
                    <div class="col-12">
                        <div class="">
                           <p> <span class="">  تاريخ الانشاء {{$order->created_at}}</span></p>
                            <p> <span class="">  تاريخ أخر تحديث {{$order->updated_at}}</span></p>
                            <p>
                                <span class="float-start">   الحالة   <span class="">{{$order->status}}</span></span>
                            <br/>

                            <span class="float-start"> طلب رقم {{$order->id}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>

                        </p>
                        </div>
                    </div><!-- end col -->
                </div>
                <!-- end row -->
            </div>

                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table mt-0 table-centered">
                                <thead>
                                <tr>
                                    <th style="width: 10%" class="text-end">المنتج</th>
                                    <th style="width: 10%" class="text-end">المقاس</th>
                                    <th style="width: 10%" class="text-end">اللون</th>
                                    <th style="width: 10%">الكمية</th>
                                    <th style="width: 10%">سعر القطعة</th>
                                    <th style="width: 10%">السعر الاجمالي</th>
                                </tr></thead>
                                <tbody>
                                @foreach ((object)$items as $item)
                                <tr>
                                    <td class="text-end" style="white-space: nowrap;
                                    overflow: hidden;
                                    max-width: 120px;
                                    text-overflow: ellipsis;">{{$item->product_name}}</td>
                                    <td class="text-end">{{($item->size)}}</td>
                                    <td class="text-end">{{($item->color)}}</td>
                                    <td>{{$item->needed}}</td>
                                    <td>{{$item->needed_price}}</td>
                                    <td>{{$item->needed_price * $item->needed}}</td>
                                </tr>

                                @endforeach

                                </tbody>
                            </table>
                        </div> <!-- end table-responsive -->
                    </div> <!-- end col -->
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-6">
                        <div class="clearfix text-right" style="text-align: right;">
                            <h6 class="text-muted">الملاحظات</h6>

                            <pre class="text-muted" style='direction: rtl;white-space: break-spaces;unicode-bidi: embed;'>{{
                                sizeof($order->notes) > 0 ?
                                ($order->notes[sizeof($order->notes )- 1])->note  :
                                $order->note ? $order->note : 'لا يوجد ملاحظات'
                            }}</pre>
                        </div>
                    </div> <!-- end col -->
                    <div class="col-6">
                        <div class="float-start">
                            <p>صافي السعر <strong>{{$order->totalWithoutShipping}}</strong></p>

                            <p>الخصم <strong>{{$order->discount}}</strong></p>

                            <p>الشحن <strong>{{$order->Shipping_fees}}</strong></p>
                            <p>الاجمالي {{$order->total - ($order->discount )}}</h3>
                        </div>
                        <div class="clearfix"></div>
                    </div> <!-- end col -->
                </div>
                <!-- end row -->


            </div>
        </div> <!-- end card -->
    </div> <!-- end col -->
</div>
<pagebreak />

@endforeach
@endforeach

</html>
