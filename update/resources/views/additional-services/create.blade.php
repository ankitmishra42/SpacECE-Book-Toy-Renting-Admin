@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-5">
        <div class="col-sm-6 p-md-0 mt-2 mt-sm-0 d-flex">
            <a href="{{ route('additional.index') }}" class="btn btn-danger"><i class="fa fa-arrow-left"></i> Back</a>
        </div>

        <div class="row">
            <div class="col-xl-7 col-xxl-7 col-lg-7 m-auto">
                <div class="card shadow rounded-12 border-0">
                    <div class="card-header">
                        <h4 class="card-title m-0">{{ __('Create') .' '. __('Additional_Service') }}</h4>
                    </div>
                    <div class="card-body py-3">
                        <x-form route="additional.store" type="Submit">

                            <div class="mb-3">
                                <label class="mb-0">{{ __('Title') }}</label>
                                <x-input name="title" placeholder="Title" type='text' />
                            </div>

                            <div class="mb-3">
                                <label class="mb-0">{{ __('Price') }}</label>
                                <x-input name="price" placeholder="Price" type="number" />
                            </div>

                            <div class="mb-3">
                                <label class="mb-0">{{ __('Service') }}</label>
                                <x-select name="service_id">
                                    @foreach ($services as $service)
                                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                                    @endforeach
                                </x-select>
                            </div>

                            <x-textarea name="description" placeholder="write description" />
                        </x-form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
