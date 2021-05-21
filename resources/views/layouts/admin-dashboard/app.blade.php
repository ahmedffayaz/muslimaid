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
    
<style>
    .logo:hover .edit {
	display: block;
}

.edit {
	padding-top: 7px;	
	padding-right: 7px;
	position: absolute;
	right: 0;
	top: 0;
	display: none;
}

.edit a {
	color: #000;
}
.nk-files-view-grid .nk-file-icon-type {
    width: 150px;
    padding: 2rem 0 .5rem 0;
}
@media (min-width: 1200px){
    .nk-files-view-grid .nk-file {
    width: calc(20% - 16px);
}
}
.nk-sidebar .nk-menu > li .nk-menu-sub .nk-menu-link {
    padding-left: 40px;
}
</style>
@php $settings = App\Models\SiteSetting::latest()->get()->pluck('value','type'); @endphp
@php $currency =  0; @endphp
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
    {{-- <script src="{{ asset('admin-dashboard/js/charts/chart-ecommerce.js?ver=2.2.0')}}"></script> --}}
    
{{-- <script>
    $(document).ready(function(){
    
     $(document).on('click', '.pagination a', function(event){
        event.preventDefault(); 
        var route = $('.pagination').attr('route');
        var page = $(this).attr('href').split('page=')[1];
        fetch_data(page,route);
     });
    
     function fetch_data(page,route)
     {
         if(route=='users'){
             pageurl = "{{route('admin.users.fetch')}}?page="
         }
         if(route=='importedcategory'){
             pageurl = "{{route('admin.networks.fetch')}}?page="
         }
         if(route=='stores'){
             pageurl = "{{route('admin.stores.fetch')}}?page="
         }if(route=='categories'){
             pageurl = "{{route('admin.categories.fetch')}}?page="
         }
         if(route=='clicks'){
             pageurl = "{{route('admin.clicks.fetch')}}?page="
         }
         if(route=='commissions'){
             pageurl = "{{route('admin.commissions.fetch')}}?page="
         }
         if(route=='vouchers'){
             pageurl = "{{route('admin.vouchers.fetch')}}?page="
         }
         if(route=='reviews'){
             pageurl = "{{route('admin.reviews.fetch')}}?page="
         }
      var _token = $("input[name=_token]").val();
      $.ajax({
          url:pageurl+page,
          method:"POST",
          data:{_token:_token, page:page},
          success:function(data)
          {
           $('#table-data').html(data);
           $('html, body').animate({ scrollTop: 0 }, 'slow');
          }
        });
     }
    
    });
    </script>  --}}
    <script>
        function exportTasks(_this) {
           let _url = $(_this).data('href');
           window.location.href = _url;
        }
     </script>
     <script>
        $('div.alert').not('.alert-important').delay(3000).fadeOut(350);
        </script>
     <!-- page scripts -->
   
    @stack('scripts')
</body>

</html>