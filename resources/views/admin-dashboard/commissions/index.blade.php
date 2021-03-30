@extends('layouts.admin-dashboard.app')
@section('content')
   

<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Commissions: &#163;{{$coms->sum('amount')}}</h3>
                            <div class="nk-block-des text-soft">
                                <p>Total {{count($coms)}} commissions.</p>
                            </div>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                       
                                        <li class="nk-block-tools-opt"><a href="#"  class="btn btn-primary" data-toggle="modal" data-target="#modalAlert"><em class="icon ni ni-download"></em><span>Import</span></a></li>
                                    </ul>
                                </div>
                            </div><!-- .toggle-wrap -->
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="nk-block">
                    <table class="nk-tb-list is-separate nk-tb-ulist datatable-init table">
                        <thead>
                            <tr class="nk-tb-item nk-tb-head">
                               
                                <th class="nk-tb-col"><span class="sub-text">User</span></th>
                                <th class="nk-tb-col"><span class="sub-text">Store</span></th>
                                <th class="nk-tb-col"><span class="sub-text">Amount (&#163;)</span></th>
                                <th class="nk-tb-col"><span class="sub-text">Exit Click Id</span></th>
                                <th class="nk-tb-col"><span class="sub-text">Event Time</span></th>
                                <th class="nk-tb-col"><span class="sub-text">Status</span></th>
                                <th class="nk-tb-col nk-tb-col-tools text-right">
                                    <span class="sub-text">Action</span>
                                </th>
                            </tr><!-- .nk-tb-item -->
                        </thead>
                        <tbody>

                            @foreach ($coms as $com)
                                
                            
                            <tr class="nk-tb-item">
                               
                                <td class="nk-tb-col"> 
                                    <a href="#">
                                        <div class="user-card">
                                            <div class="user-avatar
                                            <?php
                                           
                                            $color = rand(1,5);
                                            if($color==1){echo 'bg-info';}
                                            elseif($color==2){echo 'bg-primary';}
                                            elseif($color==3){echo 'bg-danger';}
                                            elseif($color==4){echo 'bg-success';}
                                            elseif($color==5){echo 'bg-warning';}
                                            else{}
                                            ?>
                                            
                                            ">
                                                {{-- <span>{{$click->user->first_name[0]}}{{$click->user->last_name[0]}}</span> --}}
                                            </div>
                                            <div class="user-info">
                                                {{-- <span class="tb-lead">{{$click->user->first_name}} {{$click->user->last_name}}<span class="dot dot-success d-md-none ml-1"></span></span>
                                                <span>{{$click->user->email}}</span> --}}
                                            </div>
                                        </div>
                                    </a>
                                    
                                    
                                   </td>
                              
                               
                                <td class="nk-tb-col"> <p>{{$com->store->name}}</p></td>
                                <td class="nk-tb-col"> <p>&#163;{{$com->amount}}</p></td>
                                <td class="nk-tb-col"> <p>{{$com->exit_click_id}}</p></td>
                                <td class="nk-tb-col"> <p>{{$com->event_date}}</p></td>
                                <td class="nk-tb-col"> <span class="tb-status text-info">{{ $com->status}}</span></td>
                                <td class="nk-tb-col nk-tb-col-tools">
                                    <ul class="nk-tb-actions gx-1">
                                        <li>
                                            <div class="drodown">
                                                <a href="#" class="dropdown-toggle btn btn-sm btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <ul class="link-list-opt no-bdr">
                                                        {{-- <li><a href="#"><em class="icon ni ni-edit"></em><span>Edit User</span></a></li> --}}
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
                    {{-- <div class="card">
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
                    </div><!-- .card --> --}}
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>


<!-- Modal Alert -->
<div class="modal fade" tabindex="-1" id="modalAlert">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross"></em></a>
            <div class="modal-body modal-body-lg text-center">
                <div class="nk-modal">
                    <em class="nk-modal-icon icon icon-circle icon-circle-xxl ni ni-download bg-success"></em>
                    <h4 class="nk-modal-title">Run Importer</h4>
                    {{-- <div class="nk-modal-text">
                        <div class="caption-text">You’ve successfully bought <strong>0.5968</strong> BTC for <strong>200.00</strong> USD</div>
                        <span class="sub-text-sm">Learn when you reciveve bitcoin in your wallet. <a href="#"> Click here</a></span>
                    </div> --}}
                    <div class="nk-modal-action">
                        <a href="{{route('admin.importer.commissions')}}" class="btn btn-lg btn-mw btn-primary">Run</a>
                    </div>
                </div>
            </div><!-- .modal-body -->
            <div class="modal-footer bg-lighter">
                <div class="text-center w-100">
                    <p>Import Commissions</p>
                </div>
            </div>
        </div>
    </div>
</div>

 
@endsection