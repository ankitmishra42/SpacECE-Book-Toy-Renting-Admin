@extends('layouts.app')
@section('content')
    <div class="container-fluid my-4">
        <div class="row">
            <div class="col-xl-8 col-lg-9 mt-2 mx-auto ">
                <form action="{{ route('sms-gateway.update') }}" method="POST">
                    @method('put')
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title m-0">
                                {{ __('SMS_Configuration') }}
                                <a class="text-info" href="https://www.mobivate.com/bulk-sms/mobile-marketing-costs"
                                    target="__blanck">Click To Go</a>
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label class="m-0">{{ __('URL') }}</label>
                                    <x-input :value="config('app.sms_base_url')" name="url" type="text"
                                        placeholder="URL (ex. https://app.mobivatebulksms.com/gateway/api/simple/MT)" />
                                </div>
                                <div class="col-lg-6">
                                    <label class="m-0">{{ __('User').' '.__('Name') }}</label>
                                    <x-input :value="config('app.sms_user_name')" name="user_name" type="text" placeholder="User Name"
                                        required />
                                </div>
                                <div class="col-md-6">
                                    <label class="m-0">{{ __('Password') }}</label>
                                    <x-input :value="config('app.sms_password')" name="password" type="text" placeholder="Password"
                                        required />
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="m-0">{{ __('Source') }}</label>
                                    <x-input :value="config('app.sms_source')" name="source" type="text" placeholder="Source" required />
                                </div>
                                <div class="col-lg-6 d-flex align-items-center">
                                    <input id="twoStep" type="checkbox" name="two_step_verification"
                                        {{ config('app.sms_two_step_verification') ? 'checked' : '' }}
                                        style="width: 20px; height: 20px;">
                                    <label for="twoStep" class="m-0 ml-1">
                                        {{ __('Two_Step_Verification') }}
                                        <button type="button" class="infoBtn bg-info" data-toggle="tooltip"
                                            data-placement="top"
                                            title="When two-step is on and the user must verify their mobile number.">
                                            <i class="fa fa-info"></i>
                                        </button>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <style>
        .infoBtn {
            border: none;
            width: 20px;
            height: 20px;
            border-radius: 100%;
            font-size: 12px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            color: #fff;
        }
    </style>
@endsection
