@extends('layouts.admin-dashboard.app')
@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="components-preview wide-md mx-auto">
                    <div class="nk-block-head nk-block-head-lg">
                        <div class="nk-block-between align-items-baseline">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title fw-normal">{{$store->name}}</h3>
                            <div class="nk-block-des">
                                <p class="lead">{!!$store->description!!}</p>
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        <li class="nk-block-tools-opt"><a href="{{route('admin.stores.edit',$store)}}" class="btn btn-primary btn-sm"><em class="icon ni ni-edit"></em><span>Edit Store</span></a></li>
                                    
                                        {{-- <li><a href="{{route('admin.stores.export')}}" id="export" class="btn btn-success btn-sm" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li> --}}
                                      
                                    </ul>
                                </div>
                            </div><!-- .toggle-wrap -->
                        </div><!-- .nk-block-head-content -->
                    </div>
                    </div>
                   
                    
                    
                    <div class="nk-block nk-block-lg">
                        
                        <div class="card card-preview">
                            <div class="card-inner">
                                <ul class="nav nav-tabs mt-n3">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#tabItem5"><em class="icon ni ni-file-text"></em><span>Details</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem6"><em class="icon ni ni-link"></em><span>Cashbacks</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem7"><em class="icon ni ni-money"></em><span>Vouchers</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem8"><em class="icon ni ni-notice"></em><span>Reviews</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#tabItem9"><em class="icon ni ni-img-fill"></em><span>Images</span></a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tabItem5">
                                        <div class="nk-block">
                                            <div class="nk-block-head">
                                                <h5 class="title">Store Information</h5>
                                                {{-- <p>Basic info, like your name and address, that you use on Nio Platform.</p> --}}
                                            </div><!-- .nk-block-head -->
                                            <div class="profile-ud-list">
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Name</span>
                                                        <span class="profile-ud-value">{{$store->name}}</span>
                                                    </div>
                                                </div>
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Network</span>
                                                        <span class="profile-ud-value">{{$store->network->name}}</span>
                                                    </div>
                                                </div>
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Store Url</span>
                                                        <span class="profile-ud-value">{{$store->store_url}}</span>
                                                    </div>
                                                </div>
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Click Url</span>
                                                        <span class="profile-ud-value">{{$store->cashback->click_url ?? "#"}}</span>
                                                    </div>
                                                </div>
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Cashback</span>
                                                        <span class="profile-ud-value">{{$store->cashback->sale_commission ?? ''}}</span>
                                                    </div>
                                                </div>
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Categories</span>
                                                        <span class="profile-ud-value">@foreach ($store->categories as $category)
                                                            {{$category->name}},
                                                        @endforeach</span>
                                                    </div>
                                                </div>
                                              
                                            </div><!-- .profile-ud-list -->
                                        </div>
                                      
                                    </div>
                                    <div class="tab-pane" id="tabItem6">
                                        <p>Cashback: {{$store->cashback->sale_commission}}</p>
                                    </div>
                                    <div class="tab-pane" id="tabItem7">
                                        @if(count($store->vouchers))
                                      @foreach ($store->vouchers as $voucher)
                                          <b>Title:</b> {{$voucher->link_name}}<br>
                                          <b>Click url:</b> {{$voucher->click_url}}<br>
                                          <b>Coupon:</b> {{$voucher->coupon_code}}<br><br>
                                      @endforeach
                                      @else
                                      <p>No vouchers found</p>
                                      @endif
                                    </div>
                                    <div class="tab-pane" id="tabItem8">
                                        @if(count($store->reviews))
                                      @foreach ($store->reviews as $review)
                                          <b>Reviewer:</b> {{$review->reviewer}}<br>
                                          <b>Review:</b> {!!$review->review!!}<br><br>
                                      @endforeach
                                      @else
                                      <p>No reviews found</p>
                                      @endif
                                    </div>
                                    <div class="tab-pane" id="tabItem9">
                                        @if(count($store->images))
                                        <div class="nk-block">
                                        <div class="nk-files nk-files-view-grid">
                                            
                                            <div class="nk-files-list">
                                                
                                                @foreach ($store->images as $item)
                                                <div class="nk-file-item nk-file align-items-center d-flex justify-content-center">
                                                    <div class="nk-file-info">
                                                        <div class="nk-file-title">
                                                            <div class="nk-file-icon">
                                                                <a class="nk-file-icon-link" href="{{asset('storage/stores/images/'.$item->image)}}" target="_blank">
                                                                    <span class="nk-file-icon-type">
                                                                       <img src="{{asset('storage/stores/images/'.$item->image)}}" alt="">
                                                                    </span>
                                                                </a>
                                                            </div>
                                                            <div class="nk-file-name">
                                                                <div class="nk-file-name-text">
                                                                    <a href="#" class="title">{{$item->title}}</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                       
                                                    </div>
                                                   
                                                </div><!-- .nk-file -->
                                                @endforeach
                                                
                                            </div>
                                        </div>
                                      
                                    </div><!-- .nk-block -->
                                    @else 
                                                <p>No images found</p>
                                                @endif
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