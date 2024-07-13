<script>
    // Base URL
    const base_url = "{{ url('/') }}";

    // Define some javascript variables to be used in JS
    var csrf_token = "{{ csrf_token() }}";
    var distance_price = 0;
    var base_price = 0;
    var per_km_price = 0;
    var service_charges = 0;
    var package_weight = 0;
    var package_length = 0;
    var package_width = 0;
    var package_height = 0;
    var calculated_weight = 0;
    var vehicle_price = 0;
    var vehicle_price_type = 0;
    var priorityID = 0;
    var tax_price = 0;
    var volume_enabled = 0;
    var package_value = 0;
    var insurance_value = 0;
    var insurance_enabled = {{ config('insurance') == 'enabled' ? 1 : 0 }};
    var no_of_room_enabled = 0;
    var floor_plan_enabled = 0;
    var floor_assess_enabled = 0;
    var job_details_enabled = 0;
    var moving_details_enabled = 0;
    var selectedNoOfRoomID = 0;
    var selectedFloorPlanID = 0;
    var selectedFloorAssessID = 0;
    var selectedJobDetailsID = [];
    var selectedMovingDetailsID = [];
    var movingDetailsTotalItems = 0;
    var movingDetailsTotalWeight = 0;
    var movingDetailsTotalVolume = 0;

    // Store $prioritySettings to JS array
    var prioritySettings = {!! json_encode($prioritySettings) !!};
    var selectedPriorityID = prioritySettings[0].id;
    console.log('Selected Priority ID: ' + selectedPriorityID);
    var newUpdatedPriority = [];

    // Store addresses to JS array
    var addresses = {!! json_encode($addresses) !!};

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
    var amountToPay = 0;

    // Check Service Type
    var selectedServiceType = 'delivery';
    var moving_price_type = 'hour';
    var selectedServiceTypeID = {{ request()->get('serviceType') ? request()->get('serviceType') : 1 }};

    // Store $serviceCategories to JS array
    var selectedServiceCategoryUuid = '';
    var selectedParcelTypeSecureshipEnable = false;
    var serviceCategories = {!! json_encode($serviceCategories) !!};
    if (serviceCategories.length > 0) {
        selectedServiceCategoryUuid = serviceCategories[0].uuid;
        vehicle_price = serviceCategories[0].vehicle_price;
        vehicle_price_type = serviceCategories[0].vehicle_price_type;
        selectedParcelTypeSecureshipEnable = serviceCategories[0].is_secureship_enabled;
        moving_price_type = serviceCategories[0].moving_price_type;
        // Update payment details
        payment_base_price = serviceCategories[0].base_price;
        payment_base_distance = serviceCategories[0].base_distance;
        payment_extra_distance_price = serviceCategories[0].extra_distance_price;
        payment_base_weight = serviceCategories[0].base_weight;
        payment_extra_weight_price = serviceCategories[0].extra_weight_price;
        volume_enabled = serviceCategories[0].volume_enabled;
        no_of_room_enabled = serviceCategories[0].no_of_room_enabled;
        floor_plan_enabled = serviceCategories[0].floor_plan_enabled;
        floor_assess_enabled = serviceCategories[0].floor_assess_enabled;
        job_details_enabled = serviceCategories[0].job_details_enabled;
        moving_details_enabled = serviceCategories[0].moving_details_enabled;
        updateMovingFormFields();
        // console.log('Selected category vehicle price is ' + serviceCategories[0]);
    }



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


    // Update the Payment Amount Card
    function updatePaymentAmount() {
        console.log('Distance: ' + distance_in_km);

        updateMovingPackageDetails();

        // If selectedServiceCategoryUuid is empty
        if (selectedServiceCategoryUuid == '') {
            // Get from first service type from serviceCategories
            selectedServiceCategoryUuid = serviceCategories[0].uuid;
            vehicle_price = serviceCategories[0].vehicle_price;
            vehicle_price_type = serviceCategories[0].vehicle_price_type;
            volume_enabled = serviceCategories[0].volume_enabled;
            moving_price_type = serviceCategories[0].moving_price_type;
            no_of_room_enabled = serviceCategories[0].no_of_room_enabled;
            floor_plan_enabled = serviceCategories[0].floor_plan_enabled;
            floor_assess_enabled = serviceCategories[0].floor_assess_enabled;
            job_details_enabled = serviceCategories[0].job_details_enabled;
            moving_details_enabled = serviceCategories[0].moving_details_enabled;
            updateMovingFormFields();
        }

        // Get data on selected uuid
        for (let i = 0; i < serviceCategories.length; i++) {
            if (serviceCategories[i].uuid === selectedServiceCategoryUuid) {
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
                vehicle_price = serviceCategories[i].vehicle_price;
                vehicle_price_type = serviceCategories[i].vehicle_price_type;
                volume_enabled = serviceCategories[i].volume_enabled;
                moving_price_type = serviceCategories[i].moving_price_type;

                no_of_room_enabled = serviceCategories[i].no_of_room_enabled;
                floor_plan_enabled = serviceCategories[i].floor_plan_enabled;
                floor_assess_enabled = serviceCategories[i].floor_assess_enabled;
                job_details_enabled = serviceCategories[i].job_details_enabled;
                moving_details_enabled = serviceCategories[i].moving_details_enabled;
                updateMovingFormFields();

                helper_fee = serviceCategories[i].helper_fee;

                base_price = serviceCategories[i].base_price;

                vehicle_price_value = parseFloat(vehicle_price) * parseFloat(distance_in_km);
            }
        }

        // Get value of priority option
        priorityID = document.querySelector('select[name="priority"]').value;
        // Get price of priority from prioritySettings
        for (let i = 0; i < prioritySettings.length; i++) {
            if (prioritySettings[i].id == priorityID) {
                priorityPriceValue = prioritySettings[i].price;
            }
        }

        // if service type is moving
        if (selectedServiceType == 'moving') {
            if (moving_price_type == 'hour') {
                $("#floor_size_div").addClass("d-none");
                $("#no_of_hours_div").removeClass("d-none");

                // add required attribute to no_of_hours
                document.querySelector('input[name="no_of_hours"]').setAttribute('required', 'required');

                // remove required attribute from floor_size
                document.querySelector('input[name="floor_size"]').removeAttribute('required');
            } else {
                $("#floor_size_div").removeClass("d-none");
                $("#no_of_hours_div").addClass("d-none");

                // remove required attribute from no_of_hours
                document.querySelector('input[name="no_of_hours"]').removeAttribute('required');

                // add required attribute to floor_size
                document.querySelector('input[name="floor_size"]').setAttribute('required', 'required');
            }
        }

        // Show or Hide deliveryPackageDimensions based on volume_enabled
        if (volume_enabled == 1) {
            document.getElementById('deliveryPackageDimensions').style.display = 'flex';
            // Add required fields to package_length, package_width, package_height
            document.querySelector('input[name="package_length"]').setAttribute('required', 'required');
            document.querySelector('input[name="package_width"]').setAttribute('required', 'required');
            document.querySelector('input[name="package_height"]').setAttribute('required', 'required');
        } else {
            document.getElementById('deliveryPackageDimensions').style.display = 'none';
            // Remove required fields from package_length, package_width, package_height
            document.querySelector('input[name="package_length"]').removeAttribute('required');
            document.querySelector('input[name="package_width"]').removeAttribute('required');
            document.querySelector('input[name="package_height"]').removeAttribute('required');
        }

        // Using AJAX call to calculate data from server
        calculateDeliveryEstimateUsingAjax();

        // var serviceType = document.querySelector('select[name="serviceType"]').value;
        // document.getElementById('distance-price-value').innerHTML = Math.round(distance_price * 100) / 100;
        // document.getElementById('base-price-value').innerHTML = Math.round(base_price * 100) / 100;
        // document.getElementById('vehicle-price-value').innerHTML = Math.round(vehicle_price_value * 100) / 100;
        // document.getElementById('priority-price-value').innerHTML = Math.round(priorityPriceValue * 100) / 100;
        // document.getElementById('booking-distance-value').innerHTML = Math.round(distance_in_km * 100) / 100;
        // // document.getElementById('helper-fee-value').innerHTML = Math.round(helper_fee * 100) / 100;

        // // Ge total amount
        // amountToPay = amountToPayCalculation();
        // // var amountToPay = parseFloat(distance_price) +
        // //     parseFloat(base_price) +
        // //     parseFloat(priorityPriceValue) +
        // //     parseFloat(vehicle_price);
        // document.getElementById('amount-to-pay-value').innerHTML = Math.round(amountToPay * 100) / 100;


        // document.getElementById('weight-price-value').innerHTML = Math.round(weight_price * 100) / 100;

        // console.log('Function calling ' + selectedServiceCategoryUuid);
    }

    // Calculate Weight Price
    function calculateWeightPrice() {
        package_weight = document.querySelector('input[name="package_weight"]').value;
        if (package_weight == '') {
            package_weight = 0;
        }
        package_weight = parseFloat(package_weight);
        package_length = document.querySelector('input[name="package_length"]').value;
        if (package_length == '') {
            package_length = 1;
        }
        package_width = document.querySelector('input[name="package_width"]').value;
        if (package_width == '') {
            package_width = 1;
        }
        package_height = document.querySelector('input[name="package_height"]').value;
        if (package_height == '') {
            package_height = 1;
        }
        var cubicVolume = parseFloat(package_length) * parseFloat(package_width) * parseFloat(package_height);
        var cubicVolumeWeight = 0;
        var dimension = '{{ config('dimension') }}';

        if (dimension != 'INCH') {
            cubicVolumeWeight = cubicVolume / 5000;
        } else {
            cubicVolumeWeight = cubicVolume / 139;
        }

        if (package_weight < cubicVolumeWeight) {
            calculated_weight = Math.round((cubicVolumeWeight) * 100) / 100;
            // document.getElementById('weight-price-value').innerHTML = Math.round((cubicVolumeWeight) * 100) /
            //     100;
        } else {
            // document.getElementById('weight-price-value').innerHTML = parseFloat(weight);
            calculated_weight = parseFloat(package_weight);
        }

        console.log('Package Weight: ' + calculated_weight);
    }


    // Calculate amount to pay
    function calculateDeliveryEstimateUsingAjax() {
        console.log('Function calling ' + floor_assess_enabled);

        // Create a form data and appnd data
        var formData = new FormData();

        // Calculate Weight Price Value before calling AJAX
        calculateWeightPrice();

        // Add csrf token
        formData.append('_token', "{{ csrf_token() }}");

        // append selectedParcelTypeSecureshipEnable
        formData.append('selectedParcelTypeSecureshipEnable', selectedParcelTypeSecureshipEnable);

        // Append variables
        formData.append('distance_in_km', distance_in_km); // distance
        formData.append('selectedServiceType', selectedServiceType); // service type
        formData.append('selectedServiceTypeID', selectedServiceTypeID); // service type
        formData.append('moving_price_type', moving_price_type); // moving_price_type
        formData.append('selectedServiceCategoryUuid', selectedServiceCategoryUuid); // parcel type
        formData.append('priorityID', priorityID); // priority
        formData.append('package_weight', package_weight); // package_weight
        formData.append('package_length', package_length); // package_length
        formData.append('package_width', package_width); // package_width
        formData.append('package_height', package_height); // package_height
        formData.append('calculated_weight', calculated_weight); // calculated_weight
        formData.append('selectedNoOfRoomID', selectedNoOfRoomID); // selectedNoOfRoomID
        formData.append('selectedFloorPlanID', selectedFloorPlanID); // selectedFloorPlanID
        formData.append('selectedFloorAssessID', selectedFloorAssessID); // selectedFloorAssessID
        formData.append('selectedJobDetailsID', selectedJobDetailsID); // selectedJobDetailsID
        formData.append('selectedMovingDetailsID', selectedMovingDetailsID); // selectedMovingDetailsID

        // Get declared value of package
        package_value = document.querySelector('input[name="package_value"]').value;
        // if not empty
        if (package_value == '') {
            package_value = 0;
        }
        formData.append('package_value', package_value);

        // Get value of floor_size
        floor_size = document.querySelector('input[name="floor_size"]').value;
        if (floor_size == '') {
            floor_size = 1;
        }
        formData.append('floor_size', floor_size);

        // Get value of no_of_hours
        no_of_hours = document.querySelector('input[name="no_of_hours"]').value;
        if (no_of_hours == '') {
            no_of_hours = 1;
        }
        formData.append('no_of_hours', no_of_hours);


        console.log(formData);

        // Call Ajax
        $.ajax({
            url: "{{ route('estimate.index') }}",
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log(response);
                if (response.status == 'success') {
                    updatePaymentCartData(response.data);
                }
            },
            error: function(error) {
                console.log(error);
            }
        });


    }

    function updatePaymentCartData(data) {
        console.log('Here we updated cart')
        base_price = data.base_price;
        distance_price = data.distance_price;
        priority_price = data.priority_price;
        vehicle_price = data.vehicle_price;
        weight_price = data.weight_price;
        tax_price = data.tax_price;
        amountToPay = data.amountToPay;
        insurance_value = data.insurance_value;
        no_of_room_price = data.no_of_room_price;
        floor_plan_price = data.floor_plan_price;
        floor_assess_price = data.floor_assess_price;
        job_details_price = data.job_details_price;
        document.getElementById('base-price-value').innerHTML = Math.round(base_price * 100) /
            100;
        document.getElementById('distance-price-value').innerHTML = Math.round(distance_price *
            100) / 100;
        document.getElementById('priority-price-value').innerHTML = Math.round(priority_price *
            100) / 100;
        document.getElementById('vehicle-price-value').innerHTML = Math.round(vehicle_price *
            100) / 100;
        document.getElementById('insurance-price-value').innerHTML = Math.round(insurance_value *
            100) / 100;
        document.getElementById('weight-price-value').innerHTML = Math.round(weight_price *
            100) / 100;
        document.getElementById('booking-distance-value').innerHTML = Math.round(
            distance_in_km * 100) / 100;
        document.getElementById('tax-price-value').innerHTML = Math.round(tax_price *
            100) / 100;
        document.getElementById('amount-to-pay-value').innerHTML = Math.round(amountToPay *
            100) / 100;
        document.getElementById('no-of-room-price-value').innerHTML = Math.round(no_of_room_price *
            100) / 100;
        document.getElementById('floor-pan-price-value').innerHTML = Math.round(floor_plan_price *
            100) / 100;
        document.getElementById('floor-access-price-value').innerHTML = Math.round(floor_assess_price *
            100) / 100;
        document.getElementById('job-details-price-value').innerHTML = Math.round(job_details_price *
            100) / 100;
        // Set value to insurance_value text field
        if (insurance_enabled == 1) {
            document.getElementById('insurance_value').value = insurance_value;
        }
    }

    // Update the moving fields as per the service type selected
    function updateMovingFormFields() {

        // No of Rooms div
        if (no_of_room_enabled == 1) {
            $("#no_of_rooms_div").removeClass("d-none");
        } else {
            $("#no_of_rooms_div").addClass("d-none");
        }

        // Floor Plan div
        if (floor_plan_enabled == 1) {
            $("#floor_plan_div").removeClass("d-none");
        } else {
            $("#floor_plan_div").addClass("d-none");
        }

        // Floor Access div
        if (floor_assess_enabled == 1) {
            $("#floor_assess_div").removeClass("d-none");
        } else {
            $("#floor_assess_div").addClass("d-none");
        }

        // Job Details div
        if (job_details_enabled == 1) {
            $("#job_details_div").removeClass("d-none");
        } else {
            $("#job_details_div").addClass("d-none");
        }

        document.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
            checkbox.required = false;
        });

        // Moving Details div
        if (moving_details_enabled == 1) {
            $("#moving_details_div").removeClass("d-none");
        } else {
            $("#moving_details_div").addClass("d-none");
        }

    }
</script>
