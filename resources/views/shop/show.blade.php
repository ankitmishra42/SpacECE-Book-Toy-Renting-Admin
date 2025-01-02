@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="page-header d-flex justify-content-between flex-wrap mb-3 align-items-center">
            <div class="title"><a href="{{ route('shop.index') }}">
                    {{ __('Shops') }}</a> / <strong>{{ $store->name }}</strong>
            </div>
            <a href="{{ route('shop.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> {{ __('Back') }}
            </a>
        </div>

        <div class="row">
            <div class="col-md-6 col-xl-4 col-2xl-3 mb-3">
                <div class="dashboard-summery bg-grow-early">
                    <h2>{{ count($store->orders) }}</h2>
                    <h3>{{ __('Total_order') }}</h3>
                    <div class="icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4 col-2xl-3 mb-3">
                <div class="dashboard-summery bg-midnight">
                    <h2>{{ $store->products->count() }}</h2>
                    <h3>{{ __('Products') }}</h3>
                    <div class="icon">
                        <i class="fas fa-shopping-basket"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4 col-2xl-3 mb-3">
                <div class="dashboard-summery bg-plum-plate">
                    <h2>{{ $store->services->count() }}</h2>
                    <h3>{{ __('Services') }}</h3>
                    <div class="icon">
                        <i class="fas fa-user-cog"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4 col-2xl-3 mb-3">
                <div class="dashboard-summery bg-gradient-teal">
                    <h2>{{ currencyPosition($wallet->amount) }}</h2>
                    <h3>{{ __('Wallet') }}</h3>
                    <div class="icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-2xl-8">
                <div class="card border-0 shadow-sm rounded-12 details position-relative overflow-hidden">
                    <div class="card-body shop details">
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
                        <div class="d-flex flex-column px-3 mt-2">
                            <div class="item">
                                <strong>{{ __('Delivery_charge') }}</strong>
                                <span>{{ currencyPosition($store->delivery_charge) }}</span>
                            </div>
                            <div class="item">
                                <strong>{{ __('Minimum_order_amount') }}</strong>
                                <span>{{ currencyPosition($store->min_order_amount) }}</span>
                            </div>
                            <div class="item">
                                <Strong>{{ __('Agreement_commission') }}</Strong>
                                <div class="d-flex gap-2 align-items-center">
                                    <span>{{ $store->commission }}%</span>
                                    <button class="btn btn-primary btn-sm" data-toggle="modal"
                                        data-target="#commissionModal">
                                        <i class="fa fa-pencil-alt"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="item gap-2">
                                <Strong>{{ __('Description') }}:</Strong>
                                {{ $store->description }}
                            </div>
                        </div>
                    </div>
                    <div class="overlay">
                        <a class="icons" href="{{ route('shop.edit', $store->id) }}">
                            <i class="fa fa-edit"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-2xl-4 mt-3 mt-2xl-0">
                <div class="card h-100 d-flex flex-column rounded-12 border-0 shadow-sm">
                    <div class="card-header py-2">
                        <h2 class="card-title m-0">{{ __('Shop_Owner') }} {{ __('Details') }}</h2>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>{{ __('First_Name') }}</th>
                                <td>{{ $store->user->first_name }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Last_Name') }}</th>
                                <td>{{ $store->user->last_name ?? '--' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Mobile') }}</th>
                                <td>{{ $store->user->mobile }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Email') }}</th>
                                <td>{{ $store->user->email }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Gender') }}</th>
                                <td>{{ $store->user->gender ?? '--' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Profile') }}</th>
                                <td>
                                    <img src="{{ $store->user->profile_photo_path }}" alt="" width="80">
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 my-3">
                <div class="card rounded-12 border-0 shadow-sm">
                    <div class="card-header d-flex justify-content-between py-2">
                        <h3 class="card-title m-0">{{ __('Latest_Orders') }}</h3>
                        <a class="text-primary" href="{{ route('shop.order', $store->id) }}">
                            {{ __('View_All') }}
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>{{ __('Order_ID') }}</th>
                                        <th>{{ __('Delivery_Date') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>{{ $order->prefix . $order->order_code }}</td>
                                            <td>{{ $order->delivery_date }}</td>
                                            <td>{{ $order->order_status }}</td>
                                            <td>{{ currencyPosition($order->payable_amount) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 my-3">
                <div class="card rounded-12 border-0 shadow-sm">
                    <div class="card-header d-flex justify-content-between py-2">
                        <h3 class="card-title m-0">{{ __('Latest_transaction') }}</h3>
                        <a class="text-primary"
                            href="{{ route('shop.transaction', $store->user->wallet->id) }}">{{ __('View_All') }}</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-secondary">
                                    <tr>
                                        <th style="min-width: 120px">{{ __('Transition_Type') }}</th>
                                        <th style="min-width: 120px">{{ __('Date') }}</th>
                                        <th>{{ __('Purpose') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $key => $transition)
                                        <tr>
                                            <td>{{ $transition->transition_type }}</td>
                                            <td>{{ $transition->created_at->format('d M, Y') }}</td>
                                            <td>{{ $transition->purpose }}</td>
                                            <td>{{ currencyPosition($transition->amount) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- commission update modal --}}
        <form action="{{ route('shop.commissionUpdate', $store->id) }}" method="POST">
            @csrf
            <div class="modal fade" id="commissionModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('Commission_Update') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <label for="">{{ __('Commission') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">%</span>
                                </div>
                                <input type="text" name="commission" class="form-control" placeholder="Commission.."
                                    value="{{ $store->commission }}" required onkeypress="onlyNumber(event)">
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
    </div>
@endsection
