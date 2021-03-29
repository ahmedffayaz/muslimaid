@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center text-center">
        <div class="col-md-12">
            <h1  class="max-w-6xl mx-auto sm:px-6 lg:px-8">Cashback Reborn</h1>
        </div>

        @foreach ($cashbacks->take(20) as $cashback)

        <div class="col-md-3 my-3">
            <a href="{{$cashback->click_url}}">
            <h2>{{$cashback->store->name}}</h2>
            <h2>{{$cashback->sale_commission}}</h2>
           <img src="{{$cashback->image}}" alt="" width="100px"></a>
        </div>
            
        @endforeach
    </div>
</div>

@endsection
