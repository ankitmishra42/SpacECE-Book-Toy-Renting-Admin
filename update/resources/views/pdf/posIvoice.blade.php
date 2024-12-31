<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('web/logos/fav-icon.png') }}">
    <title>Order Invoice</title>
    <meta name="description" content="">
    <link rel="stylesheet" href="{{ asset('web/css/all.min.css') }}" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">

    <style type="text/css">
        * {
            font-size: 14px;
            line-height: 24px;
            font-family: 'Ubuntu', sans-serif;
        }

        .btn {
            padding: 7px 10px;
            text-decoration: none;
            border: none;
            display: block;
            text-align: center;
            margin: 7px;
            cursor: pointer;
        }

        .btn-info {
            background-color: #999;
            color: #FFF;
        }

        .btn-primary {
            background-color: #6449e7;
            color: #FFF;
            width: 100%;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        td,
        th,
        tr,
        table {
            border-collapse: collapse;
        }

        td,
        th {
            padding: 7px 0;
        }

        table {
            width: 100%;
        }

        .centered {
            text-align: center;
            align-content: center;
        }

        small {
            font-size: 11px;
        }

        @media print {
            * {
                font-size: 12px;
                line-height: 20px;
            }

            td,
            th {
                padding: 5px 0;
            }

            .hidden-print {
                display: none !important;
            }

            @page {
                margin: 1.5cm 0.5cm 0.5cm;
            }

            @page: first {
                margin-top: 0.5cm;
            }

            tbody::after {
                content: '';
                display: block;
                page-break-after: avoid;
                page-break-inside: avoid;
                page-break-before: avoid;
            }
        }

        .invoiceNo {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .mb-0 {
            margin-bottom: 0px;
        }

        .line-height-14 {
            line-height: 14px !important;
        }

        .mt-0 {
            margin-top: 0 !important;
        }

        .border-top {
            border-top: 1px dotted #000;
        }

        .text-capitalize {
            text-transform: capitalize;
        }

        #receipt-data {
            background: rgb(255, 255, 255);
        }
    </style>
</head>

<body>

    <div style="max-width:400px;margin:0 auto">
        <div class="hidden-print">
            <table>
                <tr>
                    <td>
                        <a href="{{ route('order.show', $order->id) }}" class="btn btn-info"><i
                                class="fa fa-arrow-left"></i> Back To Order</a>
                    </td>
                    <td>
                        <button onclick="window.print();" class="btn btn-primary">
                            <i class="dripicons-print"></i> Print Now
                        </button>
                    </td>
                </tr>
            </table>
            <br>
        </div>

        <div id="receipt-data">
            <div class="centered">
                <img src="{{ $appSetting->websiteLogoPath ?? asset('web/logo.jpeg') }}" height="52"
                    style="margin-top: -10px; margin-bottom: 0px;">
                <div style="margin-top: 0;">
                    <p class="mb-0 line-height-14">{{ $store?->address?->address_name ?? $appSetting?->address }}</p>
                    <p class="mb-0 line-height-14">Tel: {{ $store->user?->mobile ?? $appSetting?->mobile }}</p>
                    <p class="mb-0 line-height-14">Customer: {{ $order->customer?->name }}</p>
                </div>

                <div class="invoiceNo">
                    <h3 style="font-size: 22px">Invoice No :</h3>
                    <span style="font-size: 22px">{{ $order->order_code }}</span>
                </div>

                <p class="mb-0 line-height-14 mt-0">{{ $order->created_at->format('m/d/Y') }}</p>
                <p class="mb-0 line-height-14">{{ $order->created_at->format('h:i A') }}</p>
            </div>

            @php
                $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
                $orderCode = $order->prefix . $order->order_code;
            @endphp
            <div class="centered" style="margin-top: 16px">
                <img src="data:image/png;base64, {{ base64_encode($generator->getBarcode("$orderCode", $generator::TYPE_CODE_128)) }}"
                    width="100%">
                <span class="mt-0 line-height-14">{{ $orderCode }}</span>
            </div>

            <table class="table-data border-top" style="margin-top: 20px">
                <thead>
                    <tr>
                        <th class="text-left">Item</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->products as $product)
                        <tr>
                            <td class="text-left">
                                {{ $product->name }}
                            </td>
                            <td class="text-center">
                                {{ $product->pivot->quantity }}
                            </td>
                            <td class="text-right">
                                {{ currencyPosition($product->price) }}
                            </td>
                        </tr>
                    @endforeach

                    <tr class="border-top">
                        <td colspan="2">
                            <h5 style="margin: 0" class="text-capitalize">Total</h5>
                        </td>
                        <td class="text-right">
                            <strong>{{ currencyPosition($order->total_amount) }}</strong>
                        </td>
                    </tr>

                    @if ($order->discount)
                        <tr>
                            <td colspan="2">
                                <h5 style="margin: 0" class="text-capitalize">Coupon disount</h5>
                            </td>
                            <td class="text-right">
                                <strong>-{{ currencyPosition(round($order->discount, 2)) }}</strong>
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="2">
                            <h5 style="margin: 0" class="text-capitalize">Delivery charge</h5>
                        </td>
                        <td class="text-right">
                            <strong>{{ currencyPosition($order->delivery_charge) }}</strong>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <h4 style="margin: 0" class="text-capitalize">Total payable</h4>
                        </td>
                        <td class="text-right">
                            <strong>{{ currencyPosition(round($order->payable_amount, 1)) }}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="centered">
                <h4>Thank you for your business !</h4>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        localStorage.clear();

        function auto_print() {
            window.print()
        }
        setTimeout(auto_print, 1000);
    </script>

</body>

</html>
