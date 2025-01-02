@extends('layouts.app')
@section('content')
    <div class="container-fluid my-4 my-md-0">
        <div class="row d-flex align-items-center h-100vh">
            <div class="col-md-8 m-auto">
                <form action="{{ route('profile.change-password') }}" method="POST">
                    @csrf
                    <div class="card shadow rounded-12 border-0">
                        <div class="card-header bg-primary py-3">
                            <h3 class="m-0 text-white">{{ __('Change_Password') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="mb-0">{{ __('Current_Password') }}</label>
                                <input type="text" name="current_password" placeholder="Enter Current Password"
                                    class="form-control" value="{{ old('current_password') }}">
                                @error('current_password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="mb-0">{{ __('New_Password') }}</label>
                                <input type="text" name="password" placeholder="Enter New Password" class="form-control" value="{{ old('password') }}">
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-0">
                                <label class="mb-0">{{ __('Confirm_Password') }}</label>
                                <input type="text" name="password_confirmation" placeholder="Enter Confirm Password" class="form-control">
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between py-3">
                            <a href="{{ url()->previous() }}" class="btn btn-danger">{{ __('Back') }}</a>
                            <button class="btn btn-primary">{{ __('Submit') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
