@extends('layouts.app')

@section('content')
<div class="container-fluid my-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow rounded-12 border-0">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h2 class="card-title m-0">All Admins</h2>
                    <a href="{{ route('admin.create') }}" class="btn btn-primary">Create New Admin</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped" id="myTable">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Email') }}</th>
                                <th scope="col">{{ __('Mobile') }}</th>
                                <th scope="col">{{ __('Gender') }}</th>
                                <th scope="col">{{ __('Create') }}</th>
                                <th scope="col">{{ __('Status') }}</th>
                                <th scope="col" class="px-2">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($admins as $admin)
                            <tr>
                                <td>{{ $admin->name }}</td>
                                <td>{{ $admin->email }}</td>
                                <td>{{ $admin->mobile }}</td>
                                <td>{{ $admin->gender ?? '--' }}</td>
                                <td>{{ $admin->created_at->format('M d, Y') }}</td>
                                <td>
                                    <label class="switch">
                                        <a href="{{ route('admin.status-update', $admin->id) }}">
                                            <input type="checkbox" {{ $admin->is_active ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </a>
                                    </label>
                                </td>
                                <td>
                                    <a href="{{ route('admin.edit', $admin->id) }}" class="btn btn-danger py-1 px-2">{{ __('Edit') }}</a>
                                    <a href="{{ route('admin.show', $admin->id) }}" class="btn btn-primary py-1 px-2">{{ __('Permissions') }}</a>
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
@endsection
