@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-lg-12">
                <!-- Revenue Charts -->
                <div class="col-xl-12 mb-5 mb-xl-0">
                    <div class="card shadow border-0 rounded-12">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <h6 class="text-uppercase text-muted ls-1 mb-1">{{ __('Income') }}</h6>
                                    <h2 class="text-default mb-0">{{ __('Revenue') }}</h2>
                                </div>

                                <div class="col-md-8">
                                    <form action="{{ route('revenue.index') }}" method="GET">
                                        <ul class="nav nav-pills justify-content-end">
                                            <li class="nav-item m-0">
                                                <input type="date" class="form-control" name='from'
                                                    placeholder="Search Date" value="{{ request('from') }}"
                                                    style="height: 43px;" />
                                            </li>
                                            <li class="nav-item m-0 ml-1">
                                                <input type="date" class="form-control" name='to'
                                                    placeholder="Search Date" value="{{ request('to') }}"
                                                    style="height: 43px;" />
                                            </li>
                                            <li class="nav-item m-0">
                                                <button type="submit" class="btn btn-info ml-1">{{ __('Filter') }}</button>
                                            </li>
                                        </ul>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped verticle-middle table-responsive-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col">{{ __('Delivery_Date') }}</th>
                                            <th scope="col">{{ __('Order_By') }}</th>
                                            <th scope="col">{{ __('Quantity') }}</th>
                                            <th scope="col">{{ __('Total') }}</th>
                                            @can('order.show')
                                                <th scope="col">{{ __('Action') }}</th>
                                            @endcan
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($revenues as $revenue)
                                            <tr>
                                                <td>
                                                    {{ parse($revenue->delivery_date, 'M d, Y') }}
                                                </td>
                                                <td>{{ $revenue->customer->user->name }}</td>
                                                <td>{{ $revenue->products->sum('pivot.quantity') }} {{ __('Pieces') }}</td>
                                                <td>{{ currencyPosition($revenue->payable_amount) }}</td>
                                                @can('order.show')
                                                    <td>
                                                        <a href="{{ route('order.show', $revenue->id) }}"
                                                            class="btn btn-primary">{{ __('Details') }}</a>
                                                    </td>
                                                @endcan
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">{{ __('Sorry_revenue_report_not_found') }}</td>
                                            </tr>
                                        @endforelse
                                        <tr>
                                            <td @can('order.show') colspan="3" @else colspan="2" @endcan class="text-right">
                                                {{ __('Total_Revenue') }}</td>
                                            <td>{{ currencyPosition($revenues->sum('payable_amount')) }}</td>
                                            <td>
                                                @can('report.generate.pdf')
                                                    <a class="btn btn-warning"
                                                        href="{{ route('report.generate.pdf', ['from' => \request('from'), 'to' => \request('to')]) }}"
                                                        target="_blank">{{ __('Print_Report') }}</a>
                                                @endcan
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
