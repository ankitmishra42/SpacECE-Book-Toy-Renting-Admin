@extends('layouts.app')

@section('content')
<div class="container-fluid mt-5">
    <div class="col-sm-6 p-md-0 mt-2 mt-sm-0 d-flex">
        <a href="{{ route('service.index') }}" class="btn btn-danger mb-1"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
    </div>

    <div class="row">
        <div class="col-xl-7 col-xxl-7 col-lg-7 m-auto">
            <div class="card shadow rounded-12 border-0">
                <div class="card-header py-3">
                    <h4 class="card-title">{{ __('Add_New_Service') }}</h4>
                </div>
                <div class="card-body">
                    <x-form route="service.store" type="Submit">
                        <label>{{ __('Name') }}</label>
                        <x-input name="name" type='text' placeholder="Service name"/>

                        <label>{{ __('Thumbnail') }}</label>
                        <x-input-file name="image" type="file"/>

                        <label>{{ __('Description') }}</label>
                        <x-textarea name="description" placeholder="write description" />

                    </x-form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
