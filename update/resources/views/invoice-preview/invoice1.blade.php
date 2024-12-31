<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        .text-default {
            color: #39D8D8;
        }

        .bg-default {
            background: #39D8D8;
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
    <title>#000025 - invioce</title>
</head>

<body>
    <div class="logo-sectioon">
        <div class="logo">
            <img class="white-filter" src="./images/logo-white.png" alt="logo">
        </div>
    </div>

    <div class="header bg-default">
        <div class="content">
            <div class="invoice">Invoice</div>
            <div class="invoiceId">
                NO: INV-#000025
            </div>
        </div>
    </div>

    <div class="container">

        <h1 class="bill">Bill To:</h1>

        <h3 class="" style="margin: 6px 0">Lester K. Davis</h3>
        <p style="margin: 0">01704123456</p>
        </p>
        <h4>
            Road No: 08,
            House No: 19,
            Address Line: Shekhertek, Adabor, Mohammadpur,Dhaka.
        </h4>

        <div class="date-section">
            <div class="date">Date: {{ date('d F, Y') }}</div>
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
                {{-- product items --}}
                <tr>
                    <td class="text-center">1</td>
                    <td>Jacket Wash</td>
                    <td class="text-center">2</td>
                    <td class="text-center">
                        {{ currencyPosition(30) }}
                    </td>
                    <td class="text-center">
                        {{ currencyPosition(60) }}
                    </td>
                </tr>

                <tr>
                    <td class="text-center">2</td>
                    <td>Blazer</td>
                    <td class="text-center">1</td>
                    <td class="text-center">
                        {{ currencyPosition(210) }}
                    </td>
                    <td class="text-center">
                        {{ currencyPosition(210) }}
                    </td>
                </tr>

                <tr>
                    <td class="text-center">3</td>
                    <td>T-shirt</td>
                    <td class="text-center">3</td>
                    <td class="text-center">
                        {{ currencyPosition(5) }}
                    </td>
                    <td class="text-center">
                        {{ currencyPosition(15) }}
                    </td>
                </tr>

                <tr>
                    <td class="text-center">4</td>
                    <td>Shirts</td>
                    <td class="text-center">3</td>
                    <td class="text-center">
                        {{ currencyPosition(10) }}
                    </td>
                    <td class="text-center">
                        {{ currencyPosition(30) }}
                    </td>
                </tr>

                <tr>
                    <td class="text-center">5</td>
                    <td>Pant(Men)</td>
                    <td class="text-center">3</td>
                    <td class="text-center">
                        {{ currencyPosition(20) }}
                    </td>
                    <td class="text-center">
                        {{ currencyPosition(60) }}
                    </td>
                </tr>

                {{-- end product items --}}

                <tr>
                    <td colspan="3" style="border: none;"></td>
                    <td class="text-center">Delivery Charge</td>
                    <td class="text-center">{{ currencyPosition(50) }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="border: none;"></td>
                    <td class="text-center">Sub Total</td>
                    <td class="text-center">{{ currencyPosition(375) }}</td>
                </tr>

                <tr>
                    <td colspan="3" style="border: none;"></td>
                    <td class="text-center">Coupon Discount</td>
                    <td class="text-center" style="color:red;">
                        -{{ currencyPosition(round(0)) }}
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="border: none;"></td>
                    <td class="text-center bg-default">
                        <strong>Total Payable</strong>
                    </td>
                    <td class="text-center bg-default">
                        <strong>{{ currencyPosition(425) }}</strong>
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
                        <span class="">Cash Payment</span>
                    </div>
                    <div class="font-18" style="padding-top: 10px;">
                        <strong style="margin-right: 43px;">Email:</strong>
                        <span class="">shop_1@example.com</span>
                    </div>

                </div>
                <div class="w-30 float-left text-center">
                    <div class="signature">
                        <img src="./web/signature.png">
                    </div>
                    <h2 class="text-center" style="margin: 0; line-height: 20px">Mr.ak in</h2>
                    <p class="text-center" style="margin: 0; line-height: 20px">Rosalee shop</p>
                </div>
            </div>
            <div class="clearfix" style="padding: 12px 0; margin-top: 6px;">
                <div class="col-3 float-left" style="position: relative">
                    <img src="./images/icons/call.png" width="15"
                        style="display: inline-block; margin-top: 8px; margin-right: 6px;">
                    <span style="display: inline-block">
                        01234567890
                    </span>
                </div>
                <div class="col-3 float-left">
                    <img src="./images/icons/email.png" width="15"
                        style="display: inline-block; margin-top: 8px;margin-right: 6px;">
                    <span style="display: inline-block">
                        shop_1@example.com
                    </span>
                </div>
                <div class="col-3 float-left">
                    <img src="./images/icons/location.png" width="15"
                        style="display: inline-block; margin-top: 0px;margin-right: 4px;">
                    <span style="display: inline-block; font-size: 14px; width: 224px; height: 16px;">
                       shekertek, Adabor, Mohammadpur.
                    </span>
                </div>
            </div>
        </div>
        <div class="footerColor"></div>
    </div>
</body>

</html>
