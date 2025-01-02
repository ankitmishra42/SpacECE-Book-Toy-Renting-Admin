
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        .text-default {
            color: #F8A800;
        }

        .bg-default {
            background: #F8A800;
            color: #fff;
        }

        @font-face {
            font-family: 'Bangla';
            src: url('/fonts/NotoSansBengali-Regular.ttf') format('truetype');
        }

        body {
            margin: 0;
            position: relative;
            font-family: "Bangla", sans-serif;
            font-size: 14px;
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

        td,
        th {
            padding: 0.5rem;
            text-align: left;
            font-size: 14px;
            border: 1px solid #cbd5e1;
        }

        footer {
            position: absolute;
            top: 20%;
            left: 10px;
            transform: rotate(90deg);
            transform-origin: 3% 0% 0;
            width: 100%;
            height: 40px;
        }

        .logo-sectioon {
            background: #475569;
            width: 280px;
            height: 85px;
            border-bottom-right-radius: 25px;
            position: relative;
            text-align: left;
            overflow: hidden;
        }

        .logo-sectioon .logo {
            position: absolute;
            top: 50%;
            left: 50%;
            right: 0;
            transform: translate(-50%, -50%);
            height: 60px;
            text-align: left;
        }

        .logo img {
            height: 100%;
        }

        .header {
            width: 100%;
            height: 28px;
            position: relative;
            margin-top: 25px;
        }

        .header .content {
            position: absolute;
            top: 0;
            right: 10%;
            background: #fff;
            padding: 0 12px;
            text-align: center
        }

        .header .content .invoice {
            font-size: 36px;
            line-height: 34px;
            text-transform: uppercase;
            font-weight: bold;
            color: #3A3A3A;
        }

        .header .content .invoiceId {
            line-height: 16px;
            color: #373737
        }

        h1,
        h2 {
            font-weight: bold;
            margin: 0;
        }

        .float-left {
            float: left;
        }

        h4 {
            font-weight: normal !important;
            font-size: 14px;
            margin: 5px 0
        }

        .col-3 {
            width: 33.3333333333%;
        }

        .w-70 {
            width: 70%
        }

        .w-30 {
            width: 20%
        }

        .footer {
            width: 100%;
            position: absolute;
            bottom: 0;
        }

        .signature {
            height: 60px;
        }

        .signature img {
            height: 100%;
        }

        .date-section {
            position: relative;
            margin-bottom: 10px;
            margin-top: 30px;
            height: 20px;
        }

        .date {
            position: absolute;
            right: 0;
        }

        .text-center {
            text-align: center;
        }

        .payInfo {
            font-size: 18px;
            margin-bottom: 16px;
        }

        .font-18 {
            font-size: 18px;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        .footerColor {
            height: 20px;
            background: #475569;
            overflow: hidden;
        }

        .footerColor::after {
            content: "";
            position: absolute;
            right: -15%;
            width: 300px;
            height: 150px;
            background: #fff;
            transform: rotate(45deg);
        }
    </style>
    <title>#{{ $order->prefix . $order->order_code }} - invioce</title>
</head>

<body>
    <div class="logo-sectioon">
        <div class="logo">
            @if (app()->environment('local'))
            <img class="white-filter" src="./images/logo-white.png" alt="logo">
            @else
                <img class="white-filter" src="{{ $store?->logoPath ?? './images/logo-white.png' }}" alt="logo">
            @endif
        </div>
    </div>

    <div class="header bg-default">
        <div class="content">
            <div class="invoice">Invoice</div>
            <div class="invoiceId">
                NO: INV-{{ $order->order_code }}
            </div>
        </div>
    </div>

    <div class="container">

        <h1 class="bill">Bill To:</h1>

        <h3 class="" style="margin: 6px 0">{{ $order->customer?->user?->name }}</h3>
        <p style="margin: 0">{{ $order->customer?->user?->mobile }}</p>
        @if ($order->addres)
        <h4>
            Road No: {{ $order->address?->road_no }},
            House No: {{ $order->address?->house_no }},
            Address Line: {{ $order->address?->house_no }}
        </h4>
        @endif

        <div class="date-section">
            <div class="date">Date: {{ $order->created_at->format('d F, Y') }}</div>
        </div>

        <table class="table">
            <thead>
                <tr class="bg-default">
                    <th class="text-center" style="width: 40px">No</th>
                    <th>Product Name</th>
                    <th class="text-center">Qty</th>
                    <th class="text-center">Price</th>
                    <th class="text-center" style="min-width:100px">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->products as $key => $product)
                    <tr>
                        <td class="text-center">{{ ++$key }}</td>
                        <td>{{ $product->name }}</td>
                        <td class="text-center">{{ $product->pivot->quantity }}</td>
                        <td class="text-center">
                            {{ currencyPosition($product->discount_price ?? $product->price) }}
                        </td>
                        <td class="text-center">
                            {{currencyPosition($product->pivot->quantity * ($product->discount_price ? $product->discount_price : $product->price)) }}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" style="border: none;"></td>
                    <td class="text-center">Delivery Charge</td>
                    <td class="text-center">{{ currencyPosition($order->delivery_charge) }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="border: none;"></td>
                    <td class="text-center">Sub Total</td>
                    <td class="text-center">{{ currencyPosition($order->total_amount) }}</td>
                </tr>

                @if ($order->discount)
                    <tr>
                        <td colspan="3" style="border: none;"></td>
                        <td class="text-center">Coupon Discount</td>
                        <td class="text-center" style="color:red;">
                            -{{ currencyPosition(round($order->discount, 2)) }}
                        </td>
                    </tr>
                @endif
                <tr>
                    <td colspan="3" style="border: none;"></td>
                    <td class="text-center bg-default">
                        <strong>Total Payable</strong>
                    </td>
                    <td class="text-center bg-default">
                        <strong>{{ currencyPosition($order->payable_amount) }}</strong>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <div style="padding: 0 25px">
            <div class="clearfix">
                <div class="w-70 float-left">
                    <div class="payInfo">Payment Information:</div>
                    <div class="font-18" style="height: 20px;">
                        <strong style="margin-right: 8px">Pay_Type:</strong>
                        <span class="">{{ $order->payment_type }}</span>
                    </div>
                    <div class="font-18" style="padding-top: 10px;">
                        <strong style="margin-right: 43px;">Email:</strong>
                        <span class="">{{ $store->user?->email ?? 'N/A' }}</span>
                    </div>

                </div>
                <div class="w-30 float-left text-center">
                    <div class="signature">
                        @if (app()->environment('local'))
                            <img src="./web/signature.png">
                        @else
                            <img src="{{ $appSetting?->signature_path }}">
                        @endif
                    </div>
                    <h2 class="text-center" style="margin: 0; line-height: 20px">{{ $store?->user?->name }}</h2>
                    <p class="text-center" style="margin: 0; line-height: 20px">{{ $store?->name }}</p>
                </div>
            </div>
            <div class="clearfix" style="padding: 12px 0; margin-top: 6px;">
                <div class="col-3 float-left" style="position: relative">
                    <img src="./images/icons/call.png" width="15"
                        style="display: inline-block; margin-top: 8px; margin-right: 6px;">
                    <span style="display: inline-block">
                        {{ $appSetting->mobile ?? 'N/A' }}
                    </span>
                </div>
                <div class="col-3 float-left">
                    <img src="./images/icons/email.png" width="15"
                        style="display: inline-block; margin-top: 8px;margin-right: 6px;">
                    <span style="display: inline-block">
                        {{ $store->user?->email ?? 'N/A' }}
                    </span>
                </div>
                <div class="col-3 float-left">
                    <img src="./images/icons/location.png" width="15"
                        style="display: inline-block; margin-top: 0px;margin-right: 4px;">
                    <span style="display: inline-block; font-size: 14px; width: 224px; height: 16px;">
                        {{ $store?->address?->address_name ?? 'N/A' }}
                    </span>
                </div>
            </div>
        </div>
        <div class="footerColor"></div>
    </div>
</body>

</html>
