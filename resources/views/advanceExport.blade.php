
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="/assets/css/filter_multi_select.css" />

    <style>
        .placeholder {
            display: inline-block;
    min-height: 1em;
    vertical-align: middle;
            color: #000;
            cursor: pointer;
            background-color: #fff !important;
    background-color: currentColor;
    opacity: 0.5;
        }
        .custom-control-label::before{
            background-color: #aaa !important;
        }
        .item {
            color: #000 !important;
        }
    </style>
@endsection
@section('js')
<script src="/js/bones.js"></script>
<script src="/assets/js/filter-multi-select-bundle.min.js"></script>
<script>
    window.selected = []
    $('#search').filterMultiSelect();
    $(function () {
    $('#search').on('optionselected', function(e) {
      window.selected.push(e.originalEvent.detail.value)
      $('#selector').val(window.selected);

    });
    $('#search').on('optiondeselected', function(e) {
      window.selected = window.selected.filter((v) => v !== e.originalEvent.detail.value)
      $('#selector').val(window.selected);
    });
  });

</script>
@endsection
@section('content')
        <!-- Begin page -->
        <div id="wrapper">
            @include('layouts.components.admin.navbar')

            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page p-3">

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">طلب تصدير</div>

                <div class="card-body">
                    <form method="POST" action="" >
                        @csrf
                        <input id="selector" name="selector"  type="hidden" value="">
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">اختار الطلب</label>

                            <div class="col-md-6">
                               <select id="search" name="test" class="form-select" multiple="">
                                    @foreach ($orders as $order)
                                    <option value="{{$order->id}}">order: {{$order->id}}</option>

                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button class="btn btn-primary m-1">
                                    تصدير
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
