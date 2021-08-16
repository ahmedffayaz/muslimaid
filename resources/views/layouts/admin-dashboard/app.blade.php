@php $settings = SiteSetting(); @endphp

<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <base href="../">
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <!-- Fav Icon  -->
    
    <link rel="shortcut icon"
    
    @isset($settings['favicon'])
        @if($settings['favicon'] == 'default.png')
            href="{{asset('admin-dashboard/images/favicon.png')}}"
        @else
            href="{{asset('storage/dashboard/images/logo/'.$settings['favicon'])}}"
        @endif

        @else  
        href="{{asset('admin-dashboard/images/favicon.png')}}"
        
    @endisset>
    
    <!-- Page Title  -->
    <title>{{$settings['website_title']}} | Admin Panel</title>
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="{{ asset('admin-dashboard/css/dashlite.css?ver=2.2.0')}}">
    <link id="skin-default" rel="stylesheet" href="{{ asset('admin-dashboard/css/theme.css?ver=2.2.0')}}">
    <link rel="stylesheet" href="{{asset('vendor/laraberg/css/laraberg.css')}}">
    <script src="https://unpkg.com/react@16.8.6/umd/react.production.min.js"></script>
    <script src="https://unpkg.com/react-dom@16.8.6/umd/react-dom.production.min.js"></script>
    <script src="{{ asset('vendor/laraberg/js/laraberg.js') }}"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/css/bootstrap-colorpicker.min.css" rel="stylesheet">
    @livewireStyles
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
    /* Dropdown Button */
.dropbtn {
  color: #526484;
  padding: 16px;
  border: none;
  font-size: 14px;
}

/* The container <div> - needed to position the dropdown content */
.dropdown {
  position: relative;
  display: inline-block;
}

/* Dropdown Content (Hidden by Default) */
.dropdown-content {
  display: none;
  position: absolute;
  min-width: 225px;
  /* box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2); */
  z-index: 1;
margin-top:10px;
left: 0;
}

/* Links inside the dropdown */
.dropdown-content a {
  color: #526484;
  /* padding: 12px 16px; */
  text-decoration: none;
  display: block;
}

/* Change color of dropdown links on hover */
/* .dropdown-content a:hover {background-color: #ddd;} */

/* Show the dropdown menu on hover */
.dropdown:hover .dropdown-content {display: block;}
@media (min-width: 576px){
    .nk-header-search {
    justify-content: center;
    display: none;
}
}
@media only screen and (max-width: 1199px) {
    .is-compact:not(:hover) .nk-menu-text {
    opacity: 1;
}

.is-compact:not(:hover) .nk-menu-badge, .is-compact:not(:hover) .nk-menu-toggle:after {
    opacity: 1;
}
}
@media (min-width: 1200px){
    .nk-sidebar.is-compact.d-xl-none + .nk-wrap {
    padding-left: 0;
}
.nk-sidebar.is-compact.d-xl-none + .nk-wrap > .nk-header-fixed {
    left: 0;
}
.nk-header-search {
    justify-content: center;
    display: flex;
    align-items: center;
    flex-grow: 1;
}
}
.dropbtn.icon-status-before::before {
    position: absolute;
    border-radius: 50%;
    left: 0px;
    top: -30%;
    height: 10px;
    width: 10px;
    border: 2px solid #fff;
    content: '';
    background: red;
}
.icon-status:after {
    background:red;
}
</style>

@php $settings = App\Models\SiteSetting::latest()->get()->pluck('value','type'); @endphp
@php $currency =  0; @endphp
<!-- site -->
@isset($settings['theme_skin'])
    @if($settings['theme_skin']=='custom')
        @include('layouts.admin-dashboard.includes.admin_colors')
    @endif
@endisset

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
        $('a.dropbtn').click(function(e)
{
    // Special stuff to do when this link is clicked...

    // Cancel the default action
    // e.preventDefault();
});
        
        </script>
     <!-- page scripts -->
     @livewireScripts
    @stack('scripts')
</body>

</html>