@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h2 class="m-0"> Drivers Details</h2>

                    <a class="btn btn-light" href="{{ url()->previous() }}"> Back </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered table-striped verticle-middle table-responsive-sm">
                            <tbody>
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $driver->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Driver photo</th>
                                    <td>
                                        <img style="max-width: 100px" src="{{ $driver->user->profile_photo_path }}" alt="">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Join Date</th>
                                    <td><strong>{{ $driver->user->created_at->format('d F, Y') }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <label class="switch">
                                            <a href="{{ route('driver.status.toggle', $driver->id) }}">
                                                <input {{ $driver->is_approve ? 'checked':'' }} type="checkbox">
                                                <span class="slider round"></span>
                                            </a>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $driver->user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Phone number</th>
                                    <td>
                                        @if ($driver->user->mobile)
                                            {{ $driver->user->mobile }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                {{-- <tr>
                                    <th>Passport/Driving Lience</th>
                                    <td>
                                        @if ($driver->user->driving_lience)
                                            {{ $driver->user->driving_lience }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr> --}}
                                <tr>
                                    <th>Date of birth</th>
                                    <td>
                                        @if ($driver->user->date_of_birth)
                                            {{ Carbon\Carbon::parse($driver->user->date_of_birth)->format('d F, Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped verticle-middle table-responsive-sm" id="myTable">
                            <thead>
                                <tr>
                                    <th class="px-2">ID</th>
                                    <th scope="col" class="px-2">Order Date</th>
                                    <th scope="col" class="px-2">Pickup Date</th>
                                    <th scope="col" class="px-2">Delivery Date</th>
                                    <th scope="col" class="px-2">Order Status</th>
                                    <th scope="col" class="px-2 text-center">Amount</th>
                                    <th scope="col" class="px-2 text-center">Assign</th>
                                    <th scope="col" class="px-2 text-center">Accepted</th>
                                    <th scope="col" class="px-2 text-center">Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($driver->orders as $order)
                                    <tr>
                                        <td class="px-2">
                                            {{ $order->prefix.$order->order_code }}
                                        </td>
                                        <td class="px-2">
                                            {{ $order->created_at->format('M d, Y') }}<br>
                                            <small>{{ $order->created_at->format('h:i a') }}</small>
                                        </td>
                                        <td class="px-2">
                                            <span style="font-size: 14px">
                                                {{ Carbon\Carbon::parse($order->pick_date)->format('M d, Y') }}<br>
                                            </span>
                                            <small>
                                                {{ $order->getTime(substr($order->pick_hour, 0, 2)) }}
                                            </small>
                                        </td>
                                        <td class="px-2">
                                            <span style="font-size: 14px">
                                                {{ Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') }}<br>
                                            </span>
                                            <small>
                                                {{ $order->getTime(substr($order->delivery_hour, 0, 2)) }}
                                            </small>
                                        </td>
                                        <td class="px-2">{{ $order->order_status }}</td>
                                        <td class="px-2 text-center">{{ $order->amount - $order->discount }}</td>
                                        <td class="px-2 text-center text-capitalize">{{ $order->pivot->status }}</td>
                                        <td class="px-2 text-center">
                                            @if ($order->pivot->is_accept)
                                                <span class="text-success">Accepted</span>
                                            @else
                                                <span class="text-danger">Pending</span>
                                            @endif
                                        </td>
                                        <td class="px-2 text-center">
                                            <a href="{{ route('order.show', $order->id) }}" class="btn btn-primary">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                                @foreach ($driver->orderHistories as $order)
                                    <tr>
                                        <td class="px-2">
                                            {{ $order->prefix.$order->order_code }}
                                        </td>
                                        <td class="px-2">
                                            {{ $order->created_at->format('M d, Y') }}<br>
                                            <small>{{ $order->created_at->format('h:i a') }}</small>
                                        </td>
                                        <td class="px-2">
                                            <span style="font-size: 14px">
                                                {{ Carbon\Carbon::parse($order->pick_date)->format('M d, Y') }}<br>
                                            </span>
                                            <small>
                                                {{ $order->getTime(substr($order->pick_hour, 0, 2)) }}
                                            </small>
                                        </td>
                                        <td class="px-2">
                                            <span style="font-size: 14px">
                                                {{ Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') }}<br>
                                            </span>
                                            <small>
                                                {{ $order->getTime(substr($order->delivery_hour, 0, 2)) }}
                                            </small>
                                        </td>
                                        <td class="px-2">{{ $order->order_status }}</td>
                                        <td class="px-2 text-center">{{ $order->amount - $order->discount }}</td>
                                        <td class="px-2 text-center text-capitalize">{{ $order->pivot->status }}</td>
                                        <td class="px-2 text-center">
                                            <span class="text-success">Accepted</span>
                                        </td>
                                        <td class="px-2 text-center">
                                            <a href="{{ route('order.show', $order->id) }}" class="btn btn-primary">
                                                <i class="fa fa-eye"></i>
                                            </a>
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
