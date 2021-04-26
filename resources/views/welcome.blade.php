@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center text-center">
        <div class="col-md-12">
            <h1  class="max-w-6xl mx-auto sm:px-6 lg:px-8">Cashback Reborn</h1>
            <h2  class="max-w-6xl mx-auto sm:px-6 lg:px-8 my-4" >{{ trans('headings.products', [], app()->getLocale()) }}</h2>

        </div>
      @isset($cashbacks)
          
      
        @foreach ($cashbacks->take(20) as $cashback)

        <div class="col-md-3 my-3">
            @guest

            @else
            <form action="{{route('site.exit_click.store')}}" method="POST" id="form_{{$cashback->id}}">
                @csrf
                <input type="hidden" name="url" id="url" value="{{$cashback->click_url}}">
                <input type="hidden" name="store_id" id="store_id" value="{{$cashback->store->id}}">
                <input type="hidden" name="user_id" id="user_id" value="{{\Auth::id()}}">

            </form>
            @endguest
            
            
            <a @guest
            data-toggle="modal" data-target="#signinModal"
            @else onclick="document.getElementById('form_{{$cashback->id}}').submit()"
            @endguest  style="cursor: pointer">
            <h2>{{$cashback->store->name}}</h2>
            <h2>{{$cashback->sale_commission}}</h2>
           <img src="{{$cashback->image}}" alt="" width="100px"></a>
        </div>
            
        @endforeach
        @endisset
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="signinModal" tabindex="-1" role="dialog" aria-labelledby="signinModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="signinModalLabel">Login to get cashback</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <a href="{{route('register')}}" class="btn btn-primary">Login/Register</a>
        </div>
        
      </div>
    </div>
  </div>

@endsection
