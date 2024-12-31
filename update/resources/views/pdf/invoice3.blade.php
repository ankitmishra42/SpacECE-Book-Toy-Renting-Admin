<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        .bg-gray {
            background: gray;
            color: #fff;
        }

        .text-default {
            color: #39D8D8;
        }

        .bg-default {
            background: #39D8D8;
        }

        body {
            margin: 0;
            position: relative;
            font-family: "HelveticaNeue-CondensedBold", "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
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
            border: 1px solid rgba(0, 0, 0, 0.07);
        }

        img {
            max-width: 100%;
            max-height: 100%;
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

        h4 {
            margin: 5px 0
        }

        .header {
            width: 100%;
            height: 28px;
            position: relative;
            background-color: #39D8D8;
            margin-top: 25px;
        }

        .header .logo {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 25px;
            background: #fff;
            width: 120px;
            height: 40px;
            text-align: center;
        }

        .header .logo img {
            max-width: 98%;
        }

        h1,
        h2 {
            font-weight: bold;
            margin: 0;
        }

        .float-left {
            float: left;
        }

        .address_line {
            height: 80px;
            padding: 5px 0;
        }

        h4 {
            font-weight: normal !important;
            font-size: 14px;
        }

        .w-60 {
            width: 60%
        }

        .w-70 {
            width: 70%
        }

        .w-10 {
            width: 10%
        }

        .w-15 {
            width: 15%
        }

        .w-20 {
            width: 20%
        }

        .footer {
            width: 100%;
            position: absolute;
            bottom: 0;
        }

        .contact {
            position: absolute;
            right: 25%;
            top: 50px
        }

        .footer-bottom {
            border-top: 2px solid rgba(0, 0, 0, 0.07);
            position: relative;
            height: 40px;
        }

        .footer-bottom .message {
            position: absolute;
            left: 25px;
            padding: 10px 0;
        }

        .footer-bottom .author {
            position: absolute;
            top: -2px;
            right: 25px;
            border-top: 2px solid #000;
            padding: 10px 0;
        }

        .footer-bottom .signature {
            position: absolute;
            right: 32px;
            top: -50px;
        }

        .footer-bottom .signature img {
            height: 50px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
    <title>#{{ $order->prefix . $order->order_code }} - invioce</title>
</head>

<body>
    <div class="header">
        <div class="logo">
            @if (app()->environment('local'))
                <img src="./web/logo.png" alt="">
            @else
                <img src="{{ $store?->logoPath ?? './web/logo.png' }}" alt="">
            @endif
        </div>
    </div>

    <div class="container">
        <div style="overflow: hidden; height: 30px;">
            <div style="float: right; width: 30%;">
                <h1 class="text-default">Invoice</h1>
            </div>
        </div>

        <h4 style="font-weight: normal !important"> Customer: {{ $order->customer?->user?->name ?? 'N/A' }}</h4>
        <div class="address_line">
            <div class="w-70 float-left">
                <h4>Area: {{ $order->address?->area ?? 'N/A' }}</h4>
                <h4>Road No: {{ $order->address?->road_no ?? 'N/A' }}</h4>,
                    House No: {{ $order->address?->house_no ?? 'N/A' }},
                </h4>
                <h4>Phone | Fax: {{ $order->customer?->user?->mobile ?? 'N/A' }}</h4>
            </div>
            <div class="w-10 float-left">
                <h4 class="text-default">RECEIPT #</h4>
                <h4 class="text-default">DATE:</h4>
            </div>
            <div class="w-20 float-left" style="padding-left: 12px">
                <h4>#{{ $order->prefix . $order->order_code }}</h4>
                <h4>{{ $order->created_at->format('d F, Y') }}<h4>
            </div>
        </div>

        <div style="padding: 10px 0">
            <h4><strong>Pickup Date :</strong>
                <span class="badge text-dark">
                    {{ Carbon\Carbon::parse($order->pick_date)->format('F d, Y') }}
                </span>
            </h4>
            <h4><strong>Delivery Date :</strong>
                <span class="badge text-dark">
                  {{ $order->delivery_date ? Carbon\Carbon::parse($order->delivery_date)->format('F d, Y') : 'N/A' }}
                </span>
            </h4>
        </div>

        <table class="table">
            <thead>
                <tr class="bg-default">
                    <th>Product Name</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-center">Rate</th>
                    <th style="text-align: right;min-width:100px">Amount</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($order->products as $key => $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td class="text-center">{{ str_pad($product->pivot->quantity, 2, '0', STR_PAD_LEFT) }}</td>
                        <td class="text-center">
                            {{ currencyPosition($product->discount_price ?? $product->price) }}
                        </td>
                        <td class="text-right">
                            {{ currencyPosition($product->pivot->quantity * ($product->discount_price ? $product->discount_price : $product->price)) }}
                        </td>
                    </tr>
                @endforeach

                <tr>
                    <td style="border:none"><strong style="color: #000">
                            Total Items: {{ $order->products->sum('pivot.quantity') }} Pieces</strong>
                    </td>
                    <td colspan="2" style="text-align: right;border: 0;">
                        SUBTOTAL
                    </td>
                    <td style="text-align: right; background: rgba(0, 0, 0, 0.07);">
                        {{ currencyPosition($order->total_amount) }}
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;border: 0;">
                        DELIVERY CHARGE
                    </td>
                    <td style="text-align: right">{{ currencyPosition($order->delivery_charge) }}</td>
                </tr>
                @if ($order->discount)
                    <tr>
                        <td colspan="3" style="text-align: right;border: 0;">
                            COUPON DISCOUNT
                        </td>

                        <td style="text-align: right; color:red;">
                            -{{ currencyPosition($order->discount) }}
                        </td>
                    </tr>
                @endif
                <tr>
                    <td colspan="3" style="text-align: right;border: 0;">
                        <strong>TOTAL PAYABLE</strong>
                    </td>
                    <td style="color:#000;text-align: right; background: #ddd;">
                        <strong>{{ currencyPosition($order->payable_amount) }}</strong>
                    </td>
                </tr>
            </tbody>
        </table>

        <h4 style="margin-top: 20px">
            <strong>Delivery Note:</strong>
        </h4>
        <p style="font-weight: 700">{{ $order->instruction }}</p>
        </p>
    </div>
    <div class="footer">
        <div style="padding: 10px 25px">
            <div class="address">
                <strong>{{ $store->name ?? 'LaundryMart' }}</strong><br>
                Address: <strong>{{ $$appSetting?->address ?? 'N/A' }}</strong> <br>
                City: <strong>{{ $appSetting?->city ?? 'N/A' }}</strong> <br>
            </div>
            <div class="contact">
                Mobile: <strong>{{ $appSetting?->phone ?? 'N/A' }}</strong>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="message text-default">Thank you for your business</div>
            <div class="author">Authorised sign</div>
            <div class="signature">
                @if (app()->environment('local'))
                    <img src="./web/signature.png">
                @else
                    <img src="{{ $appSetting?->signature_path ?? './web/signature.png' }}">
                @endif
            </div>
        </div>
    </div>
</body>

</html>
