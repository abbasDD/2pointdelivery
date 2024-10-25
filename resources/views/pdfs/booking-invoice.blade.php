<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        body {
            font-family: Helvetica, sans-serif;
            font-size: 13px;
        }

        .container {
            max-width: 680px;
            margin: 0 auto;
        }

        .logotype {
            background: #000;
            color: #fff;
            width: 75px;
            height: 75px;
            line-height: 75px;
            text-align: center;
            font-size: 11px;
        }

        .column-title {
            background: #eee;
            text-transform: uppercase;
            padding: 15px 5px 15px 15px;
            font-size: 11px
        }

        .column-detail {
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
        }

        .column-header {
            background: #eee;
            text-transform: uppercase;
            padding: 15px;
            font-size: 11px;
            border-right: 1px solid #eee;
        }

        .row {
            padding: 7px 14px;
            border-left: 1px solid #eee;
            border-right: 1px solid #eee;
            border-bottom: 1px solid #eee;
        }

        .alert {
            background: #038164;
            padding: 20px;
            margin: 20px 0;
            line-height: 22px;
            color: #333
        }

        .socialmedia {
            background: #eee;
            padding: 20px;
            display: inline-block
        }
    </style>
</head>

<body>
    <div class="container">

        <table width="100%">
            <tr>
                <td width="75px">
                    <div class="logotype">
                        {{-- <img src="{{ $company_logo }}" width="50px" alt="logo"> --}}
                        2 Point
                    </div>
                </td>
                <td width="300px">
                    <div
                        style="background: #038164; color: #fff; border-left: 15px solid #fff;padding-left: 30px;font-size: 26px;font-weight: bold;letter-spacing: -1px;height: 73px;line-height: 75px;">
                        Order invoice</div>
                </td>
                <td></td>
            </tr>
        </table>
        <br><br>
        <table width="100%" style="border-collapse: collapse;">
            <tr>
                <td width="50%" style="background:#eee;padding:20px;">
                    <strong>Date:</strong> {{ app('dateHelper')->formatTimestamp($booking->created_at, 'Y-m-d') }}<br>
                    <strong>Payment type:</strong> {{ $bookingPayment->payment_method }}<br>
                    <strong>Delivery type:</strong> {{ $booking->booking_type }}<br>
                </td>
                <td style="background:#eee;padding:20px;">
                    <strong>Tracking ID:</strong> {{ $booking->uuid }}<br>
                    <strong>E-mail:</strong> {{ $client_user->email }}<br>
                    <strong>Phone:</strong> {{ $client->phone_no ?? '-' }}<br>
                </td>
            </tr>
        </table><br>
        <table width="100%">
            <tr>
                <td width="50%">
                    <table>
                        <tr>
                            <td style="vertical-align: text-top;">
                                <div
                                    style="background: #038164 url(https://cdn0.iconfinder.com/data/icons/commerce-line-1/512/comerce_delivery_shop_business-07-128.png);width: 50px;height: 50px;margin-right: 10px;background-position: center;background-size: 42px;">
                                </div>
                            </td>
                            <td>
                                <strong>Pickup</strong><br>
                                <p>
                                    {{ $booking->pickup_address }}
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="50%">
                    <table>
                        <tr>
                            <td style="vertical-align: text-top;">
                                <div
                                    style="background: #038164 url(https://cdn4.iconfinder.com/data/icons/app-custom-ui-1/48/Check_circle-128.png) no-repeat;width: 50px;height: 50px;margin-right: 10px;background-position: center;background-size: 25px;">
                                </div>
                            </td>
                            <td>
                                <strong>Dropoff</strong><br>
                                <p>
                                    {{ $booking->dropoff_address }}
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table><br>
        <table width="100%" style="border-top:1px solid #eee;border-bottom:1px solid #eee;padding:0 0 8px 0">
            <tr>
                <td>
                    <h3>Checkout details</h3>
                    @if ($bookingPayment->payment_method == 'cod')
                        <p>You have to pay on arrival with a total of
                            ${{ $bookingPayment->total_price }}</p>
                    @else
                        Your checkout made by {{ $bookingPayment->payment_method }} with a total of
                        ${{ $bookingPayment->total_price }}
                    @endif
                <td>
            </tr>
        </table><br>
        <div
            style="background: #038164 url(https://cdn4.iconfinder.com/data/icons/basic-ui-2-line/32/shopping-cart-shop-drop-trolly-128.png) no-repeat;width: 50px;height: 50px;margin-right: 10px;background-position: center;background-size: 25px;float: left; margin-bottom: 15px;">
        </div>
        <h3>Booking Details</h3>

        <table width="100%" style="border-collapse: collapse;border-bottom:1px solid #eee;">
            <tr>
                <td width="20%" class="column-header">Sr No</td>
                <td width="40%" class="column-header">Description</td>
                <td width="40%" class="column-header">Value</td>
            </tr>
            {{-- Service Type --}}
            <tr>
                <td class="row">{{ $index++ }}</td>
                <td class="row">Service</td>
                <td class="row">{{ $booking->serviceType->name }}</td>
            </tr>
            {{-- Service Category --}}
            <tr>
                <td class="row">{{ $index++ }}</td>
                <td class="row">Service Category</td>
                <td class="row">{{ $booking->serviceCategory->name }}</td>
            </tr>
            {{-- Total Price --}}
            <tr>
                <td class="row">{{ $index++ }}</td>
                <td class="row">Price</td>
                <td class="row">${{ $booking->total_price }}</td>
            </tr>
        </table>
        <br>

        {{-- Payment Terms --}}
        <h3>Payment Terms</h3>
        <p>
            We accept payments via credit card, bank transfer, and PayPal.
        </p>
        <p>
            Any disputes regarding the invoice must be reported within 10 days of the invoice date.
        </p>
        <p>
            For payment inquiries, please contact our billing department at 2pointdelivery@gmail.com.
        </p>

    </div><!-- container -->
</body>

</html>
