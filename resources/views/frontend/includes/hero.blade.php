{{-- Hero Section  --}}
<section id="pagehero" style="background-image: url({{ asset('frontend/images/header/header-bg.png') }})">
    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-md-6 content d-none d-md-block">
                <h3 class="mb-2 text-white">{{ __('frontend.header_title') }}</h3>
                <p class="text-white">{{ __('frontend.header_subtitle') }}</p>
                {{-- <a href="{{ route('helper.register') }}" class="btn btn-primary">{{ __('frontend.join_as_helper') }}</a> --}}
                {{-- Redirect to Helper Register --}}
                <div class="arrow-button">
                    <a href="{{ route('helper.register') }}" class="text-white">
                        <i class="fas fa-long-arrow-alt-right mr-2"></i> {{ __('frontend.join_as_helper') }}
                    </a>
                </div>
            </div>
            @if (count($serviceTypes) > 0)
                {{-- Track or Book Order --}}
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-pills mb-3 gap-3" id="hero-area-tab" role="tablist">
                                {{-- Booking Tab --}}
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link px-5 active" id="pills-booking-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-booking" type="button" role="tab"
                                        aria-controls="pills-booking" aria-selected="true">Booking</button>
                                </li>
                                {{-- Tracking Tab --}}
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link px-5" id="pills-tracking-tab" data-bs-toggle="pill"
                                        data-bs-target="#pills-tracking" type="button" role="tab"
                                        aria-controls="pills-tracking" aria-selected="false">Tracking</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="hero-area-tabContent">
                                {{-- Booking Tab Content --}}
                                <div class="tab-pane fade show active" id="pills-booking" role="tabpanel"
                                    aria-labelledby="pills-booking-tab">
                                    <div class="heading">
                                        <h2>Deliver with Us</h2>
                                    </div>
                                    {{-- Booking Form --}}
                                    <form id="bookingForm" method="GET">
                                        <div class="row">
                                            <p class="text-danger" id="bookingError"></p>
                                            {{-- Select Service Type --}}
                                            <div class="mb-3">
                                                <select id="serviceTypeID" class="form-control" name="serviceType"
                                                    onchange="updateServiceCategoryList()" required>
                                                    <option value="" disabled>Select Service</option>
                                                    @foreach ($serviceTypes as $serviceType)
                                                        <option value="{{ $serviceType->id }}">
                                                            {{ $serviceType->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- Select Service Category --}}
                                            <div class="mb-3">
                                                <select id="serviceCategoryID" class="form-control"
                                                    name="serviceCategory" required>
                                                    <option value="" disabled>Select Category</option>
                                                </select>
                                            </div>
                                            {{-- Pickup Location --}}
                                            <div class="mb-3">
                                                <input id="pickupLocation" class="form-control" type="text"
                                                    name="pickup_address"
                                                    placeholder="{{ __('frontend.move_and_deliver.pickup_location') }}"
                                                    required>
                                                <input type="hidden" id="pickup_latitude" name="pickup_latitude" />
                                                <input type="hidden" id="pickup_longitude" name="pickup_longitude" />
                                            </div>
                                            {{-- Delivery Location --}}
                                            <div class="mb-3">
                                                <input id="deliveryLocation" class="form-control" type="text"
                                                    name="dropoff_address"
                                                    placeholder="{{ __('frontend.move_and_deliver.delivery_location') }}"
                                                    required>
                                                <input type="hidden" id="dropoff_latitude" name="dropoff_latitude" />
                                                <input type="hidden" id="dropoff_longitude" name="dropoff_longitude" />
                                            </div>
                                            {{-- Advance Booking Options --}}
                                            <div class="mb-3 text-right">
                                                <a href="{{ route('newBooking') }}"
                                                    class="text-decoration-none text-primary">
                                                    Advance Booking Options
                                                </a>
                                            </div>
                                            {{-- Submit Button --}}
                                            <button type="submit" class="btn arrow-button w-100"><i
                                                    class="fas fa-long-arrow-alt-right"></i>
                                                {{ __('frontend.move_and_deliver.btn_text') }} </button>
                                        </div>
                                    </form>
                                </div>
                                {{-- Tracking Tab Content --}}
                                <div class="tab-pane fade" id="pills-tracking" role="tabpanel"
                                    aria-labelledby="pills-tracking-tab">
                                    <div class="heading">
                                        <h2>Track Your Booking</h2>
                                    </div>
                                    {{-- Tracking Form  --}}
                                    <form action="{{ route('trackBooking') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="mb-3">
                                                <input id="trackingCode" class="form-control" type="text"
                                                    name="tracking_code" placeholder="Enter Booking ID" required>
                                            </div>
                                            <p class="text-danger" id="trackingError"></p>
                                            <button type="submit" class="btn arrow-button w-100"><i
                                                    class="fas fa-long-arrow-alt-right"></i> Track Booking</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- Modal of Booking Estimated Price --}}
<div class="modal fade" id="bookingEstimateModal" tabindex="-1" aria-labelledby="bookingEstimateModalLabel"
    aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingEstimateModalLabel">Estimated Price</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div id="bookingEstimateModalBody" class="col-md-12 overflow-scroll">

                        {{-- Estimated Price Display here --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- Load JS of Map --}}
<script
    src="https://maps.googleapis.com/maps/api/js?key={{ config('google_map_api_key') ?? 'Your API Key' }}&libraries=places">
</script>
<script>
    // Call updateServiceCategoryList onload
    $(document).ready(function() {
        updateServiceCategoryList();
    });

    var secureship_fee_percentage = {{ $secureship_fee_percentage ? $secureship_fee_percentage : 0 }};


    var options = {
        componentRestrictions: {
            country: ["ca"]
        }
    };

    var pickupAutocomplete = new google.maps.places.Autocomplete(document.getElementById('pickupLocation'), options);
    var deliveryAutocomplete = new google.maps.places.Autocomplete(document.getElementById('deliveryLocation'),
        options);

    pickupAutocomplete.addListener('place_changed', function() {
        var place = pickupAutocomplete.getPlace();
        document.getElementById('pickup_latitude').value = place.geometry.location.lat();
        document.getElementById('pickup_longitude').value = place.geometry.location.lng();
    });
    deliveryAutocomplete.addListener('place_changed', function() {
        var place = deliveryAutocomplete.getPlace();
        document.getElementById('dropoff_latitude').value = place.geometry.location.lat();
        document.getElementById('dropoff_longitude').value = place.geometry.location.lng();
    });

    // updateServiceCategoryList
    function updateServiceCategoryList() {
        // Get service type id
        var serviceTypeId = $("#serviceTypeID").val();

        // fetch/service-categories
        var url = "{{ route('fetch.service.categories') }}";

        // AJAX call with serviceTypeID as parameter
        $.ajax({
            url: url,
            type: 'GET',
            data: {
                serviceType: serviceTypeId
            },
            success: function(response) {
                // console.log(response);
                // Update option list to serviceCategoryID
                $("#serviceCategoryID").empty();
                $("#serviceCategoryID").append(
                    '<option value="" disabled selected>Select Category </option>');
                // loop through response and add to option list
                $.each(response, function(key, value) {
                    $("#serviceCategoryID").append('<option value="' + value.uuid + '">' + value
                        .name + '</option>');
                });

            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    // Get Booking Estimate
    document.getElementById('bookingForm').onsubmit = function(event) {
        event.preventDefault(); // Prevent the default form submission
        getBookingEstimate();
    };

    // getBookingEstimate
    function getBookingEstimate() {
        // alert('Get Booking Estimate');
        document.getElementById('bookingError').innerHTML = '';
        // Your booking estimate logic here
        console.log('Booking Estimate:');
        // CSRF
        var token = $('meta[name="csrf-token"]').attr('content');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': token
            }
        });

        // AJAX Call to getBookingEstimate
        $.ajax({
            url: "{{ route('estimate.index') }}",
            type: 'POST',
            data: {
                selectedServiceTypeID: $('#serviceTypeID').val(),
                selectedServiceCategoryUuid: $('#serviceCategoryID').val(),
                pickupLocation: $('#pickupLocation').val(),
                pickup_latitude: $('#pickup_latitude').val(),
                pickup_longitude: $('#pickup_longitude').val(),
                deliveryLocation: $('#deliveryLocation').val(),
                dropoff_latitude: $('#dropoff_latitude').val(),
                dropoff_longitude: $('#dropoff_longitude').val(),
            },
            success: function(response) {
                console.log('Success:', response);
                if (response.status == 'error') {
                    document.getElementById('bookingError').innerHTML = response.message;
                    return false;
                }
                // open modal bookingEstimateModalBody
                // bookingEstimateModalBody empty it first
                document.getElementById('bookingEstimateModalBody').innerHTML = '';
                $('#bookingEstimateModal').modal('show');
                if (response.deliveryMethod == 'secureship') {
                    secureshipDataLoad(response.data);
                } else {
                    bookingEstimateDataLoad(response.data);
                }
                // handle success
            },
            error: function(xhr, status, error) {
                console.log('Error:', xhr.responseText);
                // handle error
            }
        });
    }

    // Get selected data from booking form
    function bookingFormData() {

        // Create a map to store form data
        var formData = new Map();
        // add form data to map
        formData.set('pickupLocation', document.getElementById('pickupLocation').value);
        formData.set('deliveryLocation', document.getElementById('deliveryLocation').value);
        formData.set('pickup_latitude', document.getElementById('pickup_latitude').value);
        formData.set('pickup_longitude', document.getElementById('pickup_longitude').value);
        formData.set('dropoff_latitude', document.getElementById('dropoff_latitude').value);
        formData.set('dropoff_longitude', document.getElementById('dropoff_longitude').value);
        formData.set('serviceTypeID', document.getElementById('serviceTypeID').value);
        formData.set('serviceCategoryID', document.getElementById('serviceCategoryID').value);
        // console.log(formData);
        return formData;
    }

    // secureshipDataLoad
    function secureshipDataLoad(data) {
        // base_url
        var base_url = '{{ url('/') }}';
        var formData = bookingFormData();
        // bookingEstimateModalBody
        // bookingEstimateModalBody empty it first
        document.getElementById('bookingEstimateModalBody').innerHTML = '';
        // Check if data array is empty
        if (data.length == 0) {
            // Load no data found message in bookingEstimateModalBody
            document.getElementById('bookingEstimateModalBody').innerHTML = `
                    <p>No data found</p>
                `;
        } else {
            // Load data in bookingEstimateModalBody using loop thorugh each object
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
                                <p>CAD ${(value.total + (value.total * (secureship_fee_percentage/100))).toFixed(2)}</p>
                            </td>
                            <td><a href="${base_url}/new-booking?serviceTypeID=${formData.get('serviceTypeID')}&serviceCategoryID=${formData.get('serviceCategoryID')}&pickupLocation=${formData.get('pickupLocation')}&deliveryLocation=${formData.get('deliveryLocation')}&pickup_latitude=${formData.get('pickup_latitude')}&pickup_longitude=${formData.get('pickup_longitude')}&dropoff_latitude=${formData.get('dropoff_latitude')}&dropoff_longitude=${formData.get('dropoff_longitude')}" class="btn btn-sm btn-primary">Select</a></td>
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
            document.getElementById('bookingEstimateModalBody').innerHTML = output;

        }
    }

    // bookingEstimateDataLoad
    function bookingEstimateDataLoad(data) {
        // bookingEstimateModalBody
        var base_url = '{{ url('/') }}';
        var formData = bookingFormData();
        console.log(formData);
        // bookingEstimateModalBody empty it first
        document.getElementById('bookingEstimateModalBody').innerHTML = '';

        if (data) {
            // Load data in bookingEstimateModalBody using loop thorugh each object
            var output = '';
            output += `
                        <table class="table table-striped">
                                <thead>
                                    <tr>
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

            output += `
                        <tr>
                            <td>
                                2 Point Delivery
                            </td>
                            <td>{{ date('Y-m-d') }} at {{ date('H:i') }}</td>
                            <td>${data.base_weight} Kgs</td>
                            <td>
                                <p>${data.amountToPay ?? '-'}</p>
                            </td>
                            <td><a href="${base_url}/new-booking?serviceTypeID=${formData.get('serviceTypeID')}&serviceCategoryID=${formData.get('serviceCategoryID')}&pickupLocation=${formData.get('pickupLocation')}&deliveryLocation=${formData.get('deliveryLocation')}&pickup_latitude=${formData.get('pickup_latitude')}&pickup_longitude=${formData.get('pickup_longitude')}&dropoff_latitude=${formData.get('dropoff_latitude')}&dropoff_longitude=${formData.get('dropoff_longitude')}" class="btn btn-sm btn-primary">Select</a></td>
                        </tr>
                    `;
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
            document.getElementById('bookingEstimateModalBody').innerHTML = output;
        } else {
            document.getElementById('bookingEstimateModalBody').innerHTML = `
                    <p>No data found</p>
                `;
        }
    }
</script>
