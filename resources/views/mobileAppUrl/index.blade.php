@extends('layouts.app')

@section('content')
    <div class="container-fluid my-4 my-md-0">
        <div class="row h-100vh align-items-center">
            <div class="col-md-10 col-lg-8 m-auto">
                <form action="{{ route('mobileApp') }}" method="post">
                    @csrf
                    <div class="card rounded-12 border-0 shadow">
                        <div class="card-header bg-primary py-3">
                            <h3 class="m-0 text-white">{{ __('Mobile_App_Link') }}</h3>
                        </div>

                        <div class="card-body">
                            <label class="m-0">{{ __('Android_Url') }}</label>
                            <div class="input-group mb-3">
                                <input type="text" name="android_url" class="form-control" placeholder="Android App Link"
                                    value="{{ $appLink ? $appLink->android_url : '' }}">
                            </div>

                            <label class="m-0">{{ __('IOS_Url') }}</label>
                            <div class="input-group">
                                <input type="text" name="ios_url" class="form-control" placeholder="IOS App Link"
                                    value="{{ $appLink ? $appLink->ios_url : '' }}">
                            </div>
                        </div>
                        <div class="card-footer py-3">
                            <button type="submit" class="btn btn-primary px-4">{{ __('Save_And_Update') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endsection
