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
                                <p>You have total {{count($allCategories)}} categories.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                        <li><a href="{{route('admin.categories.create')}}" class="btn btn-primary btn-sm" class="btn btn-white btn-outline-light"><em class="icon ni ni-plus"></em><span>Add category</span></a></li>
                                             {{-- <li><a href="{{route('admin.users.export')}}" id="export" class="btn btn-success btn-sm" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li> --}}
                                        </ul>
                                    </div>
                                </div><!-- .toggle-wrap -->
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    @include('flash::message')
                
                    <div class="nk-block">
                        <div class="card card-stretch">
                            <div class="card-inner-group">
                                
                                <div class="card-inner">
                                
        
                                    <h5 class="title mb-3">All Categories</h5>
                                            
                                    <ul id="tree1">
                                        @foreach($categories as $category)
                                            <li>
                                                
                                                <span class="float-right">
                                                    <div class="actions">                                                   
                                                  
                                                        <div class="drodown d-inline">
                                                            <a href="#" class="dropdown-toggle badge badge-info text-white" data-toggle="dropdown"><em class="icon ni ni-plus mr-1"></em>Options</a>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <ul class="link-list-opt no-bdr d-block ml-0">
                                                                    <a href="{{route('admin.categories.picks',$category)}}" category-id='{{$category->id}}' class='picks-edit' ><em class="icon ni ni-cart-fill"></em> Editor Picks</a>
                                                                    <a href="{{route('admin.categories.edit',$category)}}" category-id='{{$category->id}}' class='category-edit' ><em class="icon ni ni-edit"></em> Edit</a>
                                                                    <a  onclick="$('#delete-form-{{$category->id}}').submit();"  style="cursor: pointer"> <em class="icon ni ni-trash-fill"></em> Delete</a>
                                                                    
                                                    
                                                                </ul>
                                                            </div>
                                                        </div>        
                                                    </div>
                                                    <form action="{{ route('admin.categories.destroy', $category) }}" id="delete-form-{{$category->id}}" method="POST" class="m-0">
                                                        @method('DELETE')
                                                        @csrf
                                                        
                                                    </form>
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
<!-- @@ Category Modal @e -->
<div class="modal fade" tabindex="-1" role="dialog" id="category-modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header align-center">
                <div class="nk-file-title">
              
                   
                    <div class="nk-file-name">
                        <div class="nk-file-name-text"><span class="title">Update Category</span></div>

                        {{-- <div class="nk-file-name-sub">Project</div> --}}
                    </div>
                </div>
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            </div>
            <div id="categories" class=" p-4">
               

            </div>
        </div><!-- .modal-content -->
    </div><!-- .modla-dialog -->
</div><!-- .modal -->
<!-- @@ Category Modal @e -->
<div class="modal fade" tabindex="-1" role="dialog" id="picks-modal">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header align-center">
                <div class="nk-file-title">
              
                   
                    <div class="nk-file-name">
                        <div class="nk-file-name-text"><span class="title">Editor Picks</span></div>

                        {{-- <div class="nk-file-name-sub">Project</div> --}}
                    </div>
                </div>
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            </div>
            <div id="picks-form" class=" p-4">
               
                

            </div>
        </div><!-- .modal-content -->
    </div><!-- .modla-dialog -->
</div><!-- .modal -->
@endsection
@push('scripts')
<link rel="stylesheet" href="{{ asset('admin-dashboard/css/editors/quill.css?ver=2.2.0')}}">
    <script src="{{ asset('admin-dashboard/js/libs/editors/quill.js?ver=2.2.0')}}"></script>
    <script src="{{ asset('admin-dashboard/js/editors.js?ver=2.2.0')}}"></script>
<script>$(document).ready(function(){
        $(document).on('click', '.category-edit', function(event){
       event.preventDefault(); 
       
           var id = $(this).attr('category-id');
           pageurl = $(this).attr('href');
           var _token = $("input[name=_token]").val();
           $.ajax({

               url:pageurl,
               method:"GET",
               data:{_token:_token},
               success:function(data)
               {
                   $('#category-modal').modal('show');
                   $('#categories').html(data);
                   initializeSelect2();
                   var quill = new Quill('#editor-container', {
                       modules: {
                       toolbar: [
                           ['bold', 'italic'],
                           ['link', 'blockquote', 'code-block', 'image'],
                           [{ list: 'ordered' }, { list: 'bullet' }]
                       ]
                       },
                       placeholder: 'Compose an epic...',
                       theme: 'snow'
                   });
               }
               });
              
    });
    $(document).on('click', '.picks-edit', function(event){
       event.preventDefault(); 
       
           var id = $(this).attr('category-id');
           pageurl = $(this).attr('href');
           var _token = $("input[name=_token]").val();
           $.ajax({

               url:pageurl,
               method:"GET",
               data:{_token:_token},
               success:function(data)
               {
                   $('#picks-modal').modal('show');
                   $('#picks-form').html(data);
                   initializeSelect2();

               }
               });
              
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

function initializeSelect2() {
        $('.select-2').select2({
            // maximumSelectionLength: 5,
            placeholder: function(){
                $(this).data('placeholder');
                
            }
        });
    }
    $(".category_form").submit(function(e) {
          
          // Populate hidden form on submit
          var desc = document.querySelector('input[name=description]');
          desc.value = quill.root.innerHTML;
         
          
        });
        </script>
@endpush