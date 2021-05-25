@extends('layouts.admin-dashboard.app')
@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body  mx-auto">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Permissions Settings</h3>
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->

                @include('flash::message')
                <div class="nk-block">
                    <div class="card">
                        <div class="card-inner">
                            <h5 class="card-title">Permissions</h5>
                            <p>Here you can update permissions for roles.</p>
                            <form action="{{route('admin.settings.update_permissions')}}" class="gy-3 form-settings" method="POST">
                                @csrf
                                @method('POST')
                                <div class="row g-3 align-center">
                                    @foreach ($roles as $role)
                                        
                                    
                                    <div class="col-lg-12">
                                        <div id="accordion-{{$role->name}}" class="accordion accordion-s2">
                                            <div class="accordion-item">
                                                <a href="#" class="accordion-head collapsed" data-toggle="collapse" data-target="#{{$role->name}}">
                                                    <h6 class="title text-capitalize">{{$role->name}}</h6>
                                                    <span class="accordion-icon"></span>
                                                </a>
                                                <div class="accordion-body collapse" id="{{$role->name}}" data-parent="#accordion-{{$role->name}}">
                                                    <div class="accordion-inner">
                                                        
                                                        <div class="row">
                                                            @foreach ($permissions as $permission)
                                                                <div class="col-md-2 mb-1">
                                                                    <div class="custom-control custom-control-sm custom-checkbox custom-control">
                                                                        <input @if($role->hasPermissionTo($permission)) checked @endif type="checkbox" name="{{$role->name}}[]" value="{{$permission->id}}" class="custom-control-input" id="{{$role->name}} {{$permission->name}}">
                                                                        <label class="custom-control-label text-capitalize" for="{{$role->name}} {{$permission->name}}">{{$permission->name}}</label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                        
                                    

                                    @endforeach
                                </div>
                                
                         
                                
                             
                                
                                <div class="row g-3">
                                    <div class="col-lg-12">
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