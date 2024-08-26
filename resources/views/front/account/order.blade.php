@extends('front.Layouts.app')
@section('content')
    <main>
        <section class="section-5 pt-3 pb-3 mb-3 bg-white">
            <div class="container">
                <div class="light-font">
                    <ol class="breadcrumb primary-color mb-0">
                        <li class="breadcrumb-item"><a class="white-text" href="{{ route('account.profile') }}">My Account</a>
                        </li>
                        <li class="breadcrumb-item">My Orders</li>
                    </ol>
                </div>
            </div>
        </section>

        <section class=" section-11 ">
            <div class="container  mt-5">
                <div class="row">
                    <div class="col-md-3">
                        @include('front.account.common.sidebar')
                    </div>
                    <div class="col-md-9">
                        <div class="card">

                            <div class="card-header">
                                <h2 class="h5 mb-0 pt-2 pb-2">My Orders</h2>
                            </div>
                            <div class="card-body p-4">
                                <div class="card-body p-4">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Orders #</th>
                                                    <th>Date Purchased</th>
                                                    <th>Status</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($order->isNotEmpty())
                                                    @foreach ($order as $item)
                                                        <tr>
                                                            <td>
                                                                <a
                                                                    href="{{ route('account.orderdetail', $item->id) }}">{{ $item->id }}</a>
                                                            </td>
                                                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M, Y') }}
                                                            </td>
                                                            <td>
                                                                @if ($item->status == 'pending')
                                                                    <span class="badge bg-danger">Pending</span>
                                                                @elseif($item->status == 'shipped')
                                                                    <span class="badge bg-warning">Shipped</span>
                                                                @else
                                                                    <span class="badge bg-success">Delivered</span>
                                                                @endif

                                                            </td>
                                                            <td>${{ number_format($item->grand_total, 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="3" class="text-center">
                                                            Orders not found..!!
                                                        </td>
                                                    </tr>
                                                @endif


                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
