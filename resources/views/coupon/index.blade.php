@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="card rounded-12 shadow border-0">
                    <div class="card-header py-2 d-flex justify-content-between flex-wrap gap-6 align-items-center">
                        <h2 class="card-title m-0">{{ __('Coupons') }}</h2>
                        @role('store')
                            <a href="{{ route('coupon.create') }}" class="btn btn-primary">
                                {{ __('Add_New_Coupon') }}
                            </a>
                        @endrole
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered @role('store') table-striped @endrole" id="myTable">
                                <thead>
                                    <tr>
                                        @role('root|admin')
                                            <th>{{ __('Shop_Wise_Coupon') }}</th>
                                        @endrole
                                        @role('store')
                                            <th scope="col">{{ __('Code') }}</th>
                                            <th scope="col">{{ __('Discount') }}</th>
                                            <th scope="col">{{ __('Min_Amount') }}</th>
                                            <th scope="col">{{ __('Started_at') }}</th>
                                            <th scope="col">{{ __('Expired_at') }}</th>
                                            <th scope="col">{{ __('Action') }}</th>
                                        @endrole
                                    </tr>
                                </thead>
                                <tbody>
                                    @role('root|admin')
                                        @foreach ($stores as $store)
                                            <tr>
                                                <td class="p-2">
                                                    <div data-toggle="collapse" data-target="#storeCoupons{{ $store->id }}"
                                                        class="variantGroup">
                                                        <div class="d-flex gap-4 align-items-center" style="gap: 10px">
                                                            <span>
                                                                <img src="{{ $store->logoPath }}" alt="" width="46"
                                                                    height="46">
                                                            </span>
                                                            <span>{{ $store->name }}</span>
                                                        </div>
                                                        <div>
                                                            <span
                                                                class="badge badge-primary">{{ count($store->coupons) }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="collapse mt-2" id="storeCoupons{{ $store->id }}">
                                                        <div class="card card-body p-2">
                                                            <table class="table table-bordered">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th>{{ __('Code') }}</th>
                                                                        <th>{{ __('Discount') }}</th>
                                                                        <th>{{ __('Min_Amount') }}</th>
                                                                        <th>{{ __('Started_at') }}</th>
                                                                        <th>{{ __('Expired_at') }}</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($store->coupons as $coupon)
                                                                        <tr>
                                                                            <td>{{ $coupon->code }}</td>
                                                                            <td>{!! $coupon->type->value == 'Amount' ? currencyPosition($coupon->discount) : $coupon->discount . '%' !!}</td>
                                                                            <td>{{ currencyPosition($coupon->min_amount) }}
                                                                            </td>
                                                                            <td>{{ Carbon\Carbon::parse($coupon->started_at)->format('M d, Y h:i a') }}
                                                                            </td>
                                                                            <td>{{ Carbon\Carbon::parse($coupon->expired_at)->format('M d, Y h:i a') }}
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </td>
                                        @endforeach
                                    @endrole
                                    @role('store')
                                        @foreach ($coupons as $coupon)
                                            <tr>
                                                <td>{{ $coupon->code }}</td>
                                                <td>{!! $coupon->type->value == 'Amount' ? currencyPosition($coupon->discount) : $coupon->discount . '%' !!}</td>
                                                <td>{{ currencyPosition($coupon->min_amount) }}</td>
                                                <td>{{ Carbon\Carbon::parse($coupon->started_at)->format('M d, Y h:i a') }}
                                                </td>
                                                <td>{{ Carbon\Carbon::parse($coupon->expired_at)->format('M d, Y h:i a') }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('coupon.edit', $coupon->id) }}" class="btn btn-primary">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endrole
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
