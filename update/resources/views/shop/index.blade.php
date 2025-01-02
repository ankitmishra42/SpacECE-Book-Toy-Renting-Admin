@extends('layouts.app')

@section('content')
    <div class="container-fluid my-4">
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="card shadow-sm rounded-12">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <h2 class="card-title m-0">{{ __('Shop_List') }}</h2>
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('shop.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> {{ __('Add_New_Shop') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row row-gap">
            @foreach ($shops as $key => $store)
                <div class="col-12 col-md-6 col-xl-4 col-2xl-3">
                    <div class="card shadow-sm rounded-12 show-card position-relative overflow-hidden">
                        <div class="card-body shop p-2">
                            <div class="banner">
                                <img class="img-fit" src="{{ $store->banner?->file }}" />
                            </div>
                            <div class="main-content">
                                <div class="logo">
                                    <img class="img-fit" src="{{ $store->logo?->file }}" />
                                </div>
                                <div class="personal">
                                    <span class="name">{{ $store->name }}</span>
                                    <span class="email">{{ $store->user->email }}</span>
                                </div>
                            </div>
                            <div class="d-flex flex-column gap-2 px-3 mt-2">
                                <div class="item">
                                    <strong>{{ __('Status') }}</strong>
                                    <label class="switch mb-0">
                                        <a href="{{ route('shop.status.toggle', $store->user->id) }}">
                                            <input type="checkbox" {{ $store->user->is_active ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </a>
                                    </label>
                                </div>
                                <div class="item">
                                    <strong>{{ __('Services') }}</strong>
                                    <a href="{{ route('shop.service', $store->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-cogs m-0"></i>
                                        <span class="badge badge-warning m-0">
                                            {{ $store->services->count() }}
                                        </span>
                                    </a>
                                </div>
                                <div class="item">
                                    <strong>{{ __('Products') }}</strong>
                                    <a href="{{ route('shop.product', $store->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-tshirt text-white m-0"></i>
                                        <span class="badge badge-warning m-0">
                                            {{ $store->products->count() }}
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="overlay">
                            <a class="icons" href="{{ route('shop.edit', $store->id) }}">
                                <i class="fa fa-edit"></i>
                            </a>
                            <a class="icons" href="{{ route('shop.show', $store->id) }}">
                                <i class="fa fa-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <style>

    </style>
@endsection
