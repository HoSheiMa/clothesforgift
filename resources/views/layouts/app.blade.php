<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>SMSCLOTHES</title>

        <!-- App favicon -->
        <link rel="shortcut icon" href="/assets/images/favicon.ico">

        <link href="/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css">
        <!-- App css -->
        <link href="/assets/css/config/modern/bootstrap-rtl.min.css" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
        <link href="/assets/css/config/modern/app-rtl.min.css" rel="stylesheet" type="text/css" id="app-default-stylesheet" />

        <link rel="stylesheet" href="/assets/libs/sweetalert2/sweetalert2.min.css">
        <link rel="stylesheet"  type='text/css' href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <!-- icons -->
        <link href="/assets/css/icons-rtl.min.css" rel="stylesheet" type="text/css" />
        <style>
        .switch-checkbox[type=checkbox]{
            height: 0;
            width: 0;
            visibility: hidden;
        }

        .switch-checkbox-label {
            cursor: pointer;
            text-indent: -9999px;
            width: 60px;
            height: 35px;
            background: grey;
            display: block;
            border-radius: 100px;
            position: relative;
        }

        .switch-checkbox-label:after {
            content: '';
            position: absolute;
            top: 2.5px;
            left: 5px;
            width: 30px;
            height: 30px;
            background: #fff;
            border-radius: 90px;
            transition: 0.3s;
        }

        .switch-checkbox:checked + .switch-checkbox-label {
            background: #bada55;
        }

        .switch-checkbox:checked + .switch-checkbox-label:after {
            left: calc(100% - 5px);
            transform: translateX(-100%);
        }

        .switch-checkbox-label:active:after {
            width: 40px;
        }
        .footer {
            position: inherit !important
        }
</style>
        @yield('css')

    </head>
<body>

    @yield('content')

        <!-- Vendor js -->
        <script src="/assets/js/vendor.min.js"></script>

        <!-- Plugins js-->
        <script src="/assets/libs/jquery-sparkline/jquery.sparkline.min.js"></script>
        <script src="/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script>
        <script src="/assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js"></script>

        <!-- Dashboard 2 init -->
        <script src="/assets/js/pages/dashboard-2.init.js"></script>
        <script src="/assets/libs/sweetalert2/sweetalert2.min.js"></script>

        <!-- App js-->
        @yield('js')
        <script src="/assets/js/app.min.js"></script>
        <script src="/js/app-init.js"></script>

    </body>
</html>
