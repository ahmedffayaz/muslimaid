@extends('layouts.admin-dashboard.app')
<style>.tree, .tree ul {
    margin:0;
    padding:0;
    list-style:none
}
.tree ul {
    margin-left:1em;
    position:relative
}
.tree ul ul {
    margin-left:.5em
}
.tree ul:before {
    content:"";
    display:block;
    width:0;
    position:absolute;
    top:0;
    bottom:0;
    left:0;
    border-left:1px solid
}
.tree li {
    margin:0;
    padding:0 0 0 1em;
    line-height:2em;
    color:#369;
    font-weight:700;
    position:relative;
    text-transform: capitalize;
}
.tree ul li:before {
    content:"";
    display:block;
    width:10px;
    height:0;
    border-top:1px solid;
    margin-top:-1px;
    position:absolute;
    top:1em;
    left:0
}
.tree ul li:last-child:before {
    background:#fff;
    height:auto;
    top:1em;
    bottom:0
}
.indicator {
    margin-right:5px;
}
.tree li a {
    text-decoration: none;
    color:#369;
}
.tree li button, .tree li button:active, .tree li button:focus {
    text-decoration: none;
    color:#369;
    border:none;
    background:transparent;
    margin:0px 0px 0px 0px;
    padding:0px 0px 0px 0px;
    outline: 0;
}</style>
@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="components-preview wide-md mx-auto">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Categories</h3>
                                <div class="nk-block-des text-soft">
                                    {{-- <p>You have total {{$users->total()}} users.</p> --}}
                                </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            {{-- <li><a href="{{route('admin.users.create')}}" class="btn btn-primary btn-sm" class="btn btn-white btn-outline-light"><em class="icon ni ni-plus"></em><span>Add user</span></a></li>
                                        <li><a href="{{route('admin.users.export')}}" id="export" class="btn btn-success btn-sm" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li> --}}
                                        </ul>
                                    </div>
                                </div><!-- .toggle-wrap -->
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    @include('flash::message')
                    <div class="card card-preview mb-4">
                        <div class="card-inner">
                            <div id="accordion-1" class="accordion accordion-s2">
                                <div class="accordion-item">
                                    <a href="#" class="accordion-head collapsed" data-toggle="collapse" data-target="#accordion-item-1-1">
                                        <h6 class="title">Search</h6>
                                        <span class="accordion-icon"></span>
                                    </a>
                                    {{-- <div class="accordion-body collapse" id="accordion-item-1-1" data-parent="#accordion-1">
                                        <div class="accordion-inner">
                                            <div><form action="{{route('admin.stores.search_stores')}}" class="form-validate is-alter search_form" method="POST">
                                                @csrf
                                                <div class="row g-4">
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="pay-amount-1">Name</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="pay-amount-1" value="" name="name">
                                                            </div>
                                                        </div>
                                                    </div>
                                                
                                                
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="type">Regestration Type</label>
                                                            <div class="form-control-wrap ">
                                                                
                                                                    <select class="form-select form-control" id="type" name="type">
                                                                        <option value="-1">Any</option>
                                                                        
                                                                        
                                                                        <option value="sign up">Sign up</option>
                                                                        <option value="social">Social</option>
                                                                        
                                                                    </select>
                                                            
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <div class="form-group">
                                                            <label class="form-label" for="status">Status</label>
                                                            <div class="form-control-wrap ">
                                                                
                                                                    <select class="form-select form-control" id="status" name="status">
                                                                        <option value="-1">Any</option>
                                                                        
                                                                        
                                                                        <option value="1">Active</option>
                                                                        <option value="0">In-active</option>
                                                                        
                                                                    </select>
                                                            
                                                            </div>
                                                        </div>
                                                    </div>
                                                
                                                    
                                                    
                                                                        
                                                    <div class="col-3 align-self-end">
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-success btn-block">Search</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form></div>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="nk-block">
                        <div class="card card-stretch">
                            <div class="card-inner-group">
                                
                                <div class="card-inner">
                                
        
                                    <h5 class="title mb-3">All Categories</h5>
                                            
                                    <ul id="tree1">
                                        @foreach($categories as $category)
                                            <li>
                                                <span class="float-right">
                                                    <a href="" cashback-id='' class='cashback-edit'><em class="icon ni ni-edit"></em></a>
                                                    <a class='cashback-edi'> <em class="icon ni ni-trash-fill"></em></a>
                                                </span>
                                                <em class="icon ni ni-db-fill text-primary"></em> {{ $category->name }}
                                               
    
                
                                                @if(count($category->childs))
                                                    @include('admin-dashboard.categories.child',['childs' => $category->childs])
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                  
                                      
                                
                                </div><!-- .card-inner -->
                            
                            </div><!-- .card-inner-group -->
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
            </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script>
    $(document).ready(function(){
     $(document).on('click', '.pagination a', function(event){
        event.preventDefault(); 
        var route = $('.pagination').attr('route');
        var page = $(this).attr('href').split('page=')[1];
        
         if(route=='index'){
            
             pageurl = "{{route('admin.users.fetch')}}?page="
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

         if(route=='search'){
            var _token = $("input[name=_token]").val();
            var type = $("select[name=type]").val();
            var status = $("select[name=status").val();
            var name = $("input[name=name]").val();
            $.ajax({
              url:'{{route("admin.users.search_users")}}?page='+page,
              method:"POST",
              data:{_token:_token,type:type,name:name,status:status},
              success:function(data)
              {
               $('#table-data').html(data);
               $('html, body').animate({ scrollTop: 0 }, 'slow');
              }
            });
           
         }       
     });
    });
    </script> 
    <script>
        $(document).ready(function(){
        
         $(document).on('submit', '.search_form', function(event){
            event.preventDefault(); 
              
            var _token = $("input[name=_token]").val();
            var type = $("select[name=type]").val();
            var status = $("select[name=status").val();
            var name = $("input[name=name]").val();
            $.ajax({
              url:'{{route("admin.users.search_users")}}',
              method:"POST",
              data:{_token:_token,type:type,name:name,status:status},
              success:function(data)
              {
               $('#table-data').html(data);
               $('html, body').animate({ scrollTop: 0 }, 'slow');
              }
            });
            
         });
        
        });
        
        </script>  
        <script>
            $.fn.extend({
    treed: function (o) {
      
      var openedClass = 'glyphicon-minus-sign';
      var closedClass = 'glyphicon-plus-sign';
      
      if (typeof o != 'undefined'){
        if (typeof o.openedClass != 'undefined'){
        openedClass = o.openedClass;
        }
        if (typeof o.closedClass != 'undefined'){
        closedClass = o.closedClass;
        }
      };
      
        /* initialize each of the top levels */
        var tree = $(this);
        tree.addClass("tree");
        tree.find('li').has("ul").each(function () {
            var branch = $(this);
            branch.prepend("");
            branch.addClass('branch');
            branch.on('click', function (e) {
                if (this == e.target) {
                    var icon = $(this).children('i:first');
                    icon.toggleClass(openedClass + " " + closedClass);
                    $(this).children('ul').children().toggle();
                }
            })
            // branch.children().children().toggle();
        });
        /* fire event from the dynamically added icon */
        tree.find('.branch .indicator').each(function(){
            $(this).on('click', function () {
                $(this).closest('li').click();
            });
        });
        /* fire event to open branch if the li contains an anchor instead of text */
        tree.find('.branch>a').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
        /* fire event to open branch if the li contains a button instead of text */
        tree.find('.branch>button').each(function () {
            $(this).on('click', function (e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
    }
});
/* Initialization of treeviews */
$('#tree1').treed();
        </script>
@endpush