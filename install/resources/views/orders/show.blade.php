@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="card-header shadow rounded-12 py-3 d-flex justify-content-between align-items-center flex-wrap" style="gap: 12px">
                    <h3 class="card-title m-0">{{ __('Order_details_of') }} {{ $order->customer?->user?->name ?? 'No Customer' }}</h3>

                    <div class="">
                        <a class="btn btn-light" href="{{ url()->previous() }}"> {{ __('Back') }} </a>
                        @can('order.print.invioce')
                            <a class="btn btn-danger" href="{{ route('order.print.invioce', $order->id) }}" target="_blank"><i class="fas fa-print"></i> Print </a>
                        @endcan
                        @if ($order->invoiceDownload)
                            <a class="btn btn-warning" href="{{ $order->invoiceDownload }}" download="">
                                <i class="fas fa-download"></i> {{ __('Download_Invoice') }}
                            </a>
                        @endif

                        @role('store')
                            <div class="drop-down">
                                <a class="btn btn-primary" style="min-width:150px" href="#status" data-toggle="collapse"
                                    aria-expanded="false" role="button" aria-controls="navbar-examples">
                                    <span class="nav-link-text">{{ __($order->order_status->value) }}</span>
                                    <i class="fa fa-chevron-down"></i>
                                </a>

                                <div class="collapse drop-down-items mt-1" id="status">
                                    <ul class="nav nav-sm flex-column">
                                        @foreach ($orderStatus as  $order_status)
                                            <li class="nav-item">
                                                <a class="nav-link"
                                                    href="{{ route('order.status.change', ['order' => $order->id, 'status' => $order_status->value]) }}">
                                                    {{ __($order_status->value) }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endrole
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mt-3">
                <div class="card shadow border-0 rounded-12 d-flex flex-column h-100">
                    <div class="card-header py-2">
                        <h2 class="m-0">{{ __('Order_Details') }}</h2>
                    </div>
                    <div class="card-body pt-2">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th class="py-2">{{ __('Order_Status') }}</th>
                                    <td class="py-2">{{ $order->order_status }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Assign_driver') }}</th>
                                    <td>
                                        {{-- @if ($order->drivers->isNotEmpty())
                                            <li>
                                                <strong> {{ $order->drivers[0]->user->name }} </strong>
                                            </li>
                                        @endif --}}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="py-2">{{ __('Payment_status') }}</th>
                                    <td class="py-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            {{ $order->payment_status }}
                                            @if ($order->payment_status != 'Paid')
                                                <a href="{{ route('orderIncomplete.paid', $order->id) }}"
                                                    class="btn btn-primary py-2 px-4 delete-confirm">
                                                    {{ __('Paid') }}
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="py-2">{{ __('Discount') }}</th>
                                    <td class="py-2">{{ currencyPosition($order->discount) }}</td>
                                </tr>
                                <tr>
                                    <th class="py-2">{{ __('Total_Amount') }}</th>
                                    <td class="py-2">{{ currencyPosition($order->total_amount) }}</td>
                                </tr>
                                <tr>
                                    <th class="py-2">{{ __('Delivery_charge') }}</th>
                                    <td class="py-2">{{ currencyPosition($order->delivery_charge) }}</td>
                                </tr>
                                <tr>
                                    <th class="py-2">{{ __('Total_Quantity') }}</th>
                                    <td class="py-2">{{ $quantity }} {{ __('Pieces') }}</td>
                                </tr>
                                <tr>
                                    <th class="py-2">{{ __('Items') }}</th>
                                    <td class="py-2">{{ $order->products->count() }}</td>
                                </tr>
                                <tr>
                                    <th class="py-2">{{ __('Pickup_Date') }}</th>
                                    <td class="py-2">
                                        {{ parse($order->pick_date, 'F d, Y') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="py-2">{{ __('Delivery_Date') }}</th>
                                    <td class="py-2">
                                        {{ parse($order->delivery_date, 'F d, Y') }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mt-3">
                <div class="card shadow border-0 rounded-12 d-flex flex-column h-100">
                    <div class="card-header py-2">
                        <h2 class="m-0">{{ __('') }}</h2>
                    </div>
                    <div class="card-body pt-2">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <td>{{ $order->customer?->user?->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('Customer_Photo') }}</th>
                                    <td>
                                        <img style="max-width: 80px" src="{{ $order->customer?->profilePhotoPath ?? '-' }}"
                                            alt="">
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('Email') }}</th>
                                    <td>{{ $order->customer?->user?->email ?? '-' }}</td>
                                        @if ($order->customer?->user?->email_verified_at)
                                            <span class="badge bg-success text-dark">{{ __('verified') }}</span>
                                        @else
                                            <span class="badge bg-danger text-white">
                                                {{ __('Unverified') }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('Phone_number') }}</th>
                                    <td>
                                        @if ($order->customer?->user?->mobile)
                                            {{ $order->customer?->user?->mobile ?? '-' }}
                                            @if ($order->customer?->user?->mobile_verified_at)
                                                <span class="badge bg-success text-dark">{{ __('verified') }}</span>
                                            @else
                                                <span class="badge bg-danger text-white">
                                                    {{ __('Unverified') }}
                                                </span>
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 my-3">
                <div class="card shadow border-0 rounded-12">
                    <div class="card-header py-2">
                        <h2 class="m-0">{{ __('Others_Details') }}</h2>
                    </div>
                    <div class="card-body pt-2">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th class="py-2">{{ __('Address') }}</th>
                                    <td class="py-2">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>{{ __('Area') }}</th>
                                                <th>{{ __('Address_Name') }}</th>
                                                <th>{{ __('Flat_No') }}</th>
                                                <th>{{ __('House_No') }}</th>
                                                <th>{{ __('Address_line') }}</th>
                                                <th>{{ __('Road_No') }}</th>
                                            </tr>
                                            <tr>
                                                <td><strong>{{ $order->address?->area ?? '-' }}</strong></td>
                                                <td><strong>{{ $order->address?->address_name ?? '-' }}</strong></td>
                                                <td><strong>{{ $order->address?->flat_no ?? '-' }}</strong></td>
                                                <td><strong>{{ $order->address?->house_no ?? '-' }}</strong></td>
                                                <td><strong>{{ $order->address?->address_line ?? '-' }}</strong></td>
                                                <td><strong>{{ $order->address?->road_no ?? '-' }}</strong></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="py-2">{{ __('Products') }}</th>
                                    <td class="py-2">
                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#staticBackdrop">
                                            {{ __('Show_all_order_products') }}
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="staticBackdrop">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel">
                                                            {{ __('All_order_products') }}
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @foreach ($order->products as $product)
                                                            <div class="bg-white my-2 py-2 overflow-hidden">
                                                                <img width="120" class="float-left mr-4"
                                                                    src="{{ $product->thumbnailPath }}" alt="">
                                                                <div class="overflow-hidden">
                                                                    <h4>{{ $product->name }}</h4>
                                                                    <p class="m-0">{{ __('Price') }}:
                                                                        {{ $product->discount_price ?? $product->price }}
                                                                    </p>
                                                                    <p>{{ __('Quantity') }}: {{ $product->pivot->quantity }}</p>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-dark"
                                                            data-dismiss="modal">{{ __('Close') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <th class="py-2">{{ __('Labels') }}</th>
                                    <td class="py-2">
                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#labals">
                                            {{ __('Order_Labels') }}
                                        </button>

                                        @can('order.print.labels')
                                            <a href="{{ route('order.print.labels', ['order' => $order->id, 'quantity' => $quantity]) }}"
                                                target="_blank" class="btn btn-danger">
                                                {{ __('Print') }} <i class="fas fa-print"></i>
                                            </a>
                                        @endcan

                                        <!-- Modal -->
                                        <div class="modal fade" id="labals">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content" style="background: #f6f6f6;">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">
                                                            {{ __('All_order_labels') }}
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            @php
                                                                $r = 1;
                                                            @endphp
                                                            @foreach ($order->products as $key => $product)
                                                                @for ($i = 0; $i < $product->pivot->quantity; $i++)
                                                                    <div class="col-4">
                                                                        <div
                                                                            class="card text-dark bg-white shadow bg-body rounded my-2 p-2">
                                                                            <h4 class="m-0">{{ __('Name') }}:
                                                                                {{ $order->customer?->user?->name }}</h4>
                                                                            <h4 class="m-0">{{ __('Order_ID') }}:
                                                                                #{{ $order->prefix . $order->order_code }}
                                                                            </h4>
                                                                            <h4 class="m-0">{{ __('Date') }}:
                                                                                {{ Carbon\Carbon::parse($order->delivery_at)->format('M d, Y') }}
                                                                            </h4>
                                                                            <h4 class="m-0">{{ __('Title') }}: {{ $product->name }}
                                                                            </h4>
                                                                            <h4 class="m-0">{{ __('Item') }}:
                                                                                {{ $r . '/' . $quantity }}</h4>
                                                                        </div>
                                                                    </div>
                                                                    @php
                                                                        $r++;
                                                                    @endphp
                                                                @endfor
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-dark"
                                                            data-dismiss="modal">{{ __('Close') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <th class="py-2">{{ __('Additional_Instruction') }}:</th>
                                    <td class="py-2">{{ $order->instruction ?? 'N\A' }}</td>
                                </tr>

                                <tr>
                                    <th class="py-2">{{ __('Additional_Service') }}</th>
                                    <td class="py-2">
                                        <button type="button" data-target="#additional" data-toggle="modal"
                                            class="btn btn-primary">
                                            {{ __('Additional_Service') }} <span
                                                class="badge badge-dark m-0">{{ $order->additionals->count() }}</span>
                                        </button>
                                        <!-- Modal -->
                                        <div class="modal fade" id="additional">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content" style="background: #f6f6f6;">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel">{{ __('All_order_labels') }}
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table
                                                            class="table table-bordered table-striped verticle-middle table-responsive-sm">
                                                            <tr>
                                                                <th>{{ __('Title') }}</th>
                                                                <th>{{ __('Description') }}</th>
                                                                <th>{{ __('Price') }}</th>
                                                            </tr>
                                                            @foreach ($order->additionals as $additional)
                                                                <tr>
                                                                    <td>{{ $additional->title }}</td>
                                                                    <td>{{ $additional->description }}</td>
                                                                    <td>{{ $additional->price }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-dark"
                                                            data-dismiss="modal">{{ __('Close') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <th class="py-2"> {{ __('Rating') }}</th>
                                    <td class="py-2">
                                        @php
                                            $rate = $order->rating ? $order->rating->rating : 0;
                                        @endphp
                                        <i class="fas fa-star {{ $rate >= 1 ? 'rate' : 'unrate' }}"></i>
                                        <i class="fas fa-star {{ $rate >= 2 ? 'rate' : 'unrate' }}"></i>
                                        <i class="fas fa-star {{ $rate >= 3 ? 'rate' : 'unrate' }}"></i>
                                        <i class="fas fa-star {{ $rate >= 4 ? 'rate' : 'unrate' }}"></i>
                                        <i class="fas fa-star {{ $rate == 5 ? 'rate' : 'unrate' }}"></i>

                                        <br>
                                        {{ $order->rating ? $order->rating->content : null }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="modal fade" id="assinDriver">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Select Driver</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <td>Name</td>
                                    <td>Email</td>
                                    <td>Contact</td>
                                    <td>Action</td>
                                </tr>
                                @foreach ($drivers as $driver)
                                    @php
                                        $pickup = 0;
                                        $delivery = 0;
                                        foreach ($driver->orders as $driverOrder) {
                                            if ($driverOrder->pick_date == $order->pick_date && $driverOrder->getTime($driverOrder->pick_hour) == $order->getTime($order->pick_hour)) {
                                                $pickup += 1;
                                            }
                                            if ($driverOrder->delivery_date == $order->delivery_date && $driverOrder->getTime($driverOrder->delivery_hour) == $order->getTime($order->delivery_hour)) {
                                                $delivery += 1;
                                            }
                                        }
                                    @endphp
                                    @if ($pickup < 4 || $delivery < 4)
                                        <tr>
                                            <td class="py-2">{{ $driver->user->name }}</td>
                                            <td class="py-2">{{ $driver->user->email }}</td>
                                            <td class="py-2">{{ $driver->user->mobile }}</td>
                                            <td class="py-2">
                                                <a href="{{ route('driver.assign', [$order->id, $driver->id]) }}"
                                                    class="btn btn-primary">Assign</a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach

                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div> --}}

        <style>
            .rate {
                color: rgb(255, 166, 0)
            }

            .unrate {
                color: rgb(136, 136, 136)
            }
        </style>
    </div>
@endsection

@push('scripts')
    <script>
        //delete confirm sweet alert
        $('.delete-confirm').on('click', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#546bf7',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Paid it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            })
        });
    </script>
@endpush
