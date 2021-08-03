@extends('layouts.frontend.app')
@section('content')

<div class="block mt-5">
    <div class="container">
        <div class="card mb-0">
            <div class="card-body contact-us">
                <div class="contact-us__container">
                    <div class="row">
                       
                        <div class="col-12">
                            <h4 class="contact-us__header card-title">Submit a claim</h4>
                            @if($claim=='missing cashback')
                            <form action="{{route('account.claim.step3')}}" method="POST">
                                @csrf
                                <input type="hidden" name="claim_type" value="{{$claim}}">
                                <div class="form-row">
                                    <div class="form group col-xl-8 col-md-12">
                                        <label for="store_id" class="mb-2">Select Visit</label>
                                        <table id="table" class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th data-field="state" data-radio="true"></th>
                                                <th data-field="name">Date/Time</th>
                                                <th data-field="starts">Order Amount</th>
                                                <th data-field="forks">Cashback Amount</th>
                                                {{-- <th data-field="description">Description</th> --}}
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($clicks as $click)
                                                    
                                               
                                            <tr>
                                                <td><input type="radio" name="click_id" value="{{$click->id}}"></td>
                                                <td>
                                                    {{Carbon\Carbon::parse($click->created_at)->isoFormat('Do MMMM YYYY hh:mm:ss')}}
                                                </td>
                                                <td></td>
                                                <td></td>
                                                
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>   
                                </div>
                                <button type="submit" class="btn btn-primary">Next</button>
                            </form>
                        @elseif($claim == 'incorrect amount' || $claim == 'declined cashback')
                        <form action="{{route('account.claim.step3')}}" method="POST">
                            @csrf
                            <input type="hidden" name="claim_type" value="{{$claim}}">
                            <div class="form-row">
                                <div class="form group col-xl-8 col-md-12">
                                    <label for="store_id" class="mb-2">Select Cashback</label>
                                    <table id="table" class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th data-field="state" data-radio="true"></th>
                                            <th data-field="name">Date</th>
                                            <th data-field="starts">Order Amount</th>
                                            <th data-field="forks">Cashback Amount</th>
                                            {{-- <th data-field="description">Description</th> --}}
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cashback as $item)
                                                
                                           
                                        <tr>
                                            <td><input type="radio" name="click_id" value="{{$item->exit_click_id}}"></td>
                                            <td>
                                                {{Carbon\Carbon::parse($item->event_date)->isoFormat('Do MMMM YYYY')}}
                                            </td>
                                            <td>{{ currency() }}{{$item->order_value}}</td>
                                            <td>{{ currency() }}{{$item->amount}}</td>
                                            
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>   
                            </div>
                            <button type="submit" class="btn btn-primary">Next</button>
                        </form>
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection