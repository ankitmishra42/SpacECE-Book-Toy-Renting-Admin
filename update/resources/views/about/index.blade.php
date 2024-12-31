@extends('layouts.app')

@section('content')
<div class="container-fluid my-3 my-md-0">
    <div class="row row h-100vh align-items-center">
        <div class="col-lg-8 m-auto">
            <div class="card shadow rounded-12 border-0">
                <div class="card-header py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h3 class="card-title m-0">{{ __('about-us') }}</h3>
                    <a href="{{ route('about.edit') }}" class="btn btn-primary">Edit</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered ">
                        <tr>
                            <th>{{ __('Title') }}</th>
                            <td>{{ $about?->title }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Phone_number') }}</th>
                            <td>{{ $about?->phone }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Whatsapp') }}</th>
                            <td>{{ $about?->whatsapp }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Email') }}</th>
                            <td>{{ $about?->email }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Description') }}</th>
                            <td>{{ $about?->description }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
