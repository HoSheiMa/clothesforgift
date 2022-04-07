@extends('layouts.app')


@section('css')
<link rel="stylesheet" href="/assets/libs/dropify/css/dropify.min.css">
<link rel="stylesheet" href="/assets/libs/dropify/css/dropify.min.css">
@endsection

@section('js')
<script src="/assets/libs/dropify/js/dropify.min.js"></script>
<script src="/assets/libs/dropzone/min/dropzone.min.js"></script>
<script src="/assets/js/pages/form-fileuploads.init.js"></script>
<script src="/js/product-view.js"></script>
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

        @if(Session::has('done'))
        <div class="alert alert-success my-4" role="alert">
            {{Session::get('done')}}
          </div>
        @endif
        @if ($errors->any())
        @foreach ($errors->all() as $error)
        <div class="alert alert-danger my-1" role="alert">
            {{$error}}
          </div>
        @endforeach
    @endif


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
                        <h4 class="page-title">معلومات المنتج</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-5">

                                    <div class="tab-content pt-0">
                                        <div class="tab-pane active show" id="product-1-item">
                                            <img src="{{$product->icon}}" alt="" class="img-fluid mx-auto d-block rounded">
                                        </div>
                                        @foreach ($images as $key => $image)
                                        <div class="tab-pane" id="product-{{$key+2}}-item">
                                            <img src="{{$image->url}}" alt="" class="img-fluid mx-auto d-block rounded">
                                        </div>
                                        @endforeach
                                    </div>

                                    <ul class="nav nav-pills nav-justified">
                                        <li class="nav-item">
                                            <a href="#product-1-item" data-bs-toggle="tab" aria-expanded="false" class="nav-link product-thumb active show">
                                                <img src="{{$product->icon}}" alt="" class="img-fluid mx-auto d-block rounded">
                                            </a>
                                        </li>
                                        @foreach ($images as $key => $image)

                                        <li class="nav-item">
                                            <a href="#product-{{$key+2}}-item" data-bs-toggle="tab" aria-expanded="true" class="nav-link product-thumb">
                                                <img src="{{$image->url}}" alt="" class="img-fluid mx-auto d-block rounded">
                                            </a>
                                        </li>
                                        @endforeach

                                    </ul>
                                </div> <!-- end col -->
                                <div class="col-lg-7">
                                    <div class="ps-xl-3 mt-3 mt-xl-0">
                                        {{-- <a href="#" class="text-primary">{{$product->created_by}}</a> --}}
                                        <h4 class="mb-3">{{$product->name}}</h4>
                                        <p class="text-muted float-start me-3">
                                            <span class="mdi mdi-star text-warning"></span>
                                            <span class="mdi mdi-star text-warning"></span>
                                            <span class="mdi mdi-star text-warning"></span>
                                            <span class="mdi mdi-star text-warning"></span>
                                            <span class="mdi mdi-star text-warning"></span>
                                        </p>
                                        <br>
                                        <h4 class="mb-4">السعر :  <b>{{$product->min_price}} جنيه مصري</b></h4>
                                        <h4><span class="badge  {{$available > 0 ? "bg-soft-success text-success" : "bg-soft-danger text-danger"}}  mb-4">{{$available > 0 ? "متوفر" : "غير متوفر"}}</span></h4>
                                        <pre class="text-muted mb-4 p-2 text-center" style="direction: rtl!important ;unicode-bidi: embed;">{{$product->details}}</pre>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div>
                                                    <p class="text-muted"><i class="mdi mdi-checkbox-marked-circle-outline h6 text-primary me-2"></i>شحن سريع</p>
                                                    <p class="text-muted"><i class="mdi mdi-checkbox-marked-circle-outline h6 text-primary me-2"></i>كل معلوماتك في امان</p>
                                                    <p class="text-muted"><i class="mdi mdi-checkbox-marked-circle-outline h6 text-primary me-2"></i>احسن سعر في احسن وقت</p>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            window.colorsList = @php echo json_encode($colors); @endphp;
                                            window.productInfo = @php echo json_encode($product); @endphp;
                                            window.role = @php echo json_encode($role); @endphp;
                                        </script>
                                        @if ($product->status == "approved")
                                        <form class="d-flex flex-wrap align-items-center mb-4">
                                            <label class="my-1 me-2" for="quantityinput">اللون</label>
                                            <div class="me-3 w-100">
                                                <select onchange="updateInputs(event, this, 'color')" class="form-select my-1" id="color">
                                                    <option selected value="">اختر</option>
                                                    @php
                                                        $assets_colors = [];
                                                        foreach ($colors as $key => $value) {
                                                            if (!in_array($value->color, $assets_colors ))
                                                            array_push($assets_colors, $value->color);
                                                        }
                                                    @endphp
                                                    @foreach ($assets_colors as $color)
                                                        <option value="{{$color}}">{{$color}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <br>

                                            <label class="my-1 me-2" for="sizeinput">المقاس</label>
                                            <div class="me-sm-3 w-100">
                                                <select name="size" class="form-select my-1" id="size" onchange="updateInputs(event, this, 'size')">
                                                    <option selected value="">اختر</option>
                                                </select>
                                            </div>
                                            <br />
                                            <label class="my-1 me-2" for="pirce">السعر</label>
                                            <div class="me-sm-3 w-100">
                                                <select onchange="updatePrice()" name="price" class="form-select my-1" id="price">
                                                    <option selected value="">اختر</option>
                                                </select>
                                            </div>
                                            <label class="my-1 me-2" for="sizeinput">الكمية</label>
                                            <div class="me-sm-3 w-100">
                                                <select onchange="updatePrice()" name="available" class="form-select my-1" id="available">
                                                    <option selected value="">اختر</option>
                                                </select>
                                            </div>
                                            <div class="mt-2 mb-2 me-2" id="">
                                                 المكسب الاجمالي:

                                                <strong id="total">0.00</strong>
                                            </div>

                                        </form>

                                        <div>
                                            <button id="love" onclick="love(this,  '{{$product->id}}')"  type="button" class="btn btn-danger me-2"><i class="mdi mdi-heart-outline"></i></button>
                                            <button onclick="addToCart(this, '{{$product->id}}')" type="button" class="btn btn-success waves-effect waves-light">
                                                <span class="btn-label"><i class="mdi mdi-cart"></i></span>Add to cart
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                </div> <!-- end col -->
                            </div>
                            <!-- end row -->


                            <div class="table-responsive mt-4">
                                <table class="table table-bordered table-centered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>اللون</th>
                                            <th>المقاس</th>
                                            <th>أقل سعر</th>
                                            <th>أعلى سعر</th>
                                            <th>متوفر</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($colors as $color)
                                        <tr>
                                            <td>{{$color->color}}</td>
                                            <td>{{$color->size}}</td>
                                            <td>{{$product->min_price}}
                                            </td>
                                            <td>{{$product->max_price}}</td>
                                            <td>{{$color->available}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> <!-- end card-->
                </div> <!-- end col-->
            </div>
            <!-- end row-->

        </div> <!-- container -->

    </div> <!-- content -->



        <!-- Footer Start -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <script>
                            document.write(new Date().getFullYear())

                        </script> &copy; Powered by SMSCLOTHES
                    </div>
                    <div class="col-md-6">
                        <div class="text-md-end footer-links d-none d-sm-block">
                            <a href="javascript:void(0);">About Us</a>
                            <a href="javascript:void(0);">Help</a>
                            <a href="javascript:void(0);">Contact Us</a>
                        </div>
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
