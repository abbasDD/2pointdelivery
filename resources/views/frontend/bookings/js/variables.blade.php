<script>
    // Base URL
    const base_url = "{{ url('/') }}";

    // Define some javascript variables to be used in JS
    var csrf_token = "{{ csrf_token() }}";
    var distance_price = 0;
    var service_price = 0;
    var per_km_price = 0;
    var service_charges = 0;
    var package_weight = 0;

    // Booking Payment Variables
    var payment_base_price = 0;
    var payment_distance = 0;
    var payment_base_distance = 0;
    var payment_extra_distance_price = 0;
    var payment_weight = 0;
    var payment_base_weight = 0;
    var payment_extra_weight_price = 0;
    var payment_total_price = 0;
    var helper_fee = 0;

    // Check Service Type
    var selectedServiceType = 'delivery';

    // Store $serviceCategories to JS array
    var selectedparceluuid = '';
    var selectedParcelTypeSecureshipEnable = false;
    var serviceCategories = {!! json_encode($serviceCategories) !!};
    if (serviceCategories.length > 0) {
        selectedparceluuid = serviceCategories[0].uuid;
        selectedParcelTypeSecureshipEnable = serviceCategories[0].is_secureship_enabled;
        // Update payment details
        payment_base_price = serviceCategories[0].base_price;
        payment_base_distance = serviceCategories[0].base_distance;
        payment_extra_distance_price = serviceCategories[0].extra_distance_price;
        payment_base_weight = serviceCategories[0].base_weight;
        payment_extra_weight_price = serviceCategories[0].extra_weight_price;
    }
    // Store $prioritySettings to JS array
    var prioritySettings = {!! json_encode($prioritySettings) !!};
    var selectedPriorityID = prioritySettings[0].id;
    console.log('Selected Priority ID: ' + selectedPriorityID);


    // Map Variables
    var map;
    var directionsService;
    var directionsRenderer;
    var defaultPickupLat =
        {{ request()->get('pickup_latitude', 33.53) ? request()->get('pickup_latitude', 33.53) : 33.53 }};
    var defaultPickupLng =
        {{ request()->get('pickup_longitude', 74.74) ? request()->get('pickup_longitude', 74.74) : 74.74 }};
    var defaultDeliveryLat =
        {{ request()->get('dropoff_latitude', 35.33) ? request()->get('dropoff_latitude', 35.33) : 35.33 }};
    var defaultDeliveryLng =
        {{ request()->get('dropoff_longitude', 75.44) ? request()->get('dropoff_longitude', 75.44) : 75.44 }};

    // Distance
    var distance = 0;
    var distance_in_km = 0;
    var distance_in_miles = 0;

    var shippingData = {
        "fromAddress": {
            "addr1": "1500 Bank St.",
            "countryCode": "CA",
            "postalCode": "K1K1K1",
            "city": "Ottawa",
            "taxId": "A‑123456‑Z",
            "residential": true,
            "isSaturday": true,
            "isInside": true,
            "isTailGate": true,
            "isTradeShow": true,
            "isLimitedAccess": true,
            "appointment": {
                "appointmentType": "None",
                "phone": "613-723-5891",
                "date": "2023-08-19",
                "time": "3:00 PM"
            }
        },
        "toAddress": {
            "addr1": "1500 Bank St.",
            "countryCode": "CA",
            "postalCode": "K1K1K1",
            "city": "Ottawa",
            "taxId": "A‑123456‑Z",
            "residential": true,
            "isSaturday": true,
            "isInside": true,
            "isTailGate": true,
            "isTradeShow": true,
            "isLimitedAccess": true,
            "appointment": {
                "appointmentType": "None",
                "phone": "613-723-5891",
                "date": "2023-08-19",
                "time": "3:00 PM"
            }
        },
        "packages": [{
            "packageType": "MyPackage",
            "userDefinedPackageType": "Refrigerator",
            "weight": 23,
            "weightUnits": "Lbs",
            "length": 19,
            "width": 230,
            "height": 430,
            "dimUnits": "Inches",
            "insurance": 18.3,
            "isAdditionalHandling": false,
            "signatureOptions": "None",
            "description": "Gift for darling",
            "temperatureProtection": true,
            "isDangerousGoods": true,
            "isNonStackable": true
        }],
        "shipDateTime": "2019-08-24T14:15:22Z",
        "currencyCode": "CAD",
        "billingOptions": "Prepaid",
        "isDocumentsOnly": true,
        "isStopinOnly": true
    };

    // Update the Payment Amount Card
    function updatePaymentAmount() {
        console.log('Distance: ' + distance_in_km);

        // If selectedparceluuid is empty
        if (selectedparceluuid == '') {
            // Get from first service type from serviceCategories
            selectedparceluuid = serviceCategories[0].uuid;
        }

        // Get data on selected uuid
        for (let i = 0; i < serviceCategories.length; i++) {
            if (serviceCategories[i].uuid === selectedparceluuid) {
                // console.log(serviceCategories[i].base_price);
                if (distance_in_km > parseFloat(serviceCategories[i].base_distance)) {
                    distance_price = (distance_in_km - parseFloat(
                        serviceCategories[i].base_distance)) * parseFloat(serviceCategories[i].extra_distance_price);
                } else {
                    distance_price = 0;
                }

                // Update payment details
                payment_base_price = serviceCategories[i].base_price;
                payment_base_distance = serviceCategories[i].base_distance;
                payment_extra_distance_price = serviceCategories[i].extra_distance_price;
                payment_base_weight = serviceCategories[i].base_weight;
                payment_extra_weight_price = serviceCategories[i].extra_weight_price;

                helper_fee = serviceCategories[i].helper_fee;

                service_price = serviceCategories[i].base_price;
                vehicle_price = 100;
            }
        }

        // Get value of priority option
        var priorityID = document.querySelector('select[name="priority"]').value;
        // Get price of priority from prioritySettings
        for (let i = 0; i < prioritySettings.length; i++) {
            if (prioritySettings[i].id == priorityID) {
                priorityPriceValue = prioritySettings[i].price;
            }
        }


        // Calculate Weight Price Value
        calculateWeightPrice();

        var serviceType = document.querySelector('select[name="serviceType"]').value;
        document.getElementById('distance-price-value').innerHTML = Math.round(distance_price * 100) / 100;
        document.getElementById('base-price-value').innerHTML = Math.round(service_price * 100) / 100;
        document.getElementById('vehicle-price-value').innerHTML = Math.round(vehicle_price * 100) / 100;
        document.getElementById('priority-price-value').innerHTML = priorityPriceValue;
        document.getElementById('booking-distance-value').innerHTML = Math.round(distance_in_km * 100) / 100;
        document.getElementById('helper-fee-value').innerHTML = Math.round(helper_fee * 100) / 100;

        // Ge total amount
        var amountToPay = amountToPayCalculation();
        // var amountToPay = parseFloat(distance_price) +
        //     parseFloat(service_price) +
        //     parseFloat(priorityPriceValue) +
        //     parseFloat(vehicle_price);
        document.getElementById('amount-to-pay-value').innerHTML = Math.round(amountToPay * 100) / 100;


        document.getElementById('weight-price-value').innerHTML = Math.round(weight_price * 100) / 100;

        // console.log('Function calling ' + selectedparceluuid);
    }

    // Calculate Weight Price
    function calculateWeightPrice() {
        var weight = document.querySelector('input[name="package_weight"]').value;
        if (weight == '') {
            weight = 0;
        }
        weight = parseFloat(weight);
        var length = document.querySelector('input[name="package_length"]').value;
        if (length == '') {
            length = 1;
        }
        var width = document.querySelector('input[name="package_width"]').value;
        if (width == '') {
            width = 1;
        }
        var height = document.querySelector('input[name="package_height"]').value;
        if (height == '') {
            height = 1;
        }
        var cubicVolume = parseFloat(length) * parseFloat(width) * parseFloat(height);
        var cubicVolumeWeight = 0;
        var dimension = '{{ config('dimension') }}';

        if (dimension != 'INCH') {
            cubicVolumeWeight = cubicVolume / 5000;
        } else {
            cubicVolumeWeight = cubicVolume / 139;
        }

        if (weight < cubicVolumeWeight) {
            package_weight = Math.round((cubicVolumeWeight) * 100) / 100;
            // document.getElementById('weight-price-value').innerHTML = Math.round((cubicVolumeWeight) * 100) /
            //     100;
        } else {
            // document.getElementById('weight-price-value').innerHTML = parseFloat(weight);
            package_weight = parseFloat(weight);
        }

        console.log('Package Weight: ' + package_weight);
    }
</script>
