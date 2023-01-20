@extends('frontend.layouts.app')

@section('content')
    <div class="block mt-5">
        <div class="container">
            <div class="row">

                <div class="col-12 col-lg-3 d-flex">
                    @include('frontend.client-dashboard.side-nav')
                </div>

                <div class="col-12 col-lg-9 mt-4 mt-lg-0">
                    @if ($cashbacks->count())
                        <div class="card">
                            <div class="card-header">
                                <h5>My Cashback</h5>
                            </div>

                            <div class="card-divider"></div>

                            <div class="card-table">
                                <div class="table-responsive-sm">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Store</th>
                                                <th>Order Amount</th>
                                                <th>Cashback</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cashbacks as $cashback)
                                                <tr>
                                                    <td><a href="{{ route('store.show', $cashback->store->slug) }}" target="_blank">{{ $cashback->store->name }}</a></td>
                                                    <td>{{ currency() }} {{ number_format((float) $cashback->order_value, 2, '.', '') }}</td>
                                                    <td>{{ currency() }} {{ number_format((float) $cashback->amount, 2, '.', '') }}</td>
                                                    <td>{{ Carbon\Carbon::parse($cashback->event_date)->isoFormat('Do MMMM YYYY') }}</td>
                                                    <td>
                                                        @if ($cashback->statusMap->status == 'confirmed')
                                                            <span class="badge badge-success">{{ $cashback->statusMap->status }}</span>
                                                        @elseif($cashback->statusMap->status == 'paid')
                                                            <span class="badge badge-success">{{ $cashback->statusMap->status }}</span>
                                                        @elseif($cashback->statusMap->status == 'failed')
                                                            <span class="badge badge-danger">{{ $cashback->statusMap->status }}</span>
                                                        @elseif($cashback->statusMap->status == 'pending')
                                                            <span class="badge badge-info">{{ $cashback->statusMap->status }}</span>
                                                        @elseif($cashback->statusMap->status == 'processing')
                                                            <span class="badge badge-info">{{ $cashback->statusMap->status }}</span>
                                                        @elseif($cashback->statusMap->status == 'donated')
                                                            <span class="badge badge-info">{{ $cashback->statusMap->status }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    @else
                        <div class="text-center">
                            <h2>No Earning Yet</h2>
                            <p>Once you start earning cashback your transactions will be listed here.</p>
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
