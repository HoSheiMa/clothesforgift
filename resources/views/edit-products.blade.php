@extends('layouts.app')


@section('css')
<link rel="stylesheet" href="/assets/libs/dropify/css/dropify.min.css">
<link rel="stylesheet" href="/assets/libs/dropify/css/dropify.min.css">
@endsection

@section('js')
<script src="/assets/libs/dropify/js/dropify.min.js"></script>
<script src="/assets/libs/dropzone/min/dropzone.min.js"></script>
<script src="/assets/js/pages/form-fileuploads.init.js"></script>
<script src="/js/add-product.js"></script>
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
        <form action="" class="py-4" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="simpleinput" class="form-label">الاسم</label>
                <input required value='{{$product->name}}' type="text" name="name" id="simpleinput" class="form-control">
            </div>
            <div class="mb-3">
                <label for="simpleinput" class="form-label">سعر الشراء </label>
                <input required value='{{$product->price}}'  type="number" name="price" id="simpleinput" class="form-control">
            </div>@if (Auth::user()->role != "seller")
            <div class="mb-3">
                <label for="simpleinput" class="form-label">الحد الأدنى </label>
                <input required value='{{$product->min_price}}'  type="number" name="min_price" id="simpleinput" class="form-control">
            </div>
            <div class="mb-3">
                <label for="simpleinput" class="form-label">الحد الأقصى</label>
                <input required value='{{$product->max_price}}'  type="number" name="max_price" id="simpleinput" class="form-control">
            </div> @endif
            <div class="mb-3">
                <label for="simpleinput" class="form-label">معلومات حول المنتج</label>
                <textarea required value=''  type="text" name="details" id="simpleinput" class="form-control">{{$product->details}}</textarea>
            </div>
            <div class="mb-3">
                <label for="example-select" class="form-label">اختيار تصنيف</label>
                <select required name="type" class="form-select" id="example-select">
                    <option {{$product->type == "رجالي" ? "selected" : ""}}>رجالي</option>
                    <option {{$product->type == "حريمي" ? "selected" : ""}}>حريمي</option>
                    <option {{$product->type == "اطفالي" ? "selected" : ""}}>اطفالي</option>
                </select>
            </div>
            @if (sizeof($product_colors) == 0)
            <div class="form-row py-1 select-new-color" >
                <hr>
                <div class="form-group col-md-4">
                    <label for="inputState">اللون</label>
                    <select required name="items-colors[]" id="inputState" class="form-control">
                      <option  selected="">اختر...</option>
                      @foreach ($colors as $color)
                        <option  data-color="{{$color->color_code}}">{{$color->color}}</option>
                      @endforeach
                    </select>
                  </div>
                <div class="form-group col-md-4">
                  <label for="inputState">المقاس</label>
                  <select required name="items-sizes[]" id="inputState" class="form-control">
                    <option selected="">اختر...</option>
                    @foreach ($sizes as $size)
                      <option >{{$size->size}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group col-md-2">
                  <label for="inputZip">الكمية</label>
                  <input  required name="items-available[]" type="number" min="1" max="10000" class="form-control" id="inputZip">
                </div>
              </div>

            @endif
            @foreach ($product_colors as $pc)
            <div class="form-row py-1 select-new-color" >
                <hr>
                <div class="form-group col-md-4">
                    <label for="inputState">اللون</label>
                    <select required name="items-colors[]" id="inputState" class="form-control">
                      <option  selected="" value="">اختر...</option>
                      @foreach ($colors as $color)
                        <option {{$pc->color == $color->color ? "selected" : ""}} data-color="{{$color->color_code}}">{{$color->color}}</option>
                      @endforeach
                    </select>
                  </div>
                <div class="form-group col-md-4">
                  <label for="inputState">المقاس</label>
                  <select required name="items-sizes[]" id="inputState" class="form-control">
                    <option selected="" value=""> اختر...</option>
                    @foreach ($sizes as $size)
                      <option {{$pc->size == $size->size ? "selected" : ""}}>{{$size->size}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group col-md-2">
                  <label for="inputZip">الكمية</label>
                  <input value='{{$pc->available}}' required name="items-available[]" type="number" min="1" max="10000" class="form-control" id="inputZip">
                </div>
                <button class="btn btn-danger mt-1" onclick="$(this).parent().remove()"}>delete</button>
              </div>

            @endforeach
            <button onclick="addmoreInputForColor(this)" class="btn btn-blue" type="button">اضافة لون اخر</button>

            <hr>
            <label  class="p-1" for="">اختيار صورة مصغرة</label>
            <div class="row">
                <img style="max-width:100px;" class="col m-1" src="{{$product->icon}}" />
            </div>
            <input  data-max-file-size="5M" type="file" name="icon" data-plugins="dropify" data-height="300">
            <hr>
            <label class="p-1" for="">اختيار صورة اضافية</label>
            @foreach ($images as $image)
            <div>

            <img style="max-width:100px;" class="col m-1" src="{{$image->url}}" />
            <a class="btn btn-danger"  href="/delete/{{$image->id}}">مسح</a>
        </div>
        @endforeach
            <div class="row multi_image">
                <div class="py-2 ">
                    <input  data-max-file-size="5M" type="file" name="images[]" multiple data-plugins="dropify" data-height="300">
                </div>
            </div>

            <hr>
            <div class="row p-3">
                <button type="submit" class="btn btn-soft-success btn-block">تحديث</button>
            </div>

        </form>



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
