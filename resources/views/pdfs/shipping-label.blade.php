<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>2 Point - Shipping Label</title>
</head>

<body>
    <div style="width: 100%; font-family: Arial, sans-serif; border: 1px solid #ddd; padding: 20px;">
        <!-- Header -->
        <table style="width: 100%; border-bottom: 2px solid #ddd; margin-bottom: 20px;">
            <tr>
                <td style="padding: 10px 0; font-size: 20px; font-weight: bold;">2 Point Delivery Ltd</td>
                <td style="text-align: right;">
                    <div style="font-weight: bold;">Order ID</div>
                    <div style="font-size: 24px; font-weight: bold;">{{ $booking->uuid }}</div>
                </td>
            </tr>
        </table>

        <!-- Address Information -->
        <table style="width: 100%; margin-bottom: 20px;">
            <tr>
                <td style="vertical-align: top;">
                    <strong>Receiver Name:</strong><br>
                    {{ $booking->receiver_name }}
                </td>
            </tr>
        </table>

        <!-- Billing and Shipping Address -->
        <table style="width: 100%; margin-bottom: 20px; border: 1px solid #ddd; padding: 10px;">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <strong>Pickup Address:</strong><br>
                    {{ $booking->pickup_address }}<br>
                    <strong>Tel:</strong> {{ $booking->client->phone ?? '-' }}
                </td>
                <td style="width: 50%; vertical-align: top;">
                    <strong>Dropoff address:</strong><br>
                    {{ $booking->dropoff_address }}<br>
                    <strong>Tel:</strong> {{ $booking->receiver_phone ?? '-' }}
                </td>
            </tr>
        </table>

        <!-- Date and Tracking -->
        <table style="width: 100%; margin-bottom: 20px;">
            <tr>
                <td style="vertical-align: top;">
                    <strong>Date:</strong> {{ app('dateHelper')->formatTimestamp($booking->created_at, 'Y-m-d') }}<br>
                    <strong>Payment Method:</strong> {{ strtoupper($booking->payment->payment_method ?? 'COD') }}
                </td>
                <td style="text-align: right;">
                    <strong>TRACKING CODE #</strong><br>
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTdTTT6UQzqt6-vh258osVcIbssOtp-aKVJc_EjLwKx30UzpY288A&s"
                        alt="Barcode" style="width: 150px; margin: 10px 0;"><br>
                    <span style="font-weight: bold; font-size: 16px;">{{ $booking->uuid }}</span>
                </td>
            </tr>
        </table>

        <!-- Product Details -->
        <table style="width: 100%; border: 1px solid #ddd; padding: 10px;">
            <tr style="background-color: #f5f5f5; font-weight: bold;">
                <td style="width: 30%;">Sr No.</td>
                <td style="width: 70%;">Delivery Note</td>
            </tr>
            <tr>
                <td>1</td>
                <td>{{ $booking->delivery_note ?? '-' }}</td>
            </tr>
        </table>
    </div>
</body>

</html>
