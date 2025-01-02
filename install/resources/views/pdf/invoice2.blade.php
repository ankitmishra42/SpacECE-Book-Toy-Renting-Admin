<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        .text-default {
            color: #36b9fe;
        }

        .bg-default {
            background: #36b9fe;
            color: #fff;
        }

        @font-face {
            font-family: 'poppins';
            src: url('./fonts/Poppins-Regular.ttf') format('truetype');
        }

        @font-face {
            font-family: 'oswald';
            src: url('./fonts/Oswald.ttf') format('truetype');
        }

        @font-face {
            font-family: 'oswald2';
            font-style: normal;
            font-weight: normal;
            src: url('./fonts/Oswald_2.ttf') format('truetype');
        }

        body {
            margin: 0;
            position: relative;
            font-family: 'poppins', sans-serif;
            color: #2c2c2c;
            font-weight: normal;
        }

        .container {
            padding: 25px !important;
        }

        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        @page {
            size: A4;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table tbody tr td,
        th {
            padding: 0.4rem;
            text-align: left;
            font-size: 14px;
        }

        th {
            padding: 0.8rem 0.5rem;
            text-transform: uppercase;
        }

        table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tfoot {
            border-top: 3px solid #ddd;
        }

        .float-left {
            float: left;
        }

        .w-70 {
            width: 70%
        }

        .w-30 {
            width: 20%
        }

        .w-40 {
            width: 40%
        }

        .w-60 {
            width: 60%
        }

        .text-center {
            text-align: center;
        }

        .payInfo {
            font-size: 18px;
            margin-bottom: 16px;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        /* new style */
        .header-section {
            width: 100%;
        }

        .header-section .title-invoice {
            font-size: 58px;
            font-family: 'oswald2', sans-serif !important;
            font-weight: bolder;
            letter-spacing: 3px;
            padding-top: 42px;
        }

        .text-right {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .w-200 {
            width: 200px;
            display: inline-block;
            text-align: left;
        }

        .fz-13 {
            font-size: 13px;
        }

        .fz-14 {
            font-size: 14px;
        }

        .fz-18 {
            font-size: 18px;
        }

        .mt-1 {
            margin-top: 1px;
        }

        .m-0 {
            margin: 0px !important;
        }

        .header-section .info .title {
            font-weight: bold !important;
            color: #000;
            font-size: 16px;
        }

        .line-height-12 {
            line-height: 12px;
        }

        .line-height-14 {
            line-height: 14px;
        }

        .d-inline-block {
            display: inline-block !important;
            vertical-align: middle !important;
        }

        .customer-section {
            margin-top: 30px;
        }

        .customer-section .customer-name {
            font-size: 32px;
            margin: 0;
            margin-top: -15px;
            line-height: 1
        }

        .customer-section .address {
            margin: 0;
            line-height: 1.4;
            font-weight: bold;
            font-size: 14px;
        }

        .customer-section .phone,
        .customer-section .email {
            margin: 0 !important;
            line-height: 1.4;
            font-weight: bold;
            font-size: 14px;
        }

        .paymentBox {
            margin-top: 10px;
        }

        .paymentBox .pay {
            font-weight: bold;
            padding-bottom: 4px;
            letter-spacing: 3px;
            font-size: 17px;
        }

        .paymentBox .payInfo {
            vertical-align: top !important;
            margin-top: -20px;
        }

        .totalQTY {
            font-size: 12px;
            width: 100% !important;
            border-top: 3px solid #36b9fe !important;
            border-bottom: 3px solid #36b9fe !important;
        }

        .question {
            width: 100% !important;
            margin-top: 10px;
        }

        .question .questionBox {
            margin-top: 12px;
        }

        .question .thank {
            font-size: 46px;
            font-weight: bolder;
            margin: 0 !important;
            line-height: 46px !important;
        }

        .footerAddress {
            border-top: 3px solid #36b9fe;
            margin-top: 12px;
        }
    </style>
    <title>#{{ $order->prefix . $order->order_code }} - invioce</title>
</head>

<body>

    <div class="container">
        {{-- header section --}}
        <div class="header-section clearfix">
            <div class="float-left w-40">
                <div class="brand-logo">
                    @if (app()->environment('local'))
                        <img src="./web/logo.png" alt="logo" width="240" height="auto">
                    @else
                        <img src="{{ $store?->logoPath ?? './web/logo.png' }}" alt="logo" width="240" height="auto">
                    @endif
                </div>
                <div class="info">
                    <div class="title mt-1">{{ config('app.name') }} Services</div>
                    <p class="m-0 line-height-12 fz-13 mt-1">{{ $order->store?->name }}</p>
                    <p class="m-0 line-height-12 fz-13">
                        Road No: {{ $order->store?->address?->road_no }},
                        House No: {{ $order->store?->address?->house_no }},
                        Address Line: {{ $order->store?->address?->house_no }}
                    </p>
                    <p class="m-0 line-height-12 fz-13">
                        {{ $order->store?->user?->mobile }}
                    </p>
                    <p class="m-0 line-height-12 fz-13">
                        {{ $order->store?->user?->email }}
                    </p>
                </div>
            </div>
            <div class="w-60 float-left text-right">
                <div class="title-invoice text-default">TAX INVOICE</div>
                <div class="fw-bold">
                    <span class="text-default">DATE: </span>
                    <span class="w-200">{{ $order->created_at->format('F d, Y') }}</span>
                </div>
                <div class="fw-bold mt-1">
                    <span class="text-default">INVOICE NO: </span>
                    <span class="w-200">#{{ $order->prefix . $order->order_code }}</span>
                </div>
                <div class="fw-bold mt-1">
                    <span class="text-default">PICKUP DATE: </span>
                    <span class="w-200">
                        {{ Carbon\Carbon::parse($order->pick_date)->format('F d, Y') }}
                    </span>
                </div>
                <div class="fw-bold mt-1">
                    <span class="text-default">DELIVERY DATE: </span>
                    <span class="w-200">
                        {{ $order->delivery_date ? Carbon\Carbon::parse($order->delivery_date)->format('F d, Y') : 'N/A' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- customer section --}}
        <div class="customer-section clearfix">
            <div class="float-left w-40">
                <p class="customer-name">{{ $order->customer->user?->name }}</p>
                @if ($order->address)
                <p class="address">
                    Road No: {{ $order->address?->road_no }},
                    House No: {{ $order->address?->house_no }},
                    Address Line: {{ $order->address?->house_no }}
                </p>
                @endif
                @if ($order->customer?->user?->mobile)
                    <p class="address">{{ $order->customer->user?->mobile }}</p>
                @endif
                @if ($order->customer?->user?->email)
                    <p class="address">{{ $order->customer->user?->email }}</p>
                @endif
            </div>
            <div class="w-60 float-left paymentBox">
                <span class="d-inline-block" style="padding-top: 12px;width:120px">
                    {{-- PAY USING UPI : --}}
                </span>
                <div class="d-inline-block">
                    <p class="pay text-default">PAYMENT METHOD</p>
                    <div class="mt-1">
                        <img class="barcode" src="./images/scanner.png" alt="" width="65">
                        <div class="d-inline-block payInfo">
                            <p class="m-0 line-height-12 fz-13">{{ $order->payment_type }}</p>
                            <p class="m-0 line-height-12 fz-13">
                                Status: {{ $order->payment_status }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- table section --}}
        <table class="table">
            <thead>
                <tr class="bg-default">
                    <th class="text-center">Item</th>
                    <th class="text-center">Qty</th>
                    <th class="text-center">Price</th>
                    <th class="text-center" style="min-width:100px">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->products as $key => $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td class="text-center">{{ str_pad($product->pivot->quantity, 2, '0', STR_PAD_LEFT) }}</td>
                        <td class="text-center fw-bold ">
                            {{ currencyPosition($product->discount_price ?? $product->price) }}
                        </td>
                        <td class="text-center">
                            {{ currencyPosition($product->pivot->quantity * ($product->discount_price ? $product->discount_price : $product->price)) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2"></td>
                    <td class="text-right fz-14">Delivery Charge:</td>
                    <td class="text-center fz-14">
                        {{ currencyPosition(number_format($order->delivery_charge, 2)) }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td class="text-right fz-14">Subtotal:</td>
                    <td class="text-center fz-14">{{ currencyPosition(number_format($order->total_amount, 2)) }}</td>
                </tr>

                @if ($order->discount)
                    <tr>
                        <td colspan="2"></td>
                        <td class="text-right fz-14">Discount:</td>
                        <td class="text-center fz-14" style="color:red;">
                            -{{ currencyPosition(round($order->discount, 2)) }}
                        </td>
                    </tr>
                @endif
                <tr>
                    <td colspan="2"></td>
                    <td class="text-right">
                        <strong>GRAND TOTAL:</strong>
                    </td>
                    <td class="text-center">
                        <strong>{{ currencyPosition(number_format($order->payable_amount, 2)) }}</strong>
                    </td>
                </tr>
            </tfoot>
        </table>

        {{-- total qty section --}}
        @php
            use Rmunate\Utilities\SpellNumber;
            $spellNumber = SpellNumber::value(round($order->payable_amount))->toLetters();
        @endphp
        <div class="totalQTY clearfix w-100">
            <div class="float-left">Total Quantity: {{ $order->products->sum('pivot.quantity') }}</div>
            <div class="float-right">
                <p class="m-0 text-right">Total Amount(in word): {{ $spellNumber }} Only</p>
            </div>
        </div>

        {{-- footer section --}}
        <div class="question clearfix">
            <div class="float-left questionBox">
                <p class="m-0 line-height-14">Questions?</p>
                <p class="m-0 line-height-14">Email us at {{ $order->store?->user?->email }}</p>
                <p class="m-0 line-height-14 text-muted">or call us at {{ $order->store?->user?->mobile }}</p>
            </div>
            <div class="float-right">
                <div class="text-default">
                    <p class="text-right thank m-0">Thank</p>
                    <p class="text-right thank m-0">You!</p>
                </div>
            </div>
        </div>

        <div class="footerAddress">
            <p class="text-left m-0">
               {{ $appSetting?->address }}
            </p>
        </div>

    </div>
</body>

</html>
