<script>
    // Base URL
    const base_url = "{{ url('/') }}";

    // Define some javascript variables to be used in JS
    var csrf_token = "{{ csrf_token() }}";
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

        // console.log('Selected category vehicle price is ' + serviceCategories[0]);
    }


    // Update the categories as per the service type selected

    function parcelCategoriesDiv() {
        // console.log('Function Called');
        var serviceType = document.querySelector('select[name="serviceType"]').value;
        // Get type of ServiceType from serviceTypes
        var serviceTypes = {!! json_encode($serviceTypes) !!};
        // console.log(serviceTypes);
        if (serviceTypes.length > 0) {
            for (let i = 0; i < serviceTypes.length; i++) {
                if (serviceTypes[i].id == serviceType) {
                    selectedServiceType = serviceTypes[i].type;
                    selectedServiceTypeID = serviceTypes[i].id;
                    // console.log('Service Type: ' + serviceTypes[i].type);
                }
            }
        }
        // Update priority as per the service type selected
        updatePriority(selectedServiceType);

        // console.log('Selected Service Type: ' + selectedServiceType);

        var url =
            '{{ route('fetch.service.categories') }}' +
            '?serviceType=' + serviceType;

        fetch(url, {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => {
                // Update parcel categories div based on received data
                var parcelCategoriesDiv = document.getElementById('parcelCategoriesDiv');
                parcelCategoriesDiv.innerHTML = ''; // Clear previous content
                // Store updated service categories in serviceCategories variable
                serviceCategories = data;
                // console.log(serviceCategories);
                // Loop through each category and create a new div
                data.forEach(category => {
                    var categoryDiv = document.createElement('div');
                    categoryDiv.classList.add('col-md-3');
                    categoryDiv.innerHTML = `
            <div class="d-flex align-items-center cursor-pointer" onclick="toggleBackground('${category.uuid}')">
                <div class="me-3">
                    <span class="form-check-input" style="display: none;">
                        <input type="radio" class="form-check-input" name="parcelType"
                            value="${category.uuid}">
                    </span>
                </div>
                <div class="text-center parcel-type w-100"
                    id="${category.uuid}">
                    <h5 class="mb-1">${category.name}</h5>
                    <p class="fs-xxs">${category.description}</p>
                </div>
            </div>
        `;
                    parcelCategoriesDiv.appendChild(categoryDiv);
                });

                // Get first item uuid and set it as default
                if (data.length > 0) {
                    selectedServiceCategoryUuid = data[0].uuid;
                    console.log('Selected Parcel UUID: is ' + selectedServiceCategoryUuid);

                    toggleBackground(selectedServiceCategoryUuid);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    // Parcel Type changes background color function
    function toggleBackground(id) {
        var divs = document.querySelectorAll('.parcel-type');
        divs.forEach(function(div) {
            if (div.id === id) {
                div.classList.add('active-parcel');
            } else {
                div.classList.remove('active-parcel');
            }
        });

        // console.log('Here id is:' + id);
        selectedServiceCategoryUuid = id;
    }

    // Update priority as per the service type selected
    function updatePriority(selectedServiceType) {
        // Get selected service type
        // console.log(prioritySettings);
        newUpdatedPriority = [];
        // Get priority settings from list only which has type is equal to selected service type
        for (let i = 0; i < prioritySettings.length; i++) {
            if (prioritySettings[i].type == selectedServiceType) {
                newUpdatedPriority.push(prioritySettings[i]);
            }
        }
        // console.log(newUpdatedPriority);
        if (newUpdatedPriority.length > 0) {
            selectedPriorityID = newUpdatedPriority[0].id;
        }

        // Populate the priority dropdown
        if (newUpdatedPriority.length > 0) {
            var priorityDropdown = document.getElementById('priorityDropdown');
            // Empty the dropdown
            priorityDropdown.innerHTML = '';
            newUpdatedPriority.forEach(priority => {
                var option = document.createElement('option');
                option.value = priority.id;
                option.text = priority.name;
                priorityDropdown.appendChild(option);
            });
        }

    }


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

    }

    // Get Estimate on form submit
    document.getElementById('newBookingForm').onsubmit = function(event) {
        event.preventDefault(); // Prevent the default form submission
        // getTrackingDetail();
        alert('Booking Submitted');
    };




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

        // Append map variables
        formData.append('pickup_latitude', parseFloat(document.getElementById('pickup_latitude').value ||
            defaultPickupLat)); // pickup_latitude
        formData.append('pickup_longitude', parseFloat(document.getElementById('pickup_longitude').value ||
            defaultPickupLng)); // pickup_longitude
        formData.append('dropoff_latitude', parseFloat(document.getElementById('dropoff_latitude').value ||
            defaultDropLat)); // dropoff_latitude
        formData.append('dropoff_longitude', parseFloat(document.getElementById('dropoff_longitude').value ||
            defaultDropLng)); // dropoff_longitude

        // booking date
        booking_date = document.querySelector('input[name="booking_date"]').value;
        formData.append('booking_date', booking_date);
        // booking time
        booking_time = document.querySelector('input[name="booking_time"]').value;
        formData.append('booking_time', booking_time);

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
