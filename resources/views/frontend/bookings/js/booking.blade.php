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

    // $secureship_fee_percentage
    var secureship_fee_percentage = {{ $secureship_fee_percentage ? $secureship_fee_percentage : 0 }};

    // Store $prioritySettings to JS array
    var prioritySettings = {!! json_encode($prioritySettings) !!};
    var selectedPriorityID = prioritySettings[0].id;
    console.log('Selected Priority ID: ' + selectedPriorityID);
    var newUpdatedPriority = [];

    // Empty array for secureship_packages
    var secureshipPackages = [];

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
    var selectedServiceTypeID = {{ request()->get('serviceTypeID') ? request()->get('serviceTypeID') : 1 }};

    // Store $serviceCategories to JS array
    var selectedServiceCategoryUuid = '';
    var selectedParcelTypeSecureshipEnable = false;
    var serviceCategories = {!! json_encode($serviceCategories) !!};
    if (serviceCategories.length > 0) {
        selectedServiceCategoryUuid = serviceCategories[0].uuid;
        selectedParcelTypeSecureshipEnable = serviceCategories[0].is_secureship_enabled;
        volume_enabled = serviceCategories[0].volume_enabled;
        no_of_room_enabled = serviceCategories[0].no_of_room_enabled;
        floor_plan_enabled = serviceCategories[0].floor_plan_enabled;
        floor_assess_enabled = serviceCategories[0].floor_assess_enabled;
        job_details_enabled = serviceCategories[0].job_details_enabled;
        moving_details_enabled = serviceCategories[0].moving_details_enabled;

        // console.log('Selected category vehicle price is ' + serviceCategories[0]);
    }

    // selectedServiceCategoryUuid
    selectedServiceCategoryUuid =
        {{ request()->get('serviceCategoryID') ? request()->get('serviceCategoryID') : $serviceCategories[0]->uuid }};


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

    // Update the form data as per the service type
    function updateServiceFormData() {
        console.log('Update service form function called' + selectedParcelTypeSecureshipEnable);

        // console.log(selectedServiceType);
        if (selectedServiceType == 'moving') {
            // Hide and Show Div
            $("#deliveryPackageDetails").addClass("d-none");
            $("#movingPackageDetails").removeClass("d-none");
            $("#deliverySecureshipDetails").addClass("d-none");

            return true;

        }

        if (selectedParcelTypeSecureshipEnable == 1) {
            // Hide and Show Div
            $("#deliveryPackageDetails").addClass("d-none");
            $("#movingPackageDetails").addClass("d-none");
            $("#deliverySecureshipDetails").removeClass("d-none");
            return true;

        }

        // Hide and Show Div
        $("#deliveryPackageDetails").removeClass("d-none");
        $("#movingPackageDetails").addClass("d-none");
        $("#deliverySecureshipDetails").addClass("d-none");

        return true;
    }

    // Change setPriority
    function setPriority() {
        selectedPriorityID = document.getElementById('priorityDropdown').value;
        console.log('Selected Priority: ' + selectedPriorityID);
    }

    // setAddressBook
    function setAddressBook(id) {
        // get the value
        // let address_book = $('#address_book').val();
        console.log(id);

        // Get the address from address book id

        for (let i = 0; i < addresses.length; i++) {
            if (addresses[i].id == id) {
                addressBook = addresses[i];
                console.log(addressBook);
            }
        }

        // Set the address values to input fields

        // pickup_address
        $('#pickup_address').val(addressBook.pickup_address);
        $('#pickup_latitude').val(addressBook.pickup_latitude);
        $('#pickup_longitude').val(addressBook.pickup_longitude);

        // dropoff_address
        $('#dropoff_address').val(addressBook.dropoff_address);
        $('#dropoff_latitude').val(addressBook.dropoff_latitude);
        $('#dropoff_longitude').val(addressBook.dropoff_longitude);

        // receiver details
        $('#receiver_name').val(addressBook.receiver_name);
        $('#receiver_phone').val(addressBook.receiver_phone);
        $('#receiver_email').val(addressBook.receiver_email);


    }

    // Update priority as per the service type selected
    function updatePriority(selectedServiceType) {

        console.log('Update priority function called');

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

    // Parcel Type changes background color function
    function toggleBackground(id) {
        console.log('Toggle background function called');

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

        // updateFormFields
        updateFormFields();
    }


    // Update the Form Fields
    function updateFormFields() {
        console.log('Update field function called');

        // If selectedServiceCategoryUuid is empty
        if (selectedServiceCategoryUuid == '') {
            // Get from first service type from serviceCategories
            selectedServiceCategoryUuid = serviceCategories[0].uuid;
            volume_enabled = serviceCategories[0].volume_enabled;
            no_of_room_enabled = serviceCategories[0].no_of_room_enabled;
            floor_plan_enabled = serviceCategories[0].floor_plan_enabled;
            floor_assess_enabled = serviceCategories[0].floor_assess_enabled;
            job_details_enabled = serviceCategories[0].job_details_enabled;
            moving_details_enabled = serviceCategories[0].moving_details_enabled;
            selectedParcelTypeSecureshipEnable = serviceCategories[0].is_secureship_enabled;
            updateMovingFormFields();
        }

        // Get data on selected uuid
        for (let i = 0; i < serviceCategories.length; i++) {
            if (serviceCategories[i].uuid === selectedServiceCategoryUuid) {

                no_of_room_enabled = serviceCategories[i].no_of_room_enabled;
                floor_plan_enabled = serviceCategories[i].floor_plan_enabled;
                floor_assess_enabled = serviceCategories[i].floor_assess_enabled;
                job_details_enabled = serviceCategories[i].job_details_enabled;
                moving_details_enabled = serviceCategories[i].moving_details_enabled;
                volume_enabled = serviceCategories[i].volume_enabled;
                selectedParcelTypeSecureshipEnable = serviceCategories[i].is_secureship_enabled;
                updateMovingFormFields();

            }
        }


        // Update service form data
        updateServiceFormData();

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

        if (selectedParcelTypeSecureshipEnable == 1) {
            volume_enabled = 0;
        }

        // Show or Hide deliveryPackageDimensions based on volume_enabled
        console.log('Volume enabled:' + volume_enabled);
        if (volume_enabled == 1 && selectedServiceType == 'delivery') {
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

        // Add d-none class to calculatedAmountCart
        document.getElementById('calculatedAmountCart').classList.add('d-none');

    }

    // Update the moving fields as per the service type selected
    function updateMovingFormFields() {

        console.log('Update moving field function called');
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


    // Get Estimate on form submit
    document.getElementById('newBookingForm').onsubmit = function(event) {
        event.preventDefault(); // Prevent the default form submission
        calculateDeliveryEstimateUsingAjax();
        //alert('Booking Submitted');
    };

    // Collect formData and return
    function collectFormData() {
        // Create a form data and appnd data
        var formData = new FormData();

        // Add csrf token
        formData.append('_token', "{{ csrf_token() }}");

        // append selectedParcelTypeSecureshipEnable
        formData.append('selectedParcelTypeSecureshipEnable', selectedParcelTypeSecureshipEnable);

        // Append variables
        formData.append('selectedServiceType', selectedServiceType); // service type
        formData.append('selectedServiceTypeID', selectedServiceTypeID); // service type
        formData.append('selectedServiceCategoryUuid', selectedServiceCategoryUuid); // parcel type
        formData.append('priorityID', selectedPriorityID); // priority
        package_weight = $("input[name='package_weight']").val();
        formData.append('package_weight', package_weight); // package_weight
        package_length = $("input[name='package_length']").val();
        formData.append('package_length', package_length); // package_length
        package_width = $("input[name='package_width']").val();
        formData.append('package_width', package_width); // package_width
        package_height = $("input[name='package_height']").val();
        formData.append('package_height', package_height); // package_height
        // selectedNoOfRoomID
        selectedNoOfRoomID = $("select[name='no_of_rooms']").val();
        formData.append('selectedNoOfRoomID', selectedNoOfRoomID);
        selectedFloorPlanID = $("select[name='floor_plan']").val();
        formData.append('selectedFloorPlanID', selectedFloorPlanID); // selectedFloorPlanID
        selectedFloorAssessID = $("select[name='floor_assess']").val();
        formData.append('selectedFloorAssessID', selectedFloorAssessID); // selectedFloorAssessID
        // chheck if job details is enabled
        if (job_details_enabled == 1) {
            // get value from radio button field name job_details[]
            const checkboxes = document.querySelectorAll('input[name="job_details[]"]:checked');

            selectedJobDetailsID = [];

            checkboxes.forEach((checkbox) => {
                selectedJobDetailsID.push(checkbox.value);
            });
        }
        console.log('job_details_enabled ' + selectedJobDetailsID);
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
        @if (config('declare_package_value') == 'yes')
            package_value = document.querySelector('input[name="package_value"]').value;
        @endif
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

        // Add secureshipPackages array to formData as json object
        formData.append('secureshipPackages', JSON.stringify(secureshipPackages));

        // secureship_signature
        var secureship_signature = document.getElementById('secureship_signature').value;
        formData.append('secureship_signature', secureship_signature);

        // documents_only
        var documents_only = document.getElementById('documents_only').value;
        formData.append('documents_only', documents_only);

        // Receiver Details
        formData.append('receiver_name', document.getElementById('receiver_name').value);
        formData.append('receiver_phone', document.getElementById('receiver_phone').value);
        formData.append('receiver_email', document.getElementById('receiver_email').value);
        formData.append('delivery_note', document.getElementById('deliveryNote').value);

        return formData;
    }

    // Calculate amount to pay
    function calculateDeliveryEstimateUsingAjax() {

        // Collect formData
        var formData = collectFormData();


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
                // remove d-none class
                document.getElementById('calculatedAmountCart').classList.remove('d-none');
                if (response.status == 'success') {
                    if (response.deliveryMethod == 'secureship') {
                        secureshipDataLoad(response.data);
                    } else {
                        bookingEstimateDataLoad(response.data);
                    }
                } else {
                    // Load no data found message in calculatedAmountCartBody
                    document.getElementById('calculatedAmountCartBody').innerHTML = `
                            <p>${response.message}</p>
                        `;
                }
            },
            error: function(error) {
                console.log(error);
            }
        });


    }

    // secureshipDataLoad
    function secureshipDataLoad(data) {
        // calculatedAmountCartBody
        // calculatedAmountCartBody empty it first
        document.getElementById('calculatedAmountCartBody').innerHTML = '';
        // Check if data array is empty
        if (data.length == 0) {
            // Load no data found message in calculatedAmountCartBody
            document.getElementById('calculatedAmountCartBody').innerHTML = `
                    <p>No data found</p>
                `;
        } else {
            // Load data in calculatedAmountCartBody using loop thorugh each object
            var output = '';
            output += `
                        <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Carrier Code</th>
                                        <th>Service Level</th>
                                        <th>Est Delivery Time</th>
                                        <th>Billable Weight</th>
                                        <th>Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="bookingEstimateTableBody">
                                    <!-- Table rows will be appended here -->
                                
                `;
            $.each(data, function(key, value) {
                output += `
                        <tr>
                            <td>${value.carrierCode}</td>
                            <td>
                                <p>${value.selectedService}</p>
                                <p>${value.serviceName}</p>
                            </td>
                            <td>${value.deliveryTime.friendlyTime}</td>
                            <td>${value.billableWeight.value} ${value.billableWeight.units}</td>
                            <td>
                                <p>C$ ${(value.total + (value.total * (secureship_fee_percentage/100))).toFixed(2)}</p>
                            </td>
                            <td><button type="button" class="btn btn-primary btn-sm" onclick="bookService('${value.selectedService}')">Select</button></td>
                        </tr>
                    `;
            });
            output += `
                        </tbody>
                            </table>
                            {{-- Notification --}}
                            <p class="text-center">
                                Shipment estimate was calculated by Secureship on {{ \Carbon\Carbon::now()->format('Y-m-d') }} at
                                {{ \Carbon\Carbon::now()->format('H:i') }}
                                {{ \Carbon\Carbon::now()->format('e') }}
                            </p>
                            `;
            document.getElementById('calculatedAmountCartBody').innerHTML = output;

        }
    }

    // bookingEstimateDataLoad
    function bookingEstimateDataLoad(data) {
        // calculatedAmountCartBody

        // calculatedAmountCartBody empty it first
        document.getElementById('calculatedAmountCartBody').innerHTML = '';

        if (data) {
            // Load data in calculatedAmountCartBody
            var output = '';

            var pickupaddress = $('#pickup_address').val();
            var dropoff_address = $('#dropoff_address').val();
            var serviceType = $("#serviceType option:selected").text();
            var priority = $("#priorityDropdown option:selected").text();
            var deliveryNote = $('#deliveryNote').val();

            output += `
        <div class="row">
            <!-- Pickup Details -->
            <div class="col-md-6">
                <h5 class="text-primary mb-3 border-bottom pb-2">Booking Details</h5>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">Pickup Address:</span>
                    <span class="fw-bold" id="printPickupAddress">${pickupaddress ?? '___'}</span>
                </div>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">Dropoff:</span>
                    <span class="fw-bold" id="printDropoff">${dropoff_address ?? '___'}</span>
                </div>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">Service Type:</span>
                    <span class="fw-bold" id="serviceType">${data.serviceType ?? '___'}</span>
                </div>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">Service Category:</span>
                    <span class="fw-bold" id="serviceCategory">${data.serviceCategory ?? '___'}</span>
                </div>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">Priority:</span>
                    <span class="fw-bold" id="printPriority">${priority ?? '___'}</span>
                </div>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">Billable Weight:</span>
                    <span id="billable_weight" class="fw-bold">${data.billable_weight ? data.billable_weight + ' Kgs' : '___'} </span>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="col-md-6">
                <h5 class="text-primary mb-3 border-bottom pb-2">Payment Details</h5>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">Base Price:</span>
                    <span id="base-price" class="fw-bold">${data.base_price ? 'C$ ' + data.base_price : '___'}</span>
                </div>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">Extra Weight Price:</span>
                    <span id="weight-price" class="fw-bold">${data.weight_price ? 'C$ ' + data.weight_price : '___'} </span>
                </div>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">Priority Price:</span>
                    <span id="priority-price" class="fw-bold">${data.priority_price ? 'C$ ' + data.priority_price : '___'}</span>
                </div>
                `;
            // Check if serviceType is moving 
            if (data.serviceType == 'Moving') {
                output += `
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">Floor Plan Price:</span>
                    <span id="distance-price" class="fw-bold">${data.floor_plan_price ? 'C$ ' + data.floor_plan_price : '___'}</span>
                </div>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">Floor Assess Price:</span>
                    <span id="distance-price" class="fw-bold">${data.floor_assess_price ? 'C$ ' + data.floor_assess_price : '___'}</span>
                </div>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">Job Details Price:</span>
                    <span id="distance-price" class="fw-bold">${data.job_details_price ? 'C$ ' + data.job_details_price : '___'}</span>
                </div>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">No of Room Price:</span>
                    <span id="distance-price" class="fw-bold">${data.no_of_room_price ? 'C$ ' + data.no_of_room_price : '___'}</span>
                </div>
                `;
            }
            output += `  
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">Extra Distance Price:</span>
                    <span id="distance-price" class="fw-bold">${data.distance_price ? 'C$ ' + data.distance_price : '___'}</span>
                </div>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">Subtotal:</span>
                    <span id="sub_total" class="fw-bold">${data.sub_total ? 'C$ ' + data.sub_total : '___'}</span>
                </div>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">GST/PST Charges:</span>
                    <span id="tax_price" class="fw-bold">${data.tax_price ? 'C$ ' + data.tax_price : '___'}</span>
                </div>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">Total:</span>
                    <span id="amountToPay" class="fw-bold">${data.amountToPay ? 'C$ ' + data.amountToPay : '___'}</span>
                </div>
            </div>
            <div class="col-md-12 mt-3 d-flex justify-content-end">
                <button type="button" class="btn btn-primary" onclick="bookService('2 Point')">Create Booking</button>
            </div>
        </div>
    `;

            document.getElementById('calculatedAmountCartBody').innerHTML = output;
        } else {
            document.getElementById('calculatedAmountCartBody').innerHTML = `
        <p>No data found</p>
    `;
        }

    }


    // bookService
    function bookService(selectedService) {
        // alert(selectedService);

        // Collect formData
        var formData = collectFormData();

        // add csrf token

        formData.append('_token', '{{ csrf_token() }}');

        // append selectedSecureshipService
        formData.append('selectedSecureshipService', selectedService);

        // pickup_address
        formData.append('pickup_address', document.getElementById('pickup_address').value);
        // dropoff_address
        formData.append('dropoff_address', document.getElementById('dropoff_address').value);

        let base_url = '{{ url('/') }}';

        console.log(formData);

        // POST AJAX Call to /client/booking/store

        $.ajax({
            url: base_url + '/client/booking/store',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log(response);
                if (response.success == true) {
                    window.location.href = base_url + '/client/booking/payment/' + response.data.id;
                }
            },
            error: function(error) {
                alert(error);
                console.log(error);
            }
        });



        return false;
    }

    // toggleInsurance
    function toggleInsurance() {
        // get the value
        let insurance_enabled = $('#insurance_enabled').val();
        if (insurance_enabled == 'yes') {
            $('#insurance_value_div').removeClass('d-none');
        } else {
            $('#insurance_value_div').addClass('d-none');
        }

        // calculateInsurance
        calculateInsurance();

    }

    // calculateInsurance
    function calculateInsurance() {
        var insurance_value = 0;
        // Get package value
        let package_value = document.querySelector('input[name="package_value"]').value;
        if (package_value == '' || package_value == 0) {
            insurance_value = 0;
            // Set to insurance value
            document.getElementById('insurance_value').value = insurance_value;
            return false;
        }

        // Get insurance value from ajax call
        let base_url = '{{ url('/') }}';

        $.ajax({
            url: base_url + '/estimate/insurance',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                package_value: package_value
            },
            success: function(response) {
                console.log(response);
                if (response.success == true) {
                    // Set to insurance value
                    document.getElementById('insurance_value').value = response.data;
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
    }
</script>
