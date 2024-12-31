@extends('layouts.app')

@section('content')
    <div class="container-fluid my-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="card rounded-12 shadow border-0">
                    <div class="card-header py-2 d-flex align-items-center justify-content-between flex-wrap">
                        <h2 class="card-title m-0">{{ __('Shop_List') }}</h2>
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('shop.store.export') }}" class="btn btn-primary text-white" data-toggle="tooltip" data-placement="bottom" title="Export all shop">
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
                                        <th class="px-2 text-center" style="width: 25px">SL.</th>
                                        <th> {{ __('Name') }}</th>
                                        <th>{{ __('Commission') }}</th>
                                        <th>{{ __('Total_selling') }}</th>
                                        <th>{{ __('Total_revenue') }}</th>
                                        <th>{{ __('Commission_cost') }}</th>
                                        <th>{{ __('Total_order') }}</th>
                                        <th>{{ __('total_cencel_order') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($shops as $key => $store)
                                        @php
                                            $productIds = $store
                                                ->products()
                                                ->pluck('id')
                                                ->toArray();
                                            $total = \App\Models\OrderProduct::whereIn('id', $productIds)->get();

                                            $amount = $store->orders->sum('total_amount');

                                            //Total Cencel Order
                                            $cencel = $store->orders->where('order_status', 'Cancelled')->count();

                                        @endphp
                                        <tr>
                                            <td class="px-3 text-center" style="width: 25px">
                                                {{ ++$key }}
                                            </td>
                                            <td style="min-width: 160px">{{ $store->name }}</td>
                                            <td>{{ $store->commission }}%</td>
                                            <td>{{ $total->sum('quantity') }}</td>
                                            <td>{{ currencyPosition($store->orders->sum('total_amount')) }} </td>
                                            <td>
                                                {{ currencyPosition(round(($amount / 100) * $store->commission, 2)) }}
                                            </td>
                                            <td>{{ $store->orders->count() }}</td>
                                            <td>{{ $cencel }}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('shoplist.details', $store->id) }}"
                                                        class="btn btn-primary py-2 px-3">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    <a href="{{ route('shop.store.export', $store->id) }}" class="btn btn-secondary py-2 px-3" data-toggle="tooltip" data-placement="bottom" title="Export Report by Store">
                                                        <i class="fa fa-table" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            </td>
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
