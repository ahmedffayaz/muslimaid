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
                                <h4 class="title nk-block-title">Add Translation</h4>
                                <div class="nk-block-des">
                                    <p>You can make style out your....</p>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-inner">
                                <div class="card-head">
                                    <h5 class="card-title">Translation</h5>
                                </div>
                                <form action="{{route('admin.translations.store')}}" class="gy-3 form-validate is-alter" method="POST">
                                    @csrf
                                    <div class="row g-4">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="title">Group</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="group" name="group" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="key">Key</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="key" name="key" required>
                                                </div>
                                            </div>
                                        </div>
                                        @foreach($languages as $language)
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="value">{{ $language->name }}</label>
                                                    <div class="form-control-wrap">
                                                    <input type="text" @if ($language->code === 'en') required @endif class="form-control" name="text[{{ $language->code }}]">
                                                       
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                       
                                        <div class="col-12">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-lg btn-primary">Save</button>
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

