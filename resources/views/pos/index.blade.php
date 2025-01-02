@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mb-3">
            <h3 class="mb-0">
                {{ __('Point Of Sale') }}
            </h3>
        </div>

        <form action="{{ route('pos.store') }}" method="POST">
            @csrf
            <div class="row mb-3">
                <div class="col-xl-8">

                    {{-- services --}}
                    <div class="card border-0 overflow-hidden">
                        <div class="card-header py-3">
                            <h3 class="m-0">{{ __('Select') . ' ' . __('Service') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="d-flex gap-3 flex-wrap">
                                @forelse ($services as $service)
                                    <div class="service-card"
                                        onclick="selectService({{ $service->id }})" id="service-{{ $service->id }}">
                                        <img src="{{ $service->thumbnailPath }}" alt="" width="100%"
                                            class="rounded">
                                        <h3 class="text-center mb-0">{{ $service->name }}</h3>
                                    </div>
                                @empty
                                    <h3 class="text-center">
                                        {{ __('No') . ' ' . __('Service') }}
                                    </h3>
                                @endforelse

                            </div>
                        </div>
                    </div>

                    {{-- products --}}

                    <div class="card mt-3 border-0 overflow-hidden" style="border-radius: 12px;">
                        <div class="card-header py-3">
                            <h3 class="m-0">{{ __('Select') . ' ' . __('Products') }}</h3>
                        </div>
                        <div class="card-body">

                            {{-- variants --}}
                            <div class="d-flex flex-wrap mb-3" id="varients" style="gap: 12px 0">

                            </div>

                            {{-- product list --}}
                            <div class="d-flex gap-3 flex-wrap" id="products">

                            </div>

                        </div>
                    </div>

                </div>
                <div class="col-xl-4 mt-2 mt-xl-0">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="m-0">{{ __('Order') . ' ' . __('Basket') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-3 border-bottom border-light pb-3 mb-3" style="border-top-right-radius: 0 !important;">
                                <div class="flex-grow-1">
                                    <div class="input-group d-flex">
                                        <span
                                            class="input-group-text border-start border bg-white ps-2 pe-1 ">
                                            <i class="fas fa-user-circle text-muted"></i>
                                        </span>
                                        <div class="flex-grow-1 customerSelect">
                                            <select name="customer_id" class="form-control select2" style="width: 100%;"
                                                data-placeholder="{{ __('Enter customer name or phone number') }}" id="customerId">
                                                <option selected value="">
                                                    {{ __('Select Customer') }}
                                                </option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}">
                                                        {{ $customer->user?->name . '-(' . $customer->user?->mobile . ')' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-grow-1 flex-lg-grow-0">
                                    <button type="button" class="btn btn-outline-primary w-100 py-2" data-toggle="modal"
                                        data-target="#customerModal">
                                        <i class="bi bi-plus-circle-fill me-2"></i>
                                        {{ __('Customer') }}
                                    </button>
                                </div>
                            </div>

                            <div class="d-flex flex-column gap-3" id="basketProducts">

                            </div>

                            <div class="border-top mt-3 d-flex flex-column">

                                <p
                                    class="mt-1 mb-0 border-bottom border-light py-2 d-flex justify-content-between font-weight-500">
                                    Total Amount: <span id="totalAmount"></span>
                                </p>
                                <input type="hidden" name="total_amount" id="totalAmountInput">

                                <p
                                    class="mb-0 border-bottom border-light py-2 d-flex justify-content-between font-weight-500">
                                    Discount: <span>{{ currencyPosition(0) }}</span>
                                </p>
                                <input type="hidden" name="discount" id="discountInput" value="0">

                                <p
                                    class="mb-0 border-bottom border-light py-2 d-flex justify-content-between font-weight-500">
                                    Delivery Charge: <span>{{ currencyPosition(0) }}</span>
                                </p>

                                <input type="hidden" name="delivery_charge" id="deliveryChargeInput" value="">

                                <h3 class="mb-0 border-bottom border-light py-2 d-flex justify-content-between">
                                    Grand Total: <span id="grandTotal"></span>
                                </h3>
                                <input type="hidden" name="grand_total" id="grandTotalInput">
                                <button type="submit" class="btn btn-primary px-5 py-3 mt-3">
                                    Confirm Order
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>

    <!-- customer create modal -->
    <form action="#" id="customerForm">
        <div class="modal fade" id="customerModal">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content p-4">
                    <div class="modal-header border-0">
                        <h1 class="modal-title fs-4" id="productModalLabel">
                            {{ __('Add New Customer') }}
                        </h1>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body py-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mt-3">
                                            <label class="form-label">First Name</label>
                                            <x-input label="First Name" name="first_name" type="text" placeholder="First Name" class="form-control" required="true" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mt-3">
                                            <label class="form-label">Last Name</label>
                                            <x-input label="Last Name" name="last_name" type="text" placeholder="Enter Name" />
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="form-label">Phone Number</label>
                                    <x-input label="Phone Number" name="phone" type="number"
                                        placeholder="Enter phone number" required="true" />
                                </div>

                                <div class="mt-3">
                                    <label class="form-label">Gender</label>
                                    <x-select label="Gender" name="gender">
                                        <option value="male" selected>{{ __('Male') }}</option>
                                        <option value="female">{{ __('Female') }}</option>
                                    </x-select>
                                </div>
                                <div class="mt-3">
                                    <label class="form-label">Email</label>
                                    <x-input type="email" name="email" label="Email" placeholder="Enter Email Address" />
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="is_active" value="1">
                    </div>
                    <div
                        class="modal-footer border-0 mt-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <button type="button" class="btn btn-danger py-3 flex-grow-1" data-dismiss="modal">
                            {{ __('Close') }}
                        </button>

                        <button type="submit" class="btn btn-primary py-3 flex-grow-1">
                            {{ __('Submit') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection
@push('scripts')
    <script>
        var currentServiceId = null;

        function selectService(serviceId) {
            $('.service-card').removeClass('active');
            $('#service-' + serviceId).addClass('active');
            currentServiceId = serviceId;
            fetchVariants(serviceId);
        }

        function fetchVariants(serviceId) {
            var variantId = null;
            $('#varients').empty();
            $.ajax({
                url: "{{ route('pos.fetch.variants') }}",
                type: 'GET',
                data: {
                    service_id: serviceId
                },
                success: function(response) {
                    // Clear existing variants
                    $('#varients').empty();

                    // Append new variants
                    response.data.variants.forEach(function(variant, index) {
                        var variantButton = $('<button>')
                            .addClass('btn border')
                            .text(variant.name)
                            .attr('onclick', 'selectVariant(' + variant.id + ')')
                            .attr('id', 'variant-' + variant.id)
                            .attr('type', 'button');

                        // Add the btn-primary class to the first variant
                        if (index === 0) {
                            variantButton.addClass('btn-primary');
                            variantId = variant.id;
                        }

                        $('#varients').append(variantButton);
                    });

                    if (variantId) {
                        fetchProducts(variantId);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching variants:', error);
                }
            });
        }

        function selectVariant(variantId) {
            $('#varients .btn').removeClass('btn-primary');
            $('#variant-' + variantId).addClass('btn-primary');

            fetchProducts(variantId);
        }

        function fetchProducts(varintId) {
            $.ajax({
                url: "{{ route('pos.fetch.products') }}",
                type: 'GET',
                data: {
                    service_id: currentServiceId,
                    variant_id: varintId
                },
                success: function(response) {
                    // Clear existing products
                    $('#products').empty();

                    // Append new products
                    response.data.products.forEach(function(product) {
                        var productCard = $('<div>')
                            .addClass('service-card')
                            .attr('onclick', 'addProductToBasket(' + JSON.stringify(product) + ')');

                        var productImage = $('<img>')
                            .attr('src', product.image_path)
                            .attr('alt', '')
                            .css('width', '100%')
                            .addClass('rounded');

                        var productTitle = $('<h3>')
                            .addClass('text-center mb-0')
                            .text(product.name);

                        productCard.append(productImage).append(productTitle);
                        $('#products').append(productCard);
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching products:', error);
                }
            });
        }

        let basket = [];

        function addProductToBasket(product) {
            // Check if product is already in basket
            let existingProduct = basket.find(p => p.id === product.id);

            if (existingProduct) {
                existingProduct.quantity += 1;
            } else {
                product.quantity = 1;
                basket.push(product);
            }

            renderBasket();
            updateTotalAmount();
        }


        function renderBasket() {
            $('#basketProducts').empty();

            basket.forEach(product => {
                let productDiv = $(`
                <input type="hidden" name="products[${product.id}][id]" value="${product.id}">
                <input type="hidden" name="products[${product.id}][quantity]" value="${product.quantity}">
                <div class="d-flex gap-3 align-items-center pt-2" style="border-top: 1px dashed #eee" data-product-id="${product.id}">
                    <div class="border rounded">
                        <img src="${product.image_path}" alt="" width="100" height="90" class="rounded object-fit-cover">
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="m-0 d-flex justify-content-between flex-wrap gap-3">
                            ${product.name}
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeProductFromBasket(${product.id})">
                                <i class="fa fa-trash"></i>
                            </button>
                        </h3>
                        <p class="m-0 font-weight-500">
                            ${currencyPosition(product.current_price)}
                        </p>
                        <div class="d-flex align-items-center gap-3">
                            <button class="btn btn-sm m-0" onclick="changeQuantity(${product.id}, -1)">
                                <i class="fa fa-minus"></i>
                            </button>
                            <input type="text" class="text-center border rounded" value="${product.quantity}" readonly style="width: 46px">
                            <button class="btn btn-sm m-0" onclick="changeQuantity(${product.id}, 1)">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `);
                $('#basketProducts').append(productDiv);
            });

            updateTotalAmount();
        }

        function changeQuantity(productId, change) {
            let product = basket.find(p => p.id === productId);

            if (product) {
                product.quantity += change;
                if (product.quantity <= 0) {
                    removeProductFromBasket(productId);
                } else {
                    renderBasket();
                }
            }
        }

        function removeProductFromBasket(productId) {
            basket = basket.filter(p => p.id !== productId);
            renderBasket();
        }

        function updateTotalAmount() {
            let totalAmount = basket.reduce((total, product) => {
                let price = product.current_price;
                return total + (price * product.quantity);
            }, 0);

            $('#totalAmount').text(currencyPosition(totalAmount.toFixed(2)));
            $('#totalAmountInput').val(totalAmount.toFixed(2));

            let discount = parseFloat($('#discountInput').val()) || 0;
            let deliveryCharge = parseFloat($('#deliveryChargeInput').val()) || 0;
            let grandTotal = totalAmount - discount + deliveryCharge;

            if (grandTotal < 0) {
                grandTotal = 0;
            }

            $('#grandTotal').text(currencyPosition(grandTotal.toFixed(2)));
            $('#grandTotalInput').val(grandTotal.toFixed(2));
        }

        function currencyPosition(amount) {
            // Assuming USD currency
            return '$' + amount;
        }

        function submitOrder() {
            // Implement order submission logic here
            console.log('Order submitted:', basket);
        }

        $('#customerForm').submit(function(e) {
            e.preventDefault();
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            $.ajax({
                url: "{{ route('pos.customerStore') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    first_name: $('input[name="first_name"]').val(),
                    last_name: $('input[name="last_name"]').val(),
                    mobile: $('input[name="phone"]').val(),
                    gender: $('select[name="gender"]').val(),
                    email: $('input[name="email"]').val(),
                    password: $('input[name="phone"]').val(),
                },
                success: (response) => {
                    $('#customerModal').modal('hide');
                    Toast.fire({
                        icon: 'success',
                        title: response.message
                    });

                    var customer = $('select[name="customer_id"]');

                    var user = response.data.user;

                    customer.append(
                        `<option value="${user.id}" selected>${user.name}-(${user.mobile})</option>`);
                    customer.val(user.id);

                    $('input[name="first_name"]').val('');
                    $('input[name="last_name"]').val('');
                    $('input[name="phone"]').val('');
                    $('select[name="gender"]').val('');
                    $('input[name="email"]').val('');
                },
                error: (error) => {
                    Toast.fire({
                        icon: 'error',
                        title: error.responseJSON.message
                    });
                    console.log(error.responseJSON.message);
                }
            });
        });
    </script>
@endpush
