
@extends('layouts.admin-dashboard.app')
@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Charities</h3>
                            <div class="nk-block-des text-soft">
                                {{-- <p>You have total {{count($charities)}} tickets.</p> --}}
                            </div>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        <li class="nk-block-tools-opt"><a href="{{route('admin.charities.create')}}" class="btn btn-primary btn-sm"><em class="icon ni ni-plus"></em><span>Add Charity</span></a></li>
                                         <li class="nk-block-tools-opt"><a href="{{route('admin.charities.charity_type_create')}}" class="btn btn-primary btn-sm"><em class="icon ni ni-plus"></em><span>Create Charity Type</span></a></li>
                                        <li class="nk-block-tools-opt"><a href="{{route('admin.charities.charity_type_view')}}" class="btn btn-primary btn-sm"><em class="icon ni ni-plus"></em><span>View Charity Type</span></a></li>
                                        
                                        {{-- <li><a href="{{route('admin.stores.export')}}" id="export" class="btn btn-success btn-sm" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li> --}}
                                    </ul>
                                </div>
                            </div><!-- .toggle-wrap -->
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="card card-preview mb-4">
                    <div class="card-inner">
                        <form action="{{route('admin.tickets.search')}}" class="form-validate is-alter search_form" method="POST">
                            @csrf
                            <div class="row g-4">
                                {{-- <div class="col-lg-2"></div> --}}
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-label" for="keyword">Search for Keyword</label>
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control" id="keyword" value="" name="keyword">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="form-label" for="charity_types_id">All Charity Type</label>
                                        <div class="form-control-wrap ">
                                            <select class="form-select form-control" data-search="on" id="charity_types_id" name="charity_types_id">
                                                <option value="0">Any</option>
                                                @foreach ($charitiestypes as $charity)
                                                <option value="{{$charity->id}}"> {{$charity->title}}</option>
                                                @endforeach 
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label" for="status">Status</label>
                                        <div class="form-control-wrap ">
                                            <select class="form-select form-control" data-search="on" id="status" name="status">
                                                <option value="-1">Any</option>
                                                <option value="open">Open</option>
                                                <option value="closed">Closed</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="form-label" for="country">All Country</label>
                                        <div class="form-control-wrap ">
                                            <select class="form-select form-control" data-search="on" id="country" name="country">
                                                {{-- <option value="0">Any</option>
                                                @foreach ($charities as $category)
                                                <option value="{{$category->id}}">{{$category->name}}</option>
                                                @endforeach --}}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2 align-self-end ml-auto">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success btn-block">Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>  
                    </div>
               
                </div>
                @include('flash::message')
                <div class="nk-block">
                    <div class="card card-stretch">
                        <div class="card-inner-group">
                           
                            <div class="card-inner px-0">
                                <div class="nk-tb-list nk-tb-ulist" id="table-data">
                                    
                                    @include('admin-dashboard.charities.index_data')                                   
                                    
                                </div><!-- .nk-tb-list -->
                            </div><!-- .card-inner -->
                           
                        </div><!-- .card-inner-group -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function(){   
       $(document).on('click', '.delete', function(event){
            var form_id = $(this).attr('form_id');
            console.log(form_id)
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
            }).then(function (result) {
                if (result.value) {
                $('#'+form_id).submit();
                }
           });
           event.preventDefault(); 
        });
   });
</script>
@endpush