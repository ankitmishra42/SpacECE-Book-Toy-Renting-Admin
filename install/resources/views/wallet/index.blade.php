@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-3">
        <div class="page-header d-flex justify-content-between flex-wrap align-items-center">
            <div class="title">
                <h3 class="m-0">{{ __('Wallet') }}</h3>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-4 mb-3">
                <div class="wallet mb-3">
                    <span> {{ __('Available_Balance') }}</span>
                    <div class="amount">{{ currencyPosition($wallet->amount) }}</div>
                    <div class="card_holder">
                        <span class="name">{{ auth()->user()->name }}</span>
                    </div>
                    <div class="wallet_footer">
                        <div>
                            <span>{{ __('Create') }}</span>
                            <p class="m-0">{{ $wallet->created_at->format('d M, Y') }}</p>
                        </div>
                        <div>
                            <span>{{ __('Update') }}</span>
                            <p class="m-0">{{ $wallet->updated_at->format('d M, Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="">
                    <div class="card rounded-8 border-0 shadow">
                        <div class="card-body d-flex justify-content-between card-box">
                            <div class="box">
                                <h4 class="title">{{ __('Total_Transactions') }}</h4>
                                <h3 class="number">{{ currencyPosition($total) }}</h3>
                            </div>
                            <div class="icon">
                                <i class="fa fa-dollar"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 mb-3">
                <div class="card">
                    <div class="card-header d-flex justify-content-between py-2 align-items-center">
                        <span class="font-18 text-dark font-weight-bold">{{ __('Withdraw_Overview') }}</span>
                        <div>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#withdrawModal">
                                <i class="fa fa-plus"></i> {{ __('Withdraw') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-bordered" id="myTable">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('SL') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th> {{ __('Register_Date') }}</th>
                                    <th>{{ __('Accept_Date') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($withdraws as $key => $withdraw)
                                <tr>
                                    <td class="py-2">{{ ++$key }}</td>
                                    <td class="py-2">{{ $withdraw->amount }}</td>
                                    <td class="py-2">
                                        {{ $withdraw->created_at->format('d M, Y') }}
                                    </td>
                                    <td class="py-2">
                                        {{ $withdraw->accept ? Carbon\Carbon::parse($withdraw->accept)->format('d M, Y') : 'N/A' }}
                                    </td>
                                    <td class="py-2">
                                        @if ($withdraw->status == 'pending')
                                            <span class="badge badge-pill badge-warning">
                                                {{ $withdraw->status }}
                                            </span>
                                        @elseif ($withdraw->status == 'confirm')
                                            <span class="badge badge-pill badge-success">
                                                {{ $withdraw->status }}
                                            </span>
                                        @else
                                            <span class="badge badge-pill badge-danger">
                                                {{ $withdraw->status }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>

            {{-- withdraw modal --}}
            <form action="{{ route('wallet.withdraw', $wallet->id) }}" method="GET">
                @csrf
                <div class="modal fade" id="withdrawModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ __('Withdraw Balance Request') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                    <label class="m-0 font-weight-bold">{{ __('Amount') }} <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                {{ currencyPosition('0') }}
                                            </span>
                                        </div>
                                        <input type="text" name="amount" class="form-control" placeholder="Amount.." required onkeypress="onlyNumber(event)">
                                    </div>
                                    <div class="mt-3">
                                        <label class="mb-0">{{ __('Note') }}</label>
                                        <textarea name="note" class="form-control" rows="2" placeholder="Notes..."></textarea>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    {{ __('Close') }}
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Submit') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="col-12 mb-3">
                <div class="card">
                    <div class="card-header d-flex justify-content-between py-2">
                        <h3 class="card-title m-0">{{ __('Latest_transaction') }}</h3>
                        <a href="{{ route('wallet.transction', $wallet->id) }}" class="text-primary">
                            {{ __('View_All') }}
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th style="min-width: 120px">{{ __('Transition Type') }}</th>
                                        <th style="min-width: 120px">{{ __('Date') }}</th>
                                        <th>{{ __('Purpose') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Note') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $key => $transition)
                                        <tr>
                                            <th>{{ ++$key }}</th>
                                            <td>{{ $transition->transition_type }}</td>
                                            <td>{{ $transition->created_at->format('d M, Y') }}</td>
                                            <td>{{ $transition->purpose }}</td>
                                            <td>{{  currencyPosition($transition->amount) }}</td>
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
{{-- @push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            var dataLable = @json($transactionLable);
            var dataValue = @json($transactionValue);
            new ApexCharts(document.querySelector("#reportsChart"), {
                series: [{
                    name: 'This Month',
                    data: dataValue
                }],
                chart: {
                    height: 350,
                    type: 'area',
                    zoom: {
                        enabled: false
                    }
                },
                labels: dataLable,
                xaxis: {
                    type: 'text',
                },
                markers: {
                    size: 4
                },
                colors: ['#0E7490'],
                fill: {
                    type: "gradient",
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.3,
                        opacityTo: 0.4,
                        stops: [0, 90, 100]
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                }
            }).render();
        });
    </script>
@endpush --}}
