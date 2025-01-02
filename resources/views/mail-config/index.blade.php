@extends('layouts.app')
@section('content')
    <div class="container-fluid my-4">
        <div class="row">
            <div class="col-xl-8 col-lg-9 mt-2 mx-auto ">
                <form action="{{ route('mail-config.update') }}" method="POST">
                    @method('put')
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h3 class="m-0">{{ __('Mail_Configuration') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="m-0">{{ __('Mail_Mailer') }}</label>
                                    <x-input :value="config('app.mail_mailer')" name="mailer" type="text"
                                        placeholder="smtp" />
                                </div>
                                <div class="col-lg-6">
                                    <label class="m-0">{{ __('Mail_Host') }}</label>
                                    <x-input :value="config('app.mail_host')" name="host" type="text" placeholder="ex: 465"/>
                                </div>
                                <div class="col-lg-6">
                                    <label class="m-0">{{ __('Mail_Port') }}</label>
                                    <x-input :value="config('app.mail_port')" name="port" type="text" placeholder="ex: smtp.gmail.com"/>
                                </div>
                                <div class="col-md-6">
                                    <label class="m-0">{{ __('Mail_User_Name') }}</label>
                                    <x-input :value="config('app.mail_username')" name="username" type="text" placeholder="ex: example@gmail.com"/>
                                </div>
                                <div class="col-md-6">
                                    <label class="m-0">{{ __('Mail_Password') }}</label>
                                    <x-input :value="config('app.mail_password')" name="password" type="text" placeholder="Your app password"/>
                                </div>
                                <div class="col-md-6">
                                    <label class="m-0">{{ __('Mail_Encryption') }}</label>
                                    <x-input :value="config('app.mail_encryption')" name="encryption" type="text" placeholder="tls or ssl "/>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="m-0">{{ __('Mail_From_Address') }}</label>
                                    <x-input :value="config('app.mail_from_address')" name="from_address" type="text" placeholder="from email address" required />
                                </div>
                                <div class="col-lg-6 d-flex align-items-center">
                                    <input id="twoStep" type="checkbox" name="two_step_verification"
                                        {{ config('app.mail_two_step_verification') ? 'checked' : '' }}
                                        style="width: 20px; height: 20px;">
                                    <label for="twoStep" class="m-0 ml-1">
                                        {{ __('Two_Step_Verification') }}
                                        <button type="button" class="infoBtn bg-info" data-toggle="tooltip"
                                            data-placement="top"
                                            title="When two-step is on the user must verify their email address.">
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
