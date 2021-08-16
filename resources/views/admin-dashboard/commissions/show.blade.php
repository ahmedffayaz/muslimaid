@extends('layouts.admin-dashboard.app')
<style>
    .nk-tb-list{
        table-layout: fixed;
    }
    .nk-files-view-grid .nk-file-icon-type {
    width: 150px;
    padding: 2rem 0 .5rem 0;
}
@media (min-width: 1200px){
    .nk-files-view-grid .nk-file {
    width: calc(25% - 16px)!important;
}
}
.nk-files-view-grid .nk-file {
    background-color:#f5f6fa7a!important;
}
.stores .select2{
    width: 300px!important;
}
.select2-selection__choice {
    text-transform: capitalize;
}
</style>

@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="commissionponents-preview wide-md mx-auto">
                    <div class="nk-block-head nk-block-head-lg pb-2">
                        <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title fw-normal"></h3>
                            <div class="nk-block-des">
                              
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                           <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        {{-- <li class="nk-block-tools-opt"><a href="{{route('admin.stores.edit',$store)}}" class="btn btn-primary btn-sm"><em class="icon ni ni-edit"></em><span>Edit Store</span></a></li> --}}
                                        {{-- <li><a href="{{route('admin.stores.export')}}" id="export" class="btn btn-success btn-sm" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li> --}}
                                    </ul>
                                </div>
                            </div><!-- .toggle-wrap -->
                        </div><!-- .nk-block-head-content -->
                    </div>
                    </div>
                   
                @include('flash::message')
                    
                    
                    <div class="nk-block nk-block-lg">
                        <div class="card card-preview">
                            <div class="card-inner">
                                <div class="nk-block">
                                    <div class="nk-block-head">
                                        <h5 class="title">Cashback Information</h5>
                                    
                                    </div><!-- .nk-block-head -->
                                    <div class="profile-ud-list">
                                        <div class="profile-ud-item">
                                            <div class="profile-ud wider">
                                                <span class="profile-ud-label">User</span>
                                                <span class="profile-ud-value"><a href="{{route('admin.users.show_user')}}?user_id={{$commission->user->id}}" class="a_link">{{$commission->user->id}} - @if($commission->user->first_name != 'unnamed' || $commission->user->last_name != 'unnamed') {{$commission->user->first_name}} {{$commission->user->last_name}} - @endif {{$commission->user->email}}</a></span>
                                            </div>
                                        </div>
                                        <div class="profile-ud-item">
                                            <div class="profile-ud wider">
                                                <span class="profile-ud-label">Network Commission</span>
                                                <span class="profile-ud-value">{{ currency() }}{{number_format((float)$commission->network_commission, 2, '.', '')}}</span>
                                            </div>
                                        </div>
                                        <div class="profile-ud-item">
                                            <div class="profile-ud wider">
                                                <span class="profile-ud-label">Store</span>
                                                <span class="profile-ud-value"><a href="{{route('admin.users.show_user')}}?user_id={{$commission->user->id}}" class="a_link">{{$commission->store->id}} - @if($commission->store->name != 'unnamed') {{$commission->store->name}} @endif </a></span>
                                            </div>
                                        </div>
                                        <div class="profile-ud-item">
                                            <div class="profile-ud wider">
                                                <span class="profile-ud-label">Cashback</span>
                                                <span class="profile-ud-value">{{ currency() }}{{ number_format((float)$commission->amount, 2, '.', '')}}</span>
                                            </div>
                                        </div><div class="profile-ud-item">
                                            <div class="profile-ud wider">
                                                <span class="profile-ud-label">Exit Click ID</span>
                                                <span class="profile-ud-value">{{$commission->exit_click_id}}</span>
                                            </div>
                                        </div><div class="profile-ud-item">
                                            <div class="profile-ud wider">
                                                <span class="profile-ud-label">Event Time</span>
                                                <span class="profile-ud-value">{{$commission->event_date}}</span>
                                            </div>
                                        </div>
                                        <div class="profile-ud-item">
                                            <div class="profile-ud wider">
                                                <span class="profile-ud-label">Status</span>
                                                <span class="profile-ud-value">{{ $commission->statusMap->status}}</span>
                                            </div>
                                        </div>
                                        
                                    </div><!-- .profile-ud-list -->
                                </div><!-- .nk-block -->
                                {{--
                                <div class="nk-block">
                                    <div class="nk-block-head nk-block-head-line">
                                        <h6 class="title overline-title text-base">History</h6>
                                    </div><!-- .nk-block-head -->
                                    <div class="profile-ud-list">
                                        <div class="profile-ud-item">
                                            <div class="profile-ud wider">
                                                <ul class="timeline-list">
                                                    @foreach ($history as $item)
                                                    <li class="timeline-item ml-5">
                                                        <div class="timeline-status bg-primary is-outline"></div>
                                                        <div class="timeline-date">{{Carbon\Carbon::parse($item->created_at)->isoFormat('Do MMMM')}}<em
                                                                class="icon ni ni-alarm-alt"></em></div>
                                                        <div class="timeline-data">
                                                            <h6 class="timeline-title text-capitalize"> {{$item->status->status}}</h6>
                                                            <div class="timeline-des">
                                                                <span
                                                                    class="time">{{Carbon\Carbon::parse($item->created_at)->isoFormat('Do MMMM YYYY, h:mm:ss a ')}}</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div><!-- .profile-ud-list -->
                                </div><!-- .nk-block -->
                                --}}
                            </div><!-- .card-inner -->
                            <div class="card-inner">
                               
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tabItem5">
                                        <div class="nk-block">
                                            <div class="nk-block-head">
                                               
                                                {{-- <p>Basic info, like your name and address, that you use on Nio Platform.</p> --}}
                                            </div><!-- .nk-block-head -->
                                            <form action="{{route('admin.commissions.update',$commission)}}" class="gy-3 form-validate is-alter" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="row g-4">
                                                    <input type="hidden" class="form-control" id="phone-no-1" value="{{$commission->network_commission}}"
                                                        name="network_commission">
                                                    <input type="hidden" class="form-control" id="phone-no-1" value="{{$commission->amount}}" name="amount">
                                                    <input type="hidden" class="form-control" id="phone-no-1" value="{{$commission->order_value}}" name="order_value"></div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="default-06">Status</label>
                                                            <div class="form-control-wrap ">
                                                                <div class="form-control-select">
                                                                    <select class="form-control" id="default-06" name="status" required>
                                                                        @foreach ($statuses as $status)
                                                                            <option @if($status->id == $commission->status) selected @endif value="{{$status->id}}">{{$status->status}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                   <div class="col-12">
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-lg btn-primary">Save</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                      
                                    </div>
                                </div>
                            </div>
                        </div><!-- .card-preview -->
                      
                    </div>
                  
                </div>
            </div>
        </div>
    </div>
</div>




@endsection
@push('scripts')
 
@endpush