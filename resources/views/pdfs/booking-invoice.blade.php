<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
</head>

<body>
    <div class="container">

        <table width="100%">
            <tr>
                <td width="75px">
                    <div class="logotype">
                        {{-- <img src="{{ asset('images/logo/logo.png') }}" width="50px" alt="logo"> --}}
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
        <!-- Booking Details Table -->
        <table width="100%"
            style="border-collapse: collapse; border-bottom: 1px solid #eee; text-align: center; margin-top: 20px">
            <thead>
                <tr>
                    <th style="border: 1px solid #ddd; padding: 8px;">Sr No</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Service</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Service Category</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">Price</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px;">{{ $index++ }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">{{ $booking->serviceType->name ?? '-' }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">{{ $booking->serviceCategory->name ?? '-' }}</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">
                        ${{ $booking->serviceCategory->base_price ?? '-' }}</td>
                </tr>
            </tbody>
        </table>
        <!-- Payment Information (Floating to Bottom-Right) -->
        <!-- Payment Information (Floating to Bottom-Right) -->
        <div style="padding: 16px; float: right; text-align: right; width: 300px; margin-top: 20px;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; text-align: left;"><strong>Service Price:</strong></td>
                    <td style="padding: 8px 0; text-align: right;">
                        @if ($booking->booking_type == 'secureship')
                            ${{ $bookingData->subTotal }}
                        @else
                            ${{ $bookingData->sub_total }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; text-align: left;"><strong>Tax Price:</strong></td>
                    <td style="padding: 8px 0; text-align: right;">
                        @if ($booking->booking_type == 'secureship')
                            ${{ $bookingData->taxAmount }}
                        @else
                            ${{ $bookingData->tax_price }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; text-align: left;"><strong>Total Price:</strong></td>
                    <td style="padding: 8px 0; text-align: right;">
                        @if ($booking->booking_type == 'secureship')
                            ${{ $bookingData->totalAmount }}
                        @else
                            ${{ $bookingData->total_price }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; text-align: left;"><strong>Payment Method:</strong></td>
                    <td style="padding: 8px 0; text-align: right;">{{ $bookingData->payment_method ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <!-- Clear Floats -->
        <div style="clear: both;"></div>

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
