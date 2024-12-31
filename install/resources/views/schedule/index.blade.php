@extends('layouts.app')

@section('content')
    <div class="container-fluid my-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow rounded-12 border-0">
                    <div class="card-header py-3 bg-primary">
                        <h2 class="card-title m-0 text-white">{{ ucfirst($type) }} Schedules</h2>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered @role('store') table-striped @endrole" id="myTable">
                                <thead>
                                    <tr>
                                        @role('root|admin')
                                            <th>{{ __('Shop_Wise') }} {{ ucfirst($type) }}</th>
                                        @else
                                            <th scope="col">{{ __('Day') }}</th>
                                            <th scope="col">{{ __('Start_Time') }}</th>
                                            <th scope="col">{{ __('End_Time') }}</th>
                                            <th scope="col">{{ __('Per_hour') }}</th>
                                            <th scope="col">{{ __('Off_Day') }}</th>
                                            <th scope="col">{{ __('Action') }}</th>
                                        @endrole

                                    </tr>
                                </thead>
                                <tbody>
                                    @role('root|admin')
                                        @foreach ($stores as $store)
                                            <tr>
                                                <td class="p-2">
                                                    <div data-toggle="collapse" data-target="#storeVariants{{ $store->id }}"
                                                        class="variantGroup">
                                                        <div class="d-flex gap-4 align-items-center" style="gap: 10px">
                                                            <span>
                                                                <img src="{{ $store->logoPath }}" alt="" width="46"
                                                                    height="46">
                                                            </span>
                                                            <span>{{ $store->name }}</span>
                                                        </div>
                                                        <div>
                                                            <span
                                                                class="badge badge-primary">{{ $store->schedules()->where('type', ucfirst($type))->count() }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="collapse mt-2" id="storeVariants{{ $store->id }}">
                                                        <div class="card card-body p-2">
                                                            <table class="table table-bordered">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th>{{ __('Day') }}</th>
                                                                        <th>{{ __('Start_Time') }}</th>
                                                                        <th>{{ __('End_Time') }}</th>
                                                                        <th>{{ __('Per_hour') }}</th>
                                                                        <th>{{ __('Off_Day') }}</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($store->schedules()->where('type', ucfirst($type))->get() as $schedule)
                                                                        <tr>
                                                                            <td>{{ $schedule->day }}</td>
                                                                            <td>{{ $schedule->start_time }}:00</td>
                                                                            <td>{{ $schedule->end_time }}:00</td>
                                                                            <td>{{ $schedule->per_hour }}</td>
                                                                            <td>
                                                                                <label class="switch">
                                                                                    <a
                                                                                        href="{{ route('toggole.status.update', $schedule->id) }}">
                                                                                        <input type="checkbox"
                                                                                            {{ !$schedule->is_active ? 'checked' : '' }}>
                                                                                        <span class="slider round"></span>
                                                                                    </a>
                                                                                </label>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </td>
                                        @endforeach
                                    @else
                                        @foreach ($schedules as $schedule)
                                            <tr>
                                                <td>{{ $schedule->day }}</td>
                                                <td>{{ $schedule->start_time }}:00</td>
                                                <td>{{ $schedule->end_time }}:00</td>
                                                <td>{{ $schedule->per_hour }}</td>
                                                <td>
                                                    <label class="switch">
                                                        <a href="{{ route('toggole.status.update', $schedule->id) }}">
                                                            <input type="checkbox"
                                                                {{ !$schedule->is_active ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </a>
                                                    </label>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                                        data-target="#schedule_{{ $schedule->id }}">
                                                        <i class="fa fa-edit"></i>
                                                    </button>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="schedule_{{ $schedule->id }}" tabindex="-1"
                                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title" id="exampleModalLabel">{{ __('Update') }}
                                                                        {{ $schedule->day }} {{ __('Schedules') }}</h4>
                                                                    <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form
                                                                        action="{{ route('schedule.update', $schedule->id) }}"
                                                                        method="POST"> @csrf @method('put')
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <label class="mb-1">{{ __('Start_Time') }}</label>
                                                                                @php
                                                                                    $start = sprintf('%02s', $schedule->start_time) . ':00';
                                                                                    $end = sprintf('%02s', $schedule->end_time) . ':00';
                                                                                @endphp
                                                                                <x-input name="start_time" type="time"
                                                                                    value="{{ $start }}" />
                                                                            </div>

                                                                            <div class="col-12">
                                                                                <label class="mb-1">{{ __('End_Time') }}</label>
                                                                                <x-input name="end_time" type="time"
                                                                                    value="{{ $end }}" />
                                                                            </div>

                                                                            <div class="col-12">
                                                                                <label class="mb-1">{{ __('Per_hour') }}</label>
                                                                                <x-input name="per_hour" type="number"
                                                                                    value="{{ $schedule->per_hour }}" />
                                                                            </div>

                                                                            <div class="col-12 text-right">
                                                                                <button type="button" data-dismiss="modal"
                                                                                    class="btn btn-secondary px-5">{{ __('Cancle') }}</button>
                                                                                <button type="submit"
                                                                                    class="btn btn-primary px-5">{{ __('Update') }}</button>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endrole
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
