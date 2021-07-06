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
                <div class="components-preview wide-md mx-auto">
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
                                        <h5 class="title">Cashout Information</h5>
                                    
                                    </div><!-- .nk-block-head -->
                                    <div class="profile-ud-list">
                                        <div class="profile-ud-item">
                                            <div class="profile-ud wider">
                                                <span class="profile-ud-label">User</span>
                                                <span class="profile-ud-value"><a href="{{route('admin.users.show',$cashout->user)}}">{{$cashout->user->id}} - @if($cashout->user->first_name != 'unnamed' || $cashout->user->last_name != 'unnamed') {{$cashout->user->first_name}} {{$cashout->user->last_name}} - @endif {{$cashout->user->email}}</a></span>
                                            </div>
                                        </div>
                                        <div class="profile-ud-item">
                                            <div class="profile-ud wider">
                                                <span class="profile-ud-label">Withdraw Amount</span>
                                                <span class="profile-ud-value">{{ currency() }} {{$cashout->amount}}</span>
                                            </div>
                                        </div>
                                        <div class="profile-ud-item">
                                            <div class="profile-ud wider">
                                                <span class="profile-ud-label">Request Date</span>
                                                <span class="profile-ud-value">{{Carbon\Carbon::parse($cashout->created_at)->isoFormat('Do MMMM YYYY')}}</span>
                                            </div>
                                        </div>
                                        <div class="profile-ud-item">
                                            <div class="profile-ud wider">
                                                <span class="profile-ud-label">Status</span>
                                                <span class="profile-ud-value">{{$cashout->status}}</span>
                                            </div>
                                        </div>
                                        
                                    </div><!-- .profile-ud-list -->
                                </div><!-- .nk-block -->
                                <div class="nk-block">
                                    <div class="nk-block-head nk-block-head-line">
                                        <h6 class="title overline-title text-base">Payment Details</h6>
                                    </div><!-- .nk-block-head -->
                                    <div class="profile-ud-list">
                                        <div class="profile-ud-item">
                                            <div class="profile-ud wider">
                                                <span class="profile-ud-label">Payment Method</span>
                                                <span class="profile-ud-value">{{$cashout->payment_method}}</span>
                                            </div>
                                        </div>
                                        @if($cashout->payment_method == 'paypal')
                                        <div class="profile-ud-item">
                                            <div class="profile-ud wider">
                                                <span class="profile-ud-label">Paypal Email</span>
                                                <span class="profile-ud-value">{{$cashout->paypal_email}}</span>
                                            </div>
                                        </div>
                                        @endif
                                        @if($cashout->payment_method == 'bank')

                                        <div class="profile-ud-item">
                                            <div class="profile-ud wider">
                                                <span class="profile-ud-label">Bank Title</span>
                                                <span class="profile-ud-value">{{$cashout->bank_title}}</span>
                                            </div>
                                        </div>
                                        <div class="profile-ud-item">
                                            <div class="profile-ud wider">
                                                <span class="profile-ud-label">Account Title</span>
                                                <span class="profile-ud-value">{{$cashout->account_name}}</span>
                                            </div>
                                        </div>
                                        <div class="profile-ud-item">
                                            <div class="profile-ud wider">
                                                <span class="profile-ud-label">Account Number</span>
                                                <span class="profile-ud-value">{{$cashout->account_number}}</span>
                                            </div>
                                        </div>
                                        <div class="profile-ud-item">
                                            <div class="profile-ud wider">
                                                <span class="profile-ud-label">Sort Code</span>
                                                <span class="profile-ud-value">{{$cashout->bank_sort_code}}</span>
                                            </div>
                                        </div>
                                        <div class="profile-ud-item">
                                            <div class="profile-ud wider">
                                                <span class="profile-ud-label">BIC</span>
                                                <span class="profile-ud-value">{{$cashout->bic}}</span>
                                            </div>
                                        </div>
                                        @endif
                                    </div><!-- .profile-ud-list -->
                                </div><!-- .nk-block -->
                                @if(count($cashout->cashbacks))
                                <div class="nk-divider divider"></div>
                                <div class="nk-block">
                                    <div class="nk-block-head nk-block-head-line">
                                        <h6 class="title overline-title text-base">Cashback</h6>
                                    </div><!-- .nk-block-head -->
                                    <div class="profile-ud-list">
                                        
                                        @foreach ($cashout->cashbacks as $cashback)
                                            
                                       
                                        <div class="profile-ud-item">
                                            <div class="profile-ud wider">
                                                <span class="profile-ud-label">Store</span>
                                                <span class="profile-ud-value"><a href="{{route('admin.stores.show_store')}}?slug={{$cashback->store->slug}}">{{$cashback->store->name}}</a></span>
                                            </div>
                                        </div>
                                       
                                        <div class="profile-ud-item">
                                            <div class="profile-ud wider">
                                                <span class="profile-ud-label">Cashback Amount</span>
                                                <span class="profile-ud-value">{{ currency() }} {{$cashback->amount}}</span>
                                            </div>
                                        </div>
                                        

                                        @endforeach
                                      
                                        
                                    </div><!-- .profile-ud-list -->
                                </div><!-- .nk-block -->
                                @endif
                                @if($cashout->bonus)
                                <div class="nk-divider divider"></div>
                                <div class="nk-block">
                                    <div class="nk-block-head nk-block-head-line">
                                        <h6 class="title overline-title text-base">Bonus</h6>
                                    </div><!-- .nk-block-head -->
                                    <div class="profile-ud-list">
                                        
                                       
                                            
                                       
                                        <div class="profile-ud-item">
                                            <div class="profile-ud wider">
                                                <span class="profile-ud-label">Bonus</span>
                                                <span class="profile-ud-value">Sign up bonus</span>
                                            </div>
                                        </div>
                                       
                                        <div class="profile-ud-item">
                                            <div class="profile-ud wider">
                                                <span class="profile-ud-label">Amount</span>
                                                <span class="profile-ud-value">{{ currency() }} {{$cashout->bonus->amount}}</span>
                                            </div>
                                        </div>
                                        

                                       
                                      
                                        
                                    </div><!-- .profile-ud-list -->
                                </div><!-- .nk-block -->
                                @endif
                               
                            </div><!-- .card-inner -->
                            <div class="card-inner">
                               
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tabItem5">
                                        <div class="nk-block">
                                            <div class="nk-block-head">
                                               
                                                {{-- <p>Basic info, like your name and address, that you use on Nio Platform.</p> --}}
                                            </div><!-- .nk-block-head -->
                                            <form action="{{route('admin.cashouts.update',$cashout)}}" class="gy-3 form-validate is-alter" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="row g-4">
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="default-06">Status</label>
                                                            <div class="form-control-wrap ">
                                                                <div class="form-control-select">
                                                                    <select class="form-control" id="default-06" name="status" required>
                                                                        <option @if($cashout->status == 'pending') selected @endif value="pending">Pending</option>
                                                                        <option @if($cashout->status == 'paid') selected @endif value="paid">Paid</option>
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