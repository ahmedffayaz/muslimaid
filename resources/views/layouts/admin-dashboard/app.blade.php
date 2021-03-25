<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <base href="../">
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="./images/favicon.png">
    <!-- Page Title  -->
    <title>Cashback Reborn | Admin Panel</title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="{{ asset('admin-dashboard/css/dashlite.css?ver=2.2.0')}}">
    <link id="skin-default" rel="stylesheet" href="{{ asset('admin-dashboard/css/theme.css?ver=2.2.0')}}">
</head>

<body class="nk-body bg-lighter npc-default has-sidebar ">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- sidebar -->
            @include('layouts.admin-dashboard.includes.sidebar')
            <!-- /sidebar-->
            <!-- wrap @s -->
            <div class="nk-wrap ">
                <!-- header -->
                @include('layouts.admin-dashboard.includes.header')
                <!-- /header -->
                
                <!-- content @s -->
                @yield('content')
                <!-- content @e -->

                <!-- footer -->
                @include('layouts.admin-dashboard.includes.footer')

                <!-- /footer -->
               
            </div>
            <!-- wrap @e -->
        </div>
        <!-- main @e -->
    </div>
    <!-- app-root @e -->
    <!-- JavaScript -->
    <script src="{{ asset('admin-dashboard/js/bundle.js?ver=2.2.0')}}"></script>
    <script src="{{ asset('admin-dashboard/js/scripts.js?ver=2.2.0')}}"></script>
    <script src="{{ asset('admin-dashboard/js/charts/chart-ecommerce.js?ver=2.2.0')}}"></script>
</body>

</html>