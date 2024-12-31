@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="header pt-5">

        <div class="header-body mt--4">
            <div class="row align-items-center pb-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 d-inline-block">{{__('Dashboard')}}</h6>
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item "><a href="{{ route('root') }}"><i class="fa fa-home text-primary"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page"> {{__('Dashboard')}} </li>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Card stats -->
            <div class="row">
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col mt-3 text-right">
                                    <h4 class="card-title text-uppercase text-muted mb-0">{{__('Shops')}}</h4>
                                    <span class="display-3 text-dark font-weight-bold mb-0">
                                        {{ $shop }}
                                    </span>
                                </div>
                                <div class="card-icon">
                                    <div class="icon icon-shape text-white shadow">
                                        <img width="70" src="{{ asset('images/icons/shop.png') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col mt-3 text-right">
                                    <h4 class="card-title text-uppercase text-muted mb-0">{{__('Products')}}</h4>
                                    <span class="display-3 text-dark font-weight-bold mb-0">
                                        @can('dashboard.calculation')
                                        {{ $products->count() }}
                                        @else
                                        00
                                        @endcan
                                    </span>
                                </div>
                                <div class="card-icon">
                                    <div class="icon icon-shape text-white shadow">
                                        <img width="80" src="{{ asset('images/icons/items.svg') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col mt-3 text-right">
                                    <h4 class="card-title text-uppercase text-muted mb-0">{{__('Services')}}</h4>
                                    <span class="display-3 text-dark font-weight-bold mb-0">
                                        @can('dashboard.calculation')
                                        {{ $services->count() }}
                                        @else
                                        00
                                        @endcan
                                    </span>
                                </div>
                                <div class="card-icon">
                                    <div class="icon icon-shape text-white shadow">
                                        <img width="80" src="{{ asset('images/icons/services.svg') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12 col-2xl-8">

            <div class="card p-3">
                <div class="">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h3 class="m-0">{{ __('Revenue') }}</h3>
                        @can('revenue.index')
                            <div>
                                <div class="dropdown">
                                    <button class="btn btn-secondary py-1 dropdown-toggle" type="button" id="filter-revunue" data-toggle="dropdown" aria-expanded="false">
                                        {{ ucfirst(\request()->type) ? __(ucfirst(\request()->type)) : __('Today') }}
                                    </button>

                                    <div class="dropdown-menu" aria-labelledby="filter-revunue">
                                        <a class="dropdown-item" href="{{ route('root', ['type' => 'today']) }}">{{ __('Today') }}</a>
                                        <a class="dropdown-item" href="{{ route('root', ['type' => 'week']) }}">{{ __('Week') }}</a>
                                        <a class="dropdown-item" href="{{ route('root', ['type' => 'month']) }}">{{ __('This_Month') }}</a>
                                        <a class="dropdown-item" href="{{ route('root', ['type' => 'year']) }}">{{ __('This_Year') }}</a>
                                    </div>
                                </div>
                                @php
                                    $type = ucfirst(\request()->type) ? ucfirst(\request()->type) : '';
                                @endphp
                                @can('revenue.generate.pdf')
                                    <a class="btn py-1 text-white btn-primary"
                                        href="{{ route('revenue.generate.pdf', ['type' => strtolower($type)]) }}" target="_blank">
                                        <i class="fas fa-file-download mr-1"></i> {{ __('Download') }}
                                    </a>
                                @endcan
                            </div>
                        @endcan
                    </div>
                </div>
                <hr class="my-3">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('Delivery_Date') }}</th>
                                <th scope="col">{{ __('Order_By') }}</th>
                                <th scope="col">{{ __('Quantity') }}</th>
                                <th scope="col">{{ __('Total') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @can('dashboard.revenue')
                            @forelse ($revenues as $revenue)
                                <tr>
                                    <td>
                                        {{ Carbon\Carbon::parse($revenue->delivery_date)->format('M d, Y') }}
                                    </td>
                                    <td>{{ $revenue->customer->user->name }}</td>
                                    <td>{{ $revenue->products->sum('pivot.quantity') }} {{ __('Pieces') }}</td>
                                    <td>{{ currencyPosition($revenue->payable_amount) }}</td>
                                    <td>
                                        <a href="{{ route('order.show', $revenue->id) }}" class="btn btn-primary">{{ __('Details') }}</a>
                                    </td>
                                </tr>
                            @empty
                                <tr class="text-center">
                                    <td colspan="5">{{  __('Sorry') }}, {{ ucfirst(\request()->type) ? __(ucfirst(\request()->type)) : __('Today') }} {{ __('revenue_not_found') }}</td>
                                </tr>
                            @endforelse
                            @else
                            <tr class="text-center">
                                <td colspan="5">{{ __('Sorry').', '. ucfirst(\request()->type) ? __(ucfirst(\request()->type)) : __('Today') }} {{ __('revenue_not_found') }}</td>
                            </tr>
                            @endcan
                            <tr>
                                <td colspan="3" class="text-right">{{ __('Total_Revenue') }}</td>
                                <td colspan="2">{{ currencyPosition($revenues->sum('amount')) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div class="col-12 col-2xl-4 mt-3 mt-2xl-0">
            <div class="card" style="border-radius: 10px; border-bottom: 4px solid #39D8D8;">
                <div class="overview">
                    <img width="100%" src="{{ asset('web/bg/overview.svg') }}" alt="">
                    <div>
                        <h2 class="text-white">{{ __('Overview') }}</h2>
                    </div>
                </div>

                @can('dashboard.overview')
                <div class="row p-3">
                    <div class="col-2xl-6 col-md-4 col-6 mb-3">
                        <img width="50" src="{{ asset('images/icons/users.svg') }}" class="float-left mr-2" alt="">
                        <div>
                            <h3 class="m-0 text-dark">{{ $customers->count() }}</h3>
                            <span class="txt-1">{{ __('Users') }}</span>
                        </div>
                    </div>
                    <div class="col-2xl-6 col-md-4 col-6 mb-3">
                        <img width="50" src="{{ asset('images/icons/Orders.svg') }}" class="float-left mr-2" alt="">
                        <div>
                            <h3 class="m-0 text-dark">{{ $confirmOrder }}</h3>
                            <span class="txt-1">{{ __('Orders') }}</span>
                        </div>
                    </div>
                    <div class="col-2xl-6 col-md-4 col-6 mb-3">
                        <img width="50" src="{{ asset('images/icons/Pending.svg') }}" class="float-left mr-2" alt="">
                        <div>
                            <h3 class="m-0 text-dark">{{ $pendingOrder }}</h3>
                            <span class="txt-1">{{ __('Pending') }}</span>
                        </div>
                    </div>
                    <div class="col-2xl-6 col-md-4 col-6 mb-3">
                        <img width="50" src="{{ asset('images/icons/progress.svg') }}" class="float-left mr-2" alt="">
                        <div>
                            <h3 class="m-0 text-dark">{{  $onPregressOrder }}</h3>
                            <span class="txt-1">{{ __('On_progress') }}</span>
                        </div>
                    </div>
                    <div class="col-2xl-6 col-md-4 col-6">
                        <img width="50" src="{{ asset('images/icons/delivered.svg') }}" class="float-left mr-2" alt="">
                        <div>
                            <h3 class="m-0 text-dark">{{ $completeOrder }}</h3>
                            <span class="txt-1">{{ __('Delivered') }}</span>
                        </div>
                    </div>
                    <div class="col-2xl-6 col-md-4 col-6 ">
                        <img width="50" src="{{ asset('images/icons/order.svg') }}" class="float-left mr-2" alt="">
                        <div>
                            <h3 class="m-0 text-dark">{{ $cancelledOrder }}</h3>
                            <span class="txt-1">{{ __('Cancel_Order') }}</span>
                        </div>
                    </div>
                </div>
                @else
                <div class="row p-3">
                    <div class="col-2xl-6 col-md-4 col-6 mb-3">
                        <img width="50" src="{{ asset('images/icons/users.svg') }}" class="float-left mr-2" alt="">
                        <div>
                            <h3 class="m-0 text-dark">00</h3>
                            <span class="txt-1">{{ __('Users') }}</span>
                        </div>
                    </div>
                    <div class="col-2xl-6 col-md-4 col-6 mb-3">
                        <img width="50" src="{{ asset('images/icons/Orders.svg') }}" class="float-left mr-2" alt="">
                        <div>
                            <h3 class="m-0 text-dark">00</h3>
                            <span class="txt-1">{{ __('Orders') }}</span>
                        </div>
                    </div>
                    <div class="col-2xl-6 col-md-4 col-6 mb-3">
                        <img width="50" src="{{ asset('images/icons/Pending.svg') }}" class="float-left mr-2" alt="">
                        <div>
                            <h3 class="m-0 text-dark">00</h3>
                            <span class="txt-1">{{ __('Pending') }}</span>
                        </div>
                    </div>
                    <div class="col-2xl-6 col-md-4 col-6 mb-3">
                        <img width="50" src="{{ asset('images/icons/progress.svg') }}" class="float-left mr-2" alt="">
                        <div>
                            <h3 class="m-0 text-dark">00</h3>
                            <span class="txt-1">{{ __('On_progress') }}</span>
                        </div>
                    </div>
                    <div class="col-2xl-6 col-md-4 col-6">
                        <img width="50" src="{{ asset('images/icons/delivered.svg') }}" class="float-left mr-2" alt="">
                        <div>
                            <h3 class="m-0 text-dark">00</h3>
                            <span class="txt-1">{{ __('Delivered') }}</span>
                        </div>
                    </div>
                    <div class="col-2xl-6 col-md-4 col-6 ">
                        <img width="50" src="{{ asset('images/icons/order.svg') }}" class="float-left mr-2" alt="">
                        <div>
                            <h3 class="m-0 text-dark">00</h3>
                            <span class="txt-1">{{ __('Cancel_Order') }}</span>
                        </div>
                    </div>
                </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
