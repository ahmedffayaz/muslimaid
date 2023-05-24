@extends('layouts.admin-dashboard.app')
@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body wide-md mx-auto">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            {{-- <h3 class="nk-block-title page-title">Cashback Statuses</h3> --}}
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->

                @include('flash::message')
                <div class="nk-block">
                    <div class="card">
                        <div class="card-inner">
                            <h5 class="card-title">Cashback Statuses</h5>
                            <p>Here you can change the titles for the statuses assigned to cashback.</p>
                            <form action="{{route(getAdminPrefix() . '.settings.cashback_statuses_save')}}" class="gy-3 form-settings" method="POST">
                                @csrf
                                @method('POST')
                                @foreach ($statuses as $status)
                                    
                                
                                <div class="row g-3 align-center">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-label" for="status[{{$status['id']}}]">{{$status['id']}}</label>
                                            <span class="form-note">{{$status['details']}}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <input type="text" class="form-control" name="status[{{$status['id']}}]" id="site-name" value="{{$status['status']}}" placeholder="smtp">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                
                                
                                <div class="row g-3">
                                    <div class="col-lg-9 offset-lg-3">
                                        <div class="form-group mt-2">
                                            <button type="submit" class="btn btn-lg btn-primary">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div><!-- .card-inner -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>
@endsection