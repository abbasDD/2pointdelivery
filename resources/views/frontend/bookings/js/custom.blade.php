<script>
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
        // Call the function to update the payment amount
        updatePaymentAmount();
    }


    // Udpate the categories as per the service type selected

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
        // Update form data as per the service type
        updateServiceFormData();
        // console.log(serviceType);
        var url =
            '{{ route('fetch.service.categories') }}' +
            '?serviceType=' + serviceType; // Replace 'fetch.service.categories' with your actual route name
        // var formData = new FormData();
        // formData.append('serviceType', serviceType);

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
                    categoryDiv.classList.add('col-md-4');
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
                    console.log('Selected Parcel UUID: is ' + data[0].vehicle_price_type);
                    vehicle_price = data[0].vehicle_price;
                    vehicle_price_type = data[0].vehicle_price_type;
                    moving_price_type = data[0].moving_price_type;

                    toggleBackground(selectedServiceCategoryUuid);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }



    // Get estimate from https://secureship.ca/ship/api/docs#tag/Carriers/operation/Carriers_CalculateRates
    var apiKey = '0226b62a-f112-4d22-a8fc-d05b67a38e26'; // Replace 'YOUR_API_KEY' with your actual API key

    // Function to call the API
    function getEstimatedFees() {

        // Check if selected service category is empty
        if (!selectedServiceType) {
            alert('Please select a service type');
            return;
        }
        // Check if selected parcel type is empty
        if (!selectedServiceCategoryUuid) {
            alert('Please select a parcel type');
            return;
        }
        // Find the selected parcel type from serviceCategories
        var selectedParcelTypeSecureshipEnable = false;
        for (let i = 0; i < serviceCategories.length; i++) {
            if (serviceCategories[i].uuid == selectedServiceCategoryUuid) {
                selectedParcelTypeSecureshipEnable = serviceCategories[i].is_secureship_enabled;
                volume_enabled = serviceCategories[i].volume_enabled;

                no_of_room_enabled = serviceCategories[i].no_of_room_enabled;
                floor_plan_enabled = serviceCategories[i].floor_plan_enabled;
                floor_assess_enabled = serviceCategories[i].floor_assess_enabled;
                job_details_enabled = serviceCategories[i].job_details_enabled;
                updateMovingFormFields();
            }
        }

        console.log(selectedParcelTypeSecureshipEnable);

        // Submit Form
        event.preventDefault();

        // Get all fields data from newBookingForm
        var newBookingForm = document.getElementById('newBookingForm');
        var formData = new FormData(newBookingForm);

        // Add additional fields
        formData.append('service_type_id', parseInt($("select[name='serviceType']").val()));
        formData.append('priority_setting_id', parseInt($("select[name='priority']").val()));
        formData.append('service_category_id', selectedServiceCategoryUuid);
        formData.append('total_price', $("#amount-to-pay-value").text());
        formData.append('booking_type', selectedServiceType);
        // Payment details
        // formData.append('base_price', payment_base_price);
        formData.append('distance', distance_in_km);
        formData.append('base_distance', payment_base_distance);
        formData.append('extra_distance_price', payment_extra_distance_price);
        formData.append('weight', calculated_weight);
        formData.append('base_weight', payment_base_weight);
        formData.append('extra_weight_price', payment_extra_weight_price);

        // Payment fields
        formData.append('base_price', Math.round(base_price * 100) / 100);
        formData.append('distance_price', Math.round(distance_price * 100) / 100);
        formData.append('vehicle_price_value', Math.round(vehicle_price_value * 100) / 100);
        formData.append('priorityPriceValue', Math.round(priorityPriceValue * 100) / 100);
        formData.append('weight_price', Math.round(weight_price * 100) / 100);
        formData.append('total_price', Math.round(amountToPay * 100) / 100);


        // Calculate total price

        // formData.append('total_price', payment_total_price);

        // Remove some data
        // formData.delete('serviceType');
        // formData.delete('priority');

        // Stringify the form data
        // formData = JSON.stringify(Object.fromEntries(formData));

        console.log(formData);

        // return false;

        // Append csrf token
        // formData.append('_token', '{{ csrf_token() }}');

        // console.log(formData);

        let base_url = '{{ url('/') }}';

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
                console.log(error);
            }
        });



        return false;

    }

    // Update the form data as per the service type
    function updateServiceFormData() {
        // console.log(selectedServiceType);
        if (selectedServiceType == 'moving') {
            // Hide and Show Div
            $("#deliveryPackageDetails").addClass("d-none");
            $("#movingPackageDetails").removeClass("d-none");

            // Add and Remove required attribute
            $("#movingPackageDetails input").prop("required", true);
            $("#deliveryPackageDetails input").prop("required", false);



            // Hide and Show Prices
            $(".calculated-amount .moving").removeClass("d-none");
            $(".calculated-amount .delivery").addClass("d-none");
        } else {
            // Hide and Show Div
            $("#deliveryPackageDetails").removeClass("d-none");
            $("#movingPackageDetails").addClass("d-none");

            // Add and Remove required attribute
            $("#movingPackageDetails input").prop("required", false);
            $("#deliveryPackageDetails input").prop("required", true);

            // Hide and Show Prices
            $(".calculated-amount .moving").addClass("d-none");
            $(".calculated-amount .delivery").removeClass("d-none");
        }
    }


    // Call window.onload function
    window.onload = function() {
        // Call the function
        updateServiceFormData();
        toggleBackground(selectedServiceCategoryUuid);
    }
    // Update the payment card
    // updatePaymentAmount();
    // Select the selected parcel uuid

    // Redirect to login page
    function redirectToLogin() {
        // Store form data in local storage
        // storeFormDataLocalStorage();
        window.location.href = "{{ route('client.login') }}";
    }

    // Function to store form data in local storage
    function storeFormDataLocalStorage() {
        console.log('Function Called storeFormDataLocalStorage');
        // localStorage.setItem('selectedServiceType', selectedServiceType);
        // console.log('Selected Service Type in Local Storage: ' + localStorage.getItem('selectedServiceType'));
    }


    // Calculate amount to pay
    function amountToPayCalculation() {
        // Calculate distance_price
        // Check if distance is greater then base_distance
        if (distance_in_km > parseFloat(payment_base_distance)) {
            distance_price = (distance_in_km - parseFloat(
                payment_base_distance)) * parseFloat(payment_extra_distance_price);
        } else {
            distance_price = 0;
        }

        // Calculate weight_price
        // if weight is greater then base_weight
        if (parseFloat(calculated_weight) > parseFloat(payment_base_weight)) {
            weight_price = (calculated_weight - parseFloat(payment_base_weight)) * parseFloat(
                payment_extra_weight_price);
        } else {
            weight_price = 0;
        }

        // Convert to float
        base_price = parseFloat(base_price);
        priorityPriceValue = parseFloat(priorityPriceValue);
        vehicle_price_value = parseFloat(vehicle_price_value);
        weight_price = parseFloat(weight_price);
        helper_fee = parseFloat(helper_fee);

        console.log('Total Price is : ' + payment_extra_weight_price)

        return base_price + distance_price + weight_price + priorityPriceValue + vehicle_price_value;
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

        // Call the function updatePaymentAmount
        updatePaymentAmount();
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


        updateRoute();

    }

    function updateMovingPackageDetails() {
        // get value from select field name no_of_rooms
        selectedNoOfRoomID = $("select[name='no_of_rooms']").val();

        // get value from select field name floor_plan
        selectedFloorPlanID = $("select[name='floor_plan']").val();

        // get value from select field name floor_assess
        selectedFloorAssessID = $("select[name='floor_assess']").val();

        // get value from radio button field name job_details[]
        const checkboxes = document.querySelectorAll('input[name="job_details[]"]:checked');

        selectedJobDetailsID = [];

        checkboxes.forEach((checkbox) => {
            selectedJobDetailsID.push(checkbox.id);
        });

        console.log('Selected Job Details ID: ' + selectedFloorAssessID);

    }
</script>
