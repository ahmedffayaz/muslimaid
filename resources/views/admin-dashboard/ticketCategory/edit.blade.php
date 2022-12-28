@extends('layouts.admin-dashboard.app')
@section('content')
@if(session()->has('message'))
<div class = "container alert {{ session('alert-class') }} alert-dismissible fade show alert-important" role = "alert">
    {{ session('message') }}.
<button type = "button" class = "close" data-dismiss = "alert" aria-label = "Close">
    <span aria-hidden = "true">&times;</span>
</button>
</div>
@endif

<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="components-preview mx-auto">
                    <div class="nk-block nk-block-lg">
                        <div class="nk-block-head">
                            <div class="nk-block-head-content">
                                <div class="nk-block-des">
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-inner">
                                <div class="card-head">
                                </div>
                                <form action="{{route('admin.ticketCategory.update',$ticketCategory)}}" class="" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row g-4">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="form-label">Name</label>
                                                <div class="form-control-wrap">
                                                    <input id="ticket-name" type="text" class="form-control " name="name"  value="{{$ticketCategory->name}}"> 
                                                    @error('name')
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="form-label">Description</label>
                                                <div class="form-control-wrap">
                                                    <textarea id="ticket-description" type="text" class="form-control" rows="5" name="description"> {{$ticketCategory->description}} </textarea>
                                                    @error('description')
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <button class="btn btn-primary" type="submit">Save</button>  
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div><!-- .nk-block -->
                   </div><!-- .components-preview -->
            </div>
        </div>
    </div>
</div>

@endsection