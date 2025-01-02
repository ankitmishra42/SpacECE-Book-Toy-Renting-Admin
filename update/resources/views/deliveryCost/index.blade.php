@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-12 m-auto">
                <div class="card">
                    <div class="card-header bg-primary py-2">
                       <h3 class="m-0 text-white"> {{ __('Delivery_Cost') }}</h3>
                    </div>
                    <form action="{{ route('deliveryCost') }}" method="post">
                        @csrf
                        <div class="card-body">
                            @php
                                $currency = App\Models\AppSetting::first()?->currency ?? '$';
                            @endphp
                            <div class="row">
                                <div class="col-sm-4">
                                    <label class="m-0">{{ __('Delivery_charge') }}</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">{{$currency}}</span>
                                        </div>
                                        <input type="number" class="form-control" name="cost"
                                            value="{{ $cost ? $cost->cost : 0 }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <label class="m-0">{{ __('Fee_Delivery') }}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">{{$currency}}</span>
                                        </div>
                                        <input type="number" class="form-control" name="fee_cost"
                                            value="{{ $cost ? $cost->fee_cost : 100 }}" required>
                                    </div>
                                    <small class="text-gray">{{ __('Default_delivery_fee_100') }}</small>
                                </div>

                                <div class="col-sm-4">
                                    <label class="m-0">{{ __('Minimum_order_amount') }}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">{{$currency}}</span>
                                        </div>
                                        <input type="number" class="form-control" name="minimum_cost"
                                            value="{{ $cost ? $cost->minimum_cost : 00 }}" required>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer py-2">
                            <button type="submit" class="btn btn-primary px-4">{{ __('Save_And_Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
