@extends('layouts.app')

@section('content')
<div class="container-fluid my-4">
    <div class="row">
        <div class="col-xl-12 col-xxl-12 col-lg-12 m-auto">
            <div class="card shadow rounded-12 border-0">
                <div class="card-header py-3 bg-primary">
                    <h3 class="card-title m-0 text-white">{{ __($setting->title) }}</h3>
                </div>
                <div class="card-body">
                    {!! $setting->content !!}
                </div>
                <div class="card-footer py-3 text-right">
                    <a href="{{ route('setting.edit', $setting->slug) }}" class="btn btn-primary">
                        {{ __('Edit') }}
                    </a>
               </div>
            </div>
        </div>
    </div>
</div>
@endsection
