@php
    $encoded = json_encode($translation->text);
    $decoded = json_decode($encoded, true);
@endphp

@extends('layouts.admin-dashboard.app')



@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Translation Details</h3>
                            <p>Group: {{$translation->group}} Key: {{$translation->key}} </p>
                            
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        <li><a href=""  class="btn btn-primary btn-sm" class="btn btn-white btn-outline-light" data-toggle="modal" data-target="#modalForm"><em class="icon ni ni-plus"></em><span>Add Translation</span></a></li>
                                        {{-- <li><a href="{{route('admin.settings.export', $settings)}}" id="export" class="btn btn-success btn-sm" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li> --}}

                                    </ul>
                                </div>
                            </div><!-- .toggle-wrap -->
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                @include('flash::message')
                <div class="nk-block">
                    <div class="row g-gs">

                        <div class="col-sm-6 col-lg-4 col-xxl-3">
                            <div class="card h-100">
                                <div class="card-inner">
                                    <div class="project">
                                        <div class="project-head">
                                            <div class="project-title">
                                                <div class="user-avatar sq bg-purple"><span>en</span></div>
                                                <div class="project-info">
                                                    <h6 class="title">English</h6>
                                                    <span class="sub-text">en</span>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="project-details">
                                            <p>{{ $decoded['en'] }}</p>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        @foreach($decoded as $key => $singleRecord)
                            @if($key != 'en')
                            <div class="col-sm-6 col-lg-4 col-xxl-3">
                                <div class="card h-100">
                                    <div class="card-inner">
                                        <div class="project">
                                            <div class="project-head">
                                                <div class="project-title">
                                                    <div class="user-avatar sq <?php
       
                                                    $color = rand(1,5);
                                                    if($color==1){echo 'bg-info';}
                                                    elseif($color==2){echo 'bg-primary';}
                                                    elseif($color==3){echo 'bg-danger';}
                                                    elseif($color==4){echo 'bg-success';}
                                                    elseif($color==5){echo 'bg-warning';}
                                                    else{}
                                                    ?>"><span>{{$key}}</span></div>
                                                    <div class="project-info">
                                                        <h6 class="title">{{App\Models\Language::where('code',$key)->first()->name}}</h6>
                                                        <span class="sub-text">{{$key}}</span>
                                                    </div>
                                                </div>
                                                <div class="drodown">
                                                    <a href="#" class="dropdown-toggle btn btn-sm btn-icon btn-trigger mt-n1 mr-n1" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <ul class="link-list-opt no-bdr">
                                                            <li><a href="#"  data-toggle="modal" data-target="#updateForm-{{$key}}"><em class="icon ni ni-edit" ></em><span>Edit</span></a></li>
                                                           
                                                        
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="project-details">
                                                <p>{{ $decoded[$key] }}</p>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                                        <!-- Modal Form -->
                            <div class="modal fade" tabindex="-1" id="updateForm-{{$key}}">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Translation</h5>
                                            <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                                                <em class="icon ni ni-cross"></em>
                                            </a>
                                        </div>
                                        <form action="{{route('admin.lines.update', $key)}}" class="form-validate is-alter" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="translation_id" value="{{$translation->id}}">
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-3 col-sm-3">
                                                        <div class="form-group"><label class="control-label">English</label></div>
                                                    </div>
                                                    <div class="col-md-9 col-sm-9">
                                                        <div class="form-group">{{$decoded['en']}}</div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label" for="language_id">Language</label>
                                                    <div class="form-control-wrap ">
                                                        <select class="form-select form-control" data-search="on" id="langugae" name="code" disabled>
                                                            <option disabled selected>Select Language</option>
                                                            
                                                            @foreach ($languages as $language)
                                                            <option @if($key == $language['code']) selected @endif value="{{$language['code']}}">{{$language['name']}}</option>
                                                            @endforeach
                                                        </select>
                                                    
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="form-label" for="text">Title Translation</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="text" name="text" required value="{{ $decoded[$key] }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary">update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            @endif
                        @endforeach

                    </div>
                </div>

   



</div>
</div>
</div>
</div> 
<!-- Modal Form -->
<div class="modal fade" tabindex="-1" id="modalForm">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Translation</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <form action="{{route('admin.lines.store')}}" class="form-validate is-alter" method="POST">
                @csrf
                <input type="hidden" name="translation_id" value="{{$translation->id}}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-3">
                            <div class="form-group"><label class="control-label">Title</label></div>
                        </div>
                        <div class="col-md-9 col-sm-9">
                            <div class="form-group">{{$decoded['en']}}</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="language_id">Language</label>
                        <div class="form-control-wrap ">
                            <select class="form-select form-control" data-search="on" id="langugae" name="code">
                                <option disabled selected>Select Language</option>
                                
                                @foreach ($languages as $language)
                                <option value="{{$language['code']}}">{{$language['name']}}</option>
                                @endforeach
                            </select>
                        
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="text">Title Translation</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" id="text" name="text" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" id='add-btn'>Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function(){
     $(document).on('click', '.pagination a', function(event){
        event.preventDefault(); 
        var route = $('.pagination').attr('route');
        var page = $(this).attr('href').split('page=')[1];
        
         if(route=='index'){
            
             pageurl = "{{route('admin.stores.fetch')}}?page="
             var _token = $("input[name=_token]").val();
            $.ajax({

                url:pageurl+page,
                method:"POST",
                data:{_token:_token, page:page},
                success:function(data)
                {
                    $('#table-data').html(data);
                    $('html, body').animate({ scrollTop: 0 }, 'slow');
                }
                });
         } 

            
     });
    });
    </script> 
@endpush