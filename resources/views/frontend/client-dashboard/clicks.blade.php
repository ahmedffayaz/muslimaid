@extends('frontend.layouts.app')

@section('content')
    <div class="block mt-5">
        <div class="container">
            <div class="row">

                <div class="col-12 col-lg-3 d-flex">
                    @include('frontend.client-dashboard.side-nav')
                </div>

                <div class="col-12 col-lg-9 mt-4 mt-lg-0">
                    @if ($clicks->count())
                        <div class="card">
                            <div class="card-header">
                                <h5>Clicks</h5>
                            </div>
                            <div class="card-divider"></div>
                            <div class="card-table">
                                <div class="table-responsive-sm">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Store</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($clicks as $click)
                                                <tr>
                                                    <td><a href="{{ route('store.show', $click->store->slug) }}" target="_blank">{{ $click->store->name }}</a></td>
                                                    <td>{{ Carbon\Carbon::parse($click->created_at)->isoFormat('Do MMMM YYYY') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center">
                            <h2>No Clicks Acitivity</h2>
                            <p>Your clicks activity will be listed here.</p>
                            <img src="{{ asset('frontend/images/pages/wallet.png') }}" alt="" width="100%" style="max-width: 300px">
                            <div>
                                <a href="{{ route('offers') }}" class="btn btn-primary mt-4">View Offers</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
