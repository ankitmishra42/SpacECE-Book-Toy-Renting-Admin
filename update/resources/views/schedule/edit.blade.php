@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-lg-8 col-md-10 m-auto">
                <form action="" method="POST"> @csrf @method('put')
                    <div class="card rounded-12 border-0 shadow">
                        <div class="card-header bg-primary py-3">
                            <h2 class="card-title m-0 text-white">{{ ucfirst($type) }} {{ __('Schedules') }}</h2>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <label class="mb-1"> {{ __('Start_Time') }}</label>
                                    <x-input name="start_time" type="time" value="{{ $start }}" />
                                </div>

                                <div class="col-12">
                                    <label class="mb-1">{{ __('End_Time') }}</label>
                                    <x-input name="start_time" type="time" value="{{ $end }}" />
                                </div>

                                <div class="col-12">
                                    <label class="mb-1">{{ __('Per_hour') }}</label>
                                    <x-input name="per_hour" type="number" value="{{ $orderSchedule->per_hour }}" />
                                </div>

                                <div class="col-12 text-right">
                                    <button type="submit" class="btn btn-primary px-5">{{ __('Update') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
