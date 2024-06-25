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
        <h3>Payment Details</h3>

        <table width="100%" style="border-collapse: collapse;border-bottom:1px solid #eee;">
            <tr>
                <td width="20%" class="column-header">Sr No</td>
                <td width="60%" class="column-header">Description</td>
                <td width="20%" class="column-header">Price</td>
            </tr>
            {{-- Service Price --}}
            <tr>
                <td class="row">{{ $index++ }}</td>
                <td class="row">Service Price</td>
                <td class="row">${{ $bookingPayment->service_price }}</td>
            </tr>
            {{-- Distance Price --}}
            <tr>
                <td class="row">{{ $index++ }}</td>
                <td class="row">Distance Price</td>
                <td class="row">${{ $bookingPayment->distance_price }}</td>
            </tr>
            {{-- Weight Price --}}
            <tr>
                <td class="row">{{ $index++ }}</td>
                <td class="row">Weight Price</td>
                <td class="row">${{ $bookingPayment->weight_price }}</td>
            </tr>
            {{-- Priority Price --}}
            <tr>
                <td class="row">{{ $index++ }}</td>
                <td class="row">Priority Price</td>
                <td class="row">${{ $bookingPayment->priority_price }}</td>
            </tr>
            {{-- Moving Details Start --}}
            @if ($booking->booking_type == 'moving')
                {{-- Floor Access Price --}}
                <tr>
                    <td class="row">{{ $index++ }}</td>
                    <td class="row">Floor Access Price</td>
                    <td class="row">${{ $bookingPayment->floor_assess_price }}</td>
                </tr>
                {{-- Floor Plan Price --}}
                <tr>
                    <td class="row">{{ $index++ }}</td>
                    <td class="row">Floor Plan Price</td>
                    <td class="row">${{ $bookingPayment->floor_plan_price }}</td>
                </tr>
                {{-- Job Details Price --}}
                <tr>
                    <td class="row">{{ $index++ }}</td>
                    <td class="row">Job Details Price</td>
                    <td class="row">${{ $bookingPayment->job_details_price }}</td>
                </tr>
                {{-- No of Room Price --}}
                <tr>
                    <td class="row">{{ $index++ }}</td>
                    <td class="row">No of Room Price</td>
                    <td class="row">${{ $bookingPayment->no_of_room_price }}</td>
                </tr>
            @endif
            {{-- Moving Details End --}}

            {{-- Delivery Details Start --}}
            @if ($booking->booking_type == 'delivery')
                {{-- Vehicle Price --}}
                <tr>
                    <td class="row">{{ $index++ }}</td>
                    <td class="row">Vehicle Price</td>
                    <td class="row">${{ $bookingPayment->vehicle_price }}</td>
                </tr>
            @endif
            {{-- Delivery Details End --}}
        </table>
        <br>
        <table width="100%" style="background:#eee;padding:20px;">
            <tr>
                <td>
                    <table width="300px" style="float:right">
                        <tr>
                            <td><strong>Sub-total:</strong></td>
                            <td>${{ $bookingPayment->sub_total }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tax:</strong></td>
                            <td>${{ $bookingPayment->tax_price }}</td>
                        </tr>
                        <tr>
                            <td><strong>Grand total:</strong></td>
                            <td>${{ $bookingPayment->total_price }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

    </div><!-- container -->
</body>

</html>
