@extends('layouts.admin-dashboard.app')
@section('content')
    

<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="components-preview wide-md mx-auto">
                  
                    
                  
                    <div class="nk-block nk-block-lg">
                        <div class="nk-block-head">
                            <div class="nk-block-head-content">
                                <h4 class="title nk-block-title">Importer</h4>
                                <div class="nk-block-des">
                                    <p>Import stores, cashbacks and categories</p>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-inner">
                                <div class="card-head">
                                    <h5 class="card-title">Importer</h5>
                                </div>
                                <form action="{{route('admin.networks.store')}}" class="gy-3" method="POST">
                                    @csrf
                                    <div class="row g-3 align-center">
                                        <div class="col-lg-5">
                                            <div class="form-group">
                                                <label class="form-label" for="site-name">Network Name</label>
                                                <span class="form-note">Select Network.</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-7">
                                            <div class="form-group">
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" name="network_name" id="site-name" value="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3 align-center">
                                        <div class="col-lg-5">
                                            <div class="form-group">
                                                <label class="form-label">Import</label>
                                                <span class="form-note">Select import.</span>
                                            </div>
                                        </div>
                                        <div class="col-lg-7">
                                            <ul class="custom-control-group g-3 align-center flex-wrap">
                                                <li>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" checked name="reg-public" id="reg-enable">
                                                        <label class="custom-control-label" for="reg-enable">Stores</label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" name="reg-public" id="reg-disable">
                                                        <label class="custom-control-label" for="reg-disable">Cashbacks</label>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" name="reg-public" id="reg-request">
                                                        <label class="custom-control-label" for="reg-request">Categories</label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                   
                                  
                                    
                                 
                                    <div class="row g-3">
                                        <div class="col-lg-7 offset-lg-5">
                                            <div class="form-group mt-2">
                                                <a href="{{route('admin.importer.import')}}" class="btn btn-lg btn-primary">Run Importer</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div><!-- card -->
                    </div><!-- .nk-block -->
                </div><!-- .components-preview -->
            </div>
        </div>
    </div>
</div>

@endsection