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
    <title>#000029 - invioce</title>
</head>

<body>
    <div class="header">
        <div class="logo">
            <img src="./web/logo.png" alt="">
        </div>
    </div>

    <div class="container">

        <div style="overflow: hidden; height: 30px;">
            <div style="float: right; width: 30%;">
                <h1 class="text-default">Invoice</h1>
            </div>
        </div>

        <h4 style="font-weight: normal !important"> Customer: Tasnim Araf</h4>
        <div class="address_line">
            <div class="w-70 float-left">
                <h4>Area: shekertek</h4>
                <h4>Road: 08, House: 19, Flat: A</h4>
                <h4>Phone | Fax: 01598765432</h4>
            </div>
            <div class="w-10 float-left">
                <h4 class="text-default">RECEIPT #</h4>
                <h4 class="text-default">DATE:</h4>
            </div>
            <div class="w-20 float-left" style="padding-left: 12px">
                <h4>#000029</h4>
                <h4>{{ date('F d, Y') }}</h4>
            </div>
        </div>

        <div style="padding: 10px 0">
            <h4><strong>Pickup Date :</strong>
                <span class="badge text-dark">
                    {{ date('M d, Y') }}
                </span>
            </h4>
            <h4><strong>Delivery Date :</strong>
                <span class="badge text-dark">
                    {{ date('M d, Y') }}
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
                {{-- product items --}}
                <tr>
                    <td>Jacket Wash</td>
                    <td class="text-center">02</td>
                    <td class="text-center">
                        {{ currencyPosition(30) }}
                    </td>
                    <td class="text-right">
                        {{ currencyPosition(60) }}
                    </td>
                </tr>

                <tr>
                    <td>Blazer</td>
                    <td class="text-center">01</td>
                    <td class="text-center">
                        {{ currencyPosition(210) }}
                    </td>
                    <td class="text-right">
                        {{ currencyPosition(210) }}
                    </td>
                </tr>

                <tr>
                    <td>T-shirt</td>
                    <td class="text-center">03</td>
                    <td class="text-center">
                        {{ currencyPosition(5) }}
                    </td>
                    <td class="text-right">
                        {{ currencyPosition(15) }}
                    </td>
                </tr>

                <tr>
                    <td>Shirts</td>
                    <td class="text-center">03</td>
                    <td class="text-center">
                        {{ currencyPosition(10) }}
                    </td>
                    <td class="text-right">
                        {{ currencyPosition(30) }}
                    </td>
                </tr>

                <tr>
                    <td>Pant(Men)</td>
                    <td class="text-center">03</td>
                    <td class="text-center">
                        {{ currencyPosition(20) }}
                    </td>
                    <td class="text-right">
                        {{ currencyPosition(60) }}
                    </td>
                </tr>

                {{-- end product items --}}

                <tr>
                    <td style="border:none"><strong style="color: #000">
                            Total Items: 12 Pieces</strong>
                    </td>
                    <td colspan="2" style="text-align: right;border: 0;">
                        SUBTOTAL
                    </td>
                    <td style="text-align: right; background: rgba(0, 0, 0, 0.07);">
                        {{ currencyPosition(375) }}
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;border: 0;">
                        DELIVERY CHARGE
                    </td>
                    <td style="text-align: right">{{ currencyPosition(50) }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;border: 0;">
                        COUPON DISCOUNT
                    </td>

                    <td style="text-align: right; color:red;">
                        -{{ currencyPosition(round(0)) }}
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right;border: 0;">
                        <strong>TOTAL PAYABLE</strong>
                    </td>
                    <td style="color:#000;text-align: right; background: #ddd;">
                        <strong>{{ currencyPosition(425) }}</strong>
                    </td>
                </tr>
            </tbody>
        </table>

        <h4 style="margin-top: 20px">
            <strong>Delivery Note:</strong>
            Call me for delivery
        </h4>
        <p style="font-weight: 700">Call me for delivery</p>
    </div>
    <div class="footer">
        <div style="padding: 10px 25px">
            <div class="address">
                <strong>{{ config('app.name') }}</strong> <br>
                Address: <strong>shekertek, Adabor, Mohammadpur</strong> <br>
                City: <strong>Dhaka</strong> <br>
            </div>
            <div class="contact">
                Mobile: <strong>01234567890</strong>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="message text-default">Thank you for your business</div>
            <div class="author">Authorised sign</div>
            <div class="signature">
                <img src="./web/signature.png">
            </div>
        </div>
    </div>
</body>

</html>
