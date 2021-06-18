@extends('layouts.admin-dashboard.app')
@section('content')

<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="components-preview mx-auto">
                  
                    
                  
                    <div class="nk-block nk-block-lg">
                        <div class="nk-block-head">
                            <div class="nk-block-head-content">
                                {{-- <h4 class="title nk-block-title">Menu</h4> --}}
                                <div class="nk-block-des">
                                    {{-- <p>You can make style out your....</p> --}}
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-inner">
                                <div class="card-head">
                                    <h5 class="card-title">Menu</h5>
                                </div>

                            {!! Menu::render() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                

@endsection
@push('scripts')
    {!! Menu::scripts() !!}
@endpush