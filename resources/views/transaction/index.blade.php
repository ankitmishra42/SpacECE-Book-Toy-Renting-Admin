@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-3">
        <div class="page-header d-flex justify-content-between flex-wrap align-items-center">
            <div class="title">
                <a href="{{ url()->previous() }}">{{ __('Wallet') }}/</a>{{ __('Transition') }}
            </div>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> {{ __('Back') }}
            </a>
        </div>

        <div class="row">

            <div class="col-12 my-3">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3 class="card-title m-0">{{ __('All_Transition') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="myTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th style="min-width: 120px">{{ __('Transition_Type') }}</th>
                                        <th style="min-width: 120px">{{ __('Date') }}</th>
                                        <th>{{ __('Purpose') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Note') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($wallet->transactions as $key => $transition)
                                        <tr>
                                            <th>{{ ++$key }}</th>
                                            <td>{{ $transition->transition_type }}</td>
                                            <td>{{ $transition->created_at->format('d M, Y') }}</td>
                                            <td>{{ $transition->purpose }}</td>
                                            <td>{{ currencyPosition($transition->amount) }}</td>
                                            <td>{{ $transition->note }}</td>
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
