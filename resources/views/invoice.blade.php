<html >
<head>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.3.0/paper.css">


		<!-- App css -->
		<link href="{{ url('/') }}/assets/css/config/modern/bootstrap-rtl.min.css" rel="stylesheet" type="text/css" id="bs-default-stylesheet">
		<link href="{{ url('/') }}/assets/css/config/modern/app-rtl.min.css" rel="stylesheet" type="text/css" id="app-default-stylesheet">

		<link href="{{ url('/') }}/assets/css/icons.min.css" rel="stylesheet" type="text/css">
        <style>@page { size: A5 landscape }
             table  * {
                padding: 0px !important
            }


        </><style>
 * { font-family: DejaVu Sans, sans-serif; }
</style>

    </head>

    <body class="">
@foreach ($orders as $order)

@foreach ($order->items->chunk(10) as $items)


<div class=" sheet  row  A5 landscape "  style="height:100vh"  >
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Logo & title -->
                <div class="clearfix">
                    <div class="float-start">
                        <div class="auth-logo">
                            <div class="logo logo-dark">
                                <span class="logo-lg" style="background-color: yellow">
                                    <h3>clothesforgift</h3>

                                </span>
                            </div>

                            <div class="logo logo-light">
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
                            {{implode(',', json_decode($order->phone) ? json_decode($order->phone) : [])}}
                        </address>
                    </div> <!-- end col -->

                    <div class="col-sm-6">
                        <h6>بيانات التوصيل</h6>
                        <address>
                            {{$order->Shipping_to}}<br>
                            {{implode(',', json_decode($order->address) ? json_decode($order->address) : [])}}<br>
                        </address>
                    </div> <!-- end col -->
                </div><div class="col row ">
                    <div class="col-12">
                        <div class="">
                           <p> <span class="float-start"> {{$order->created_at}} تاريخ الانشاء</span></p>
                            <p> <span class="float-start"> {{$order->updated_at}} تاريخ أخر تحديث</span></p>
                            <br/>
                            <br/>
                            <p>
                                <span class="float-start"><span class="badge bg-danger">{{$order->status}}</span>   الحالة   </span>
                            <span class="float-start">{{$order->id}} طلب رقم &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>

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
                                    <th style="width: 10%">السعر الاجمالي</th>
                                    <th style="width: 10%">سعر القطعة</th>
                                    <th style="width: 10%">الكمية</th>
                                    <th style="width: 10%" class="text-end">اللون</th>
                                    <th style="width: 10%" class="text-end">المقاس</th>
                                    <th style="width: 10%" class="text-end">المنتج</th>
                                </tr></thead>
                                <tbody>
                                @foreach ((object)$items as $item)
                                <tr>
                                    <td>{{$item->needed_price * $item->needed}}</td>
                                    <td>{{$item->needed_price}}</td>
                                    <td>{{$item->needed}}</td>
                                    <td class="text-end">{{($item->color)}}</td>
                                    <td class="text-end">{{($item->size)}}</td>
                                    <td class="text-end" style="white-space: nowrap;
                                    overflow: hidden;
                                    max-width: 120px;
                                    text-overflow: ellipsis;">{{$item->product_name}}</td>
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

                            <pre class="text-muted" style='direction: rtl;white-space: break-spaces;
                            unicode-bidi: embed;'>
                                @php
                                $notes =  ($order->notes);
                               
                                if (sizeof($notes) > 0){
                                    echo ($notes[sizeof($notes) - 1])->note;
                                } else {
                                    $order->note ? $order->note : 'لا يوجد ملاحظات';
                                }
                                @endphp
                                </pre>
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
</div><div class="page-break"> </div>

@endforeach
@endforeach

<script>
    window.print()
    window.onafterprint = () => {
        window.close();
    }
</script>

</body>
</html>
