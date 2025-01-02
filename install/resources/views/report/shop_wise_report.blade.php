@extends('layouts.app')

@section('content')
    <div class="container-fluid my-4">
        <div class="page-header d-flex justify-content-between flex-wrap mb-3 align-items-center">
            <div class="title"><a href="{{ route('shop.index') }}">
                    {{ __('Shops') }}</a> / <strong>{{ $store->name }}</strong>
            </div>
            <a href="{{ route('shop.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> {{ __('Back') }}
            </a>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card rounded-12 shadow border-0">
                    <div class="card-header py-2 d-flex align-items-center justify-content-between flex-wrap">
                        <h2 class="card-title m-0">{{ __('Shop_wise_Report') }}</h2>
                        <div class="">
                            <a href="{{ route('shop.generateReport', $store->id) }}" class="btn btn-warning"> {{ __('Export_PDF') }}</a>
                            <a href="{{ route('shop.order.export', $store->id) }}" class="btn btn-primary text-white" data-toggle="tooltip" data-placement="bottom"
                                title="Export all shop">
                                {{ __('Export_Excle') }}
                                <i class="fa fa-table" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover" id="myTable">
                                <thead>
                                    <tr>
                                        <th> {{ __('Customer_Name') }}</th>
                                        <th>{{ __('Order_Date') }}</th>
                                        <th>{{ __('Total_Amount') }}</th>
                                        <th>{{ __('Discount_Amount') }}</th>
                                        <th>{{ __('Delivery_charge') }}</th>
                                        <th>{{ __('Total_Quantity') }}</th>
                                        <th>{{ __('Delivery_Date') }}</th>
                                        <th>{{ __('Payment_Method') }}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($store->orders as $order)
                                        @php
                                            $quantity = 0;
                                            foreach ($order->products as $product) {
                                                $quantity += $product->pivot->quantity;
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ $order->customer->name }}</td>
                                            <td>
                                                {{ Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}
                                            </td>
                                            <td>{{ currencyPosition($order->total_amount) }} </td>
                                            <td>{{ currencyPosition($order->discount ?? '0') }}</td>
                                            <td>{{ currencyPosition($order->delivery_charge) }}</td>
                                            <td>{{ $quantity }} {{ __('Pieces') }}</td>
                                            <td>
                                                {{ parse($order->delivery_date, 'M d, Y') }}
                                            </td>
                                            <td>{{ $order->payment_type }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
