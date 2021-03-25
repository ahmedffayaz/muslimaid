@extends('layouts.admin-dashboard.app')
@section('content')
   

<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Networks</h3>
                            <div class="nk-block-des text-soft">
                                {{-- <p>You have total 95 projects.</p> --}}
                            </div>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                       
                                        <li class="nk-block-tools-opt"><a href="{{route('admin.networks.create')}}" class="btn btn-primary"><em class="icon ni ni-plus"></em><span>Add Network</span></a></li>
                                    </ul>
                                </div>
                            </div><!-- .toggle-wrap -->
                        </div><!-- .nk-block-head-content -->
                        
                    </div><!-- .nk-block-between -->
                    {{-- <div class="example-alert mt-2">
                        <div class="alert alert-success alert-icon">
                            <em class="icon ni ni-check-circle"></em> <strong>Network added succesfully</strong> </div>
                    </div> --}}
                </div><!-- .nk-block-head -->
                <div class="nk-block">
                    <table class="nk-tb-list is-separate nk-tb-ulist">
                        <thead>
                            <tr class="nk-tb-item nk-tb-head">
                               
                                <th class="nk-tb-col"><span class="sub-text">Network Name</span></th>
                                <th class="nk-tb-col"><span class="sub-text">Click ref</span></th>
                                <th class="nk-tb-col nk-tb-col-tools text-right">
                                    <span class="sub-text">Action</span>
                                </th>
                            </tr><!-- .nk-tb-item -->
                        </thead>
                        <tbody>

                            @foreach ($networks as $network)
                                
                            
                            <tr class="nk-tb-item">
                               
                                <td class="nk-tb-col">
                                    <h6>{{$network->name}}</h6>
                                </td>
                                <td class="nk-tb-col">
                                    <h6>{{$network->click_ref}}</h6>
                                </td>
                                <td class="nk-tb-col nk-tb-col-tools">
                                    <ul class="nk-tb-actions gx-1">
                                        <li>
                                            <div class="drodown">
                                                <a href="#" class="dropdown-toggle btn btn-sm btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <ul class="link-list-opt no-bdr">
                                                        <li><a href="#"><em class="icon ni ni-edit"></em><span>Edit Network</span></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </td>
                            </tr><!-- .nk-tb-item -->
                            @endforeach
                          
                        </tbody>
                    </table><!-- .nk-tb-list -->
                    <div class="card">
                        <div class="card-inner">
                            <div class="nk-block-between-md g-3">
                                <div class="g">
                                    <ul class="pagination justify-content-center justify-content-md-start">
                                        <li class="page-item"><a class="page-link" href="#">Prev</a></li>
                                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                                       
                                        <li class="page-item"><a class="page-link" href="#">Next</a></li>
                                    </ul><!-- .pagination -->
                                </div>
                                
                            </div><!-- .nk-block-between -->
                        </div><!-- .card-inner -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>

 
@endsection