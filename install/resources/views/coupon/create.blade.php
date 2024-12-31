@extends('layouts.app')

@section('content')
    <div class="container-fluid my-md-0 my-4">
        <div class="row h-100vh">
            <div class="col-lg-8 col-md-10 m-auto">
                <form @can('coupon.store') action="{{ route('coupon.store') }}" @endcan method="POST"> @csrf
                    <div class="card rounded-12 border-0 shadow">
                        <div class="card-header d-flex align-items-center justify-content-between gap-2 py-3">
                            <h2 class="card-title m-0">{{ __('Add_New_Coupon') }}</h2>
                            <a href="{{ route('coupon.index') }}" class="btn btn-danger">
                                <i class="fa fa-arrow-left"></i> {{ __('Back') }}
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <label class="mb-1">{{ __('Code') }}</label>
                                    <x-input name="code" type="text" placeholder="Coupon code" />
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="mb-1">{{ __('Discount_Type') }}</label>
                                    <select name="discount_type"
                                        class="form-control @error('discount_type') is-invalid @enderror">
                                        <option value="">{{ __('Discount_Type') }}</option>
                                        @foreach ($discountTypes as $discountType)
                                            <option value="{{ $discountType->value }}">
                                                {{ __($discountType->value) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('discount_type')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="mb-1">{{ __('Discount') }}</label>
                                    <x-input name="discount" type="text" placeholder="Discount" />
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="mb-1">{{ __('Min_Amount') }}</label>
                                    <x-input name="min_amount" type="text" placeholder="Minimum Amount" />
                                </div>

                                <div class="col-12">
                                    <label for="">{{ __('Started_at') }}</label>
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <x-input type="date" name="start_date" />
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <x-input type="time" name="start_time" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="">{{ __('Expired_at') }}</label>
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <x-input type="date" name="expired_date" />
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <x-input type="time" name="expired_time" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <label for="yes">
                                                <input type="radio" id="yes" name="notify" value="1">
                                                {{ __('Yes_Notify_All_Customer.') }}
                                            </label>
                                            <label for="no" class="ml-3">
                                                <input type="radio" id="no" name="notify" value="0"> {{ __('Do_not_need') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer py-3 text-right">
                            @can('coupon.store')
                                <button type="submit" class="btn btn-primary px-5">
                                    {{ __('Submit') }}
                                </button>
                            @endcan
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
