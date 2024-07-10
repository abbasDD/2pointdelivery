<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>2 Point - Shipping Label</title>

    {{-- Load bootstrap --}}
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <style>
        .shipping-label-products-item {
            border: 1px solid;
            border-color: transparent transparent #a0aec0 transparent;
        }

        .shipping-label-products-qty {
            width: 40px;
            flex: none;
        }

        .shipping-label-products-name {
            flex: 1 1 auto;
        }

        .shipping-label-products-price {
            text-align: right;
            width: 80px;
            flex: none;
            font-weight: bold;
        }
    </style>
</head>

<body class="container">
    <div class="wrapper m-4"> <!-- wrapper for sample -->
        <div class="shipping-label border border-gray-500 rounded-lg">

            <div class="shipping-label-header">

                <div class=" flex items-center px-4 border-b-2 border-gray-700">
                    <div class="flex-auto flex items-center py-2">
                        <img src="{{ asset('images/logo.png') }}" alt="" class="w-48 inline-block mr-2">
                        <p class="inline-block text-lg text-gray-700 leading-tight">Shipping label</p>
                    </div>
                    <div class="flex-none text-right leading-none border-l border-gray-600 pl-4 py-2">
                        <strong>Order ID</strong>
                        <p class="text-2xl font-bold">059213</p>
                    </div>
                </div>

                <p class="flex px-4 py-3 leading-tight">
                    <strong class="flex-none">
                        From address:&nbsp;&nbsp;
                    </strong>
                    <span class="flex-auto">
                        25/F Workington Tower, 78 Bonham Strand, Sheung Wan, Hong Kong
                    </span>
                </p>


            </div>

            <div class="shipping-label-body border-t-4 border-b-4 border-gray-700 p-4">

                <div class="flex -px-2 border-b-2 border-gray-600 pb-4">
                    <div class="w-1/2 px-2">
                        <strong>Billing address:</strong>
                        <p>
                            Thunder Professional Audio<br>
                            Jalan Genteng besar no. 39 Surabaya Kota<br>
                            Surabaya, Jawa Timur, <strong class="text-gray-700">60275</strong><br>
                            Indonesia
                        </p>
                        <p class="pt-1">
                            <strong>Tel:&nbsp;</strong><span>6281359621464</span>
                        </p>
                    </div>
                    <div class="w-1/2 px-2">
                        <strong>Shipping address:</strong>
                        <p>
                            Thunder Professional Audio<br>
                            Jalan Genteng besar no. 39 Surabaya Kota<br>
                            Surabaya, Jawa Timur, <strong class="text-gray-700">60275</strong><br>
                            Indonesia
                        </p>
                        <p class="pt-1">
                            <strong>Tel:&nbsp;</strong><span>6281359621464</span>
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center">
                    <div class="w-full text-center pb-2 sm:pb-0 sm:w-2/5 sm:text-left">
                        <p class="pt-3 font-semibold"><span class="text-gray-700">Date :</span> Dec 12, 2019</p>
                        <p class="text-lg font-semibold"><span class="text-gray-700">Shipping method: </span> AIR CARGO
                        </p>
                    </div>
                    <div class="w-full sm:w-3/5 text-center">
                        <p class="font-bold text-lg">TRACKING CODE #</p>
                        <img class="mx-auto"
                            src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTdTTT6UQzqt6-vh258osVcIbssOtp-aKVJc_EjLwKx30UzpY288A&s"
                            alt="">
                    </div>
                </div>

            </div>


            <div class="shipping-label-footer border-t-4 border-gray-600 py-4">
                <div class="shipping-label-products px-2 py-2">
                    <div
                        class="shipping-label-products-header flex bg-gray-800 text-white font-bold px-2 -px-2 py-1 rounded-t">
                        <div class="shipping-label-products-qty">
                            Qty
                        </div>
                        <div class="shipping-label-products-name">
                            Product
                        </div>
                        <div class="shipping-label-products-price">
                            Total Price
                        </div>
                    </div>

                    <div class="shipping-label-products-body">
                        <div class="shipping-label-products-item flex px-2 -px-2 py-1">
                            <div class="shipping-label-products-qty">
                                2465
                            </div>
                            <div class="shipping-label-products-name">
                                Apple airpods1 2 protective cover cute cartoon stickers whireless Bluetooth headset box
                                shell protection film female Sesame Street - Sesame Street
                            </div>
                            <div class="shipping-label-products-price">
                                $1,400.00
                            </div>
                        </div>
                    </div>

                    <div class="shipping-label-products-item flex px-2 -px-2 py-1">
                        <div class="shipping-label-products-qty">
                            2465
                        </div>
                        <div class="shipping-label-products-name">
                            Apple airpods1 2 protective cover cute cartoon stickers whireless Bluetooth headset box
                            shell protection film female Sesame Street - Sesame Street
                        </div>
                        <div class="shipping-label-products-price">
                            $1,400.00
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</body>

</html>
