@extends('layouts.app')

@section('content')
    <div class="container-fluid my-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="card rounded-12 border-0 shadow">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap" style="gap: 8px">
                        <h2 class="card-title m-0">{{ __('Additional_Service') }}</h2>
                        <a href="{{ route('additional.create') }}" class="btn btn-primary">
                            {{ __('Create').' '. __('Additional_Service') }}
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table-bordered table-striped table" id="myTable">
                                <thead>
                                    <tr>
                                        <th scope="col">{{ __('Name') }}</th>
                                        <th scope="col">{{ __('Description') }}</th>
                                        <th scope="col">{{ __('Service') }}</th>
                                        <th scope="col">{{ __('Price') }}</th>
                                        <th scope="col">{{ __('Status') }}</th>
                                        <th style="min-width: 130px" scope="col">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($additionals as $additional)
                                        <tr>
                                            <td>{{ $additional->title }}</td>
                                            <td>
                                                {{ substr($additional->description, 0, 25) }}
                                            </td>
                                            <td>
                                                {{ $additional->services?->name }}
                                            </td>
                                            <td>{{ currencyPosition($additional->price) }}</td>
                                            <td>
                                                <label class="switch">
                                                    <a href="{{ route('additional.status.toggle', $additional->id) }}">
                                                        <input {{ $additional->is_active ? 'checked' : '' }} type="checkbox">
                                                        <span class="slider round"></span>
                                                    </a>
                                                </label>
                                            </td>
                                            <td>
                                                <span>
                                                    <a href="{{ route('additional.edit', $additional->id) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="far fa-edit"></i>
                                                    </a>
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
