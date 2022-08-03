@extends('layouts.app')
@section('js')
<script src="/js/show-products.js"></script>
@endsection
@section('content')
        <!-- Begin page -->
        <div id="wrapper">
            @include('layouts.components.admin.navbar')

            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->
            <script>
                @php
                    $product->colors = $product->colors
                @endphp
                product = @php echo json_encode($product) @endphp;
            </script>
            <div class="content-page py-3">
                    <div class="row justify-content-center">
                        <form action="" style="padding: 50px;">
                        <button type="button" onclick="mutliAdd()"class="btn btn-info btn-block my-2 w-100">اضافة الجميع</button>

                        @foreach ($product->colors as $color)
                            <div class="row" id="{{$color->id}}" >
                                <div class="col" >
                                    <div>
                                        <label for=""> اللون</label>
                                        <input name="size" value="{{$color->color}}" disabled type="text" class="form-control"></div>
                                </div>
                                <div class="col">
                                    <label for="">المقاس</label>
                                    <div><input name="color" value="{{$color->size}}"  disabled type="text" class="form-control"></div>
                                </div>
                                <div class="col">
                                    <label for="">الكمية</label>
                                    <div><input name="needed" value="0"  max="{{$color->available}}" type="text" class="form-control"></div>
                                </div>
                                <div class="col">
                                    <label for="">السعر</label>
                                    <div>
                                       <select name="price" id="" class="form-select">
                                        @for ($i= (int)$product->min_price; $i <= (int)$product->max_price; $i= $i+5)
                                         <option value="{{$i}}">{{$i}}</option>
                                        @endfor
                                       </select>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <button type="button" onclick="mutliAdd()" class="btn btn-info btn-block my-2 w-100">اضافة الجميع</button>
                    </form>
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
