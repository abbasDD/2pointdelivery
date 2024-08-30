<script>
    // Set default value to  country, state and city
    var selectedCountry = {{ old('country', $helperData['country'] ?? 0) }};
    var selectedState = {{ old('state', $helperData['state'] ?? 0) }};
    var selectedCity = {{ old('city', $helperData['city'] ?? 0) }};
</script>
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="front_image">ID Card Front:</label>
        <div class="form-group card clickable-card text-center" data-toggle="tooltip" data-placement="bottom"
            title="Click to upload ID card front image">
            @if (!isset($kycDetails['front_image']))
                <i class="fa fa-camera fa-3x m-3 camera-icon"></i>
            @endif
            <input type="file" class="d-none" id="front_image" name="front_image" accept="image/*">
            <img src="{{ isset($kycDetails['front_image']) ? asset('/images/kyc/' . $kycDetails['front_image']) : '' }}"
                class="img-fluid mx-auto d-block p-2 selected-image h-100" alt="ID Card front">
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <label for="back_image">ID Card Back:</label>
        <div class="form-group card clickable-card text-center" data-toggle="tooltip" data-placement="bottom"
            title="Click to upload ID card back image">
            @if (!isset($kycDetails['back_image']))
                <i class="fa fa-camera fa-3x m-3 camera-icon"></i>
            @endif
            <input type="file" class="d-none" id="back_image" name="back_image" accept="image/*">
            <img src="{{ isset($kycDetails['back_image']) ? asset('/images/kyc/' . $kycDetails['back_image']) : '' }}"
                class="img-fluid mx-auto d-block p-2 selected-image h-100" alt="ID Card back">
        </div>
    </div>

    @if (!isset($kycDetails))
        <div class="col-md-6 mb-3">
            <div class="form-group">
                <label for="kyc_type_id">KYC Type:</label>
                <select class="form-control" id="kyc_type_id" name="kyc_type_id" required>
                    <option value="" disabled selected>Select ID Card Type</option>
                    @foreach ($kycTypes as $kycType)
                        <option value="{{ $kycType->id }}"
                            {{ old('kyc_type_id', $kycDetails->kyc_type_id ?? '') == $kycType->id ? 'selected' : '' }}>
                            {{ $kycType->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @else
        <div class="col-md-6 mb-3">
            <div class="form-group">
                <label for="kyc_type_id">KYC Type:</label>
                {{-- Read only Name of KYC Type --}}
                <input class="form-control" type="text" id="kyc_type_id" name="kyc_type_id"
                    value="{{ old('kyc_type_id', $kycTypes->name ?? '') }}" readonly>
            </div>
        </div>
    @endif

    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label for="id_number">ID Number:</label>
            <input class="form-control" type="text" id="id_number" name="id_number" placeholder="Enter ID Number"
                value="{{ old('id_number', $kycDetails->id_number ?? '') }}" required>
        </div>
    </div>

    {{-- Select Country --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="country">Country</label>
            <select class="form-control @error('country') is-invalid @enderror" id="country" name="country"
                onchange="getStates(this.value)" required>
                <option value="" selected disabled>Select Country</option>
                @foreach ($addressData['countries'] as $country)
                    <option value="{{ $country->id }}"
                        {{ old('country', $kycDetails['country'] ?? '') == $country->id ? 'selected' : '' }}>
                        {{ $country->name }}</option>
                @endforeach
            </select>
            @error('country')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Select State --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="state">State</label>
            <select class="form-control @error('state') is-invalid @enderror" id="state" name="state"
                onchange="getCities(this.value)" required>
                <option value="" selected disabled>Select State</option>
                @foreach ($addressData['selectedStates'] as $state)
                    <option value="{{ $state->id }}"
                        {{ old('state', $kycDetails['state'] ?? '') == $state->id ? 'selected' : '' }}>
                        {{ $state->name }}</option>
                @endforeach
            </select>
            @error('state')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Select City --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="city">City</label>
            <select class="form-control @error('city') is-invalid @enderror" id="city" name="city" required>
                <option value="" selected disabled>Select City</option>
                @foreach ($addressData['selectedCities'] as $city)
                    <option value="{{ $city->id }}"
                        {{ old('city', $kycDetails['city'] ?? '') == $city->id ? 'selected' : '' }}>
                        {{ $city->name }}</option>
                @endforeach
            </select>
            @error('city')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Issue Date --}}
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label for="issue_date">Issue Date:</label>
            <input class="form-control" type="text" id="issue_date" name="issue_date"
                value="{{ old('issue_date', isset($kycDetails->issue_date) ? \Carbon\Carbon::parse($kycDetails->issue_date)->format(config('date_format', 'd-m-Y')) : '') }}"
                placeholder="Enter Issue Date" required>
            @error('issue_date')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <script>
        var dateFormat = convertDateFormat("<?php echo config('date_format') ?? 'd-m-Y'; ?>");

        // Initialize the date picker with the correct format
        $('#issue_date').datepicker({
            dateFormat: dateFormat
        });
    </script>

    {{-- Expiry Date --}}
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label for="expiry_date">Expiry Date:</label>
            <input class="form-control" type="text" id="expiry_date" name="expiry_date"
                value="{{ old('expiry_date', isset($kycDetails->expiry_date) ? \Carbon\Carbon::parse($kycDetails->expiry_date)->format(config('date_format', 'd-m-Y')) : '') }}"
                placeholder="Enter Expiry Date" required>
            @error('expiry_date')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <script>
        var dateFormat = convertDateFormat("<?php echo config('date_format') ?? 'd-m-Y'; ?>");

        // Initialize the date picker with the correct format
        $('#expiry_date').datepicker({
            dateFormat: dateFormat
        });
    </script>


    <div class="col-md-12 mb-3">
        <div class="form-group" style="float: right">
            <button class="btn btn-primary" type="submit">{{ isset($kycDetails) ? 'Update' : 'Add' }}</button>
        </div>
    </div>

</div>


<script>
    const cardClickable = document.querySelectorAll('.clickable-card');

    cardClickable.forEach(card => {
        const inputField = card.querySelector('input[type=file]');
        const existingImage = card.querySelector('img').getAttribute('src');

        card.addEventListener('click', function() {
            inputField.click();
        });

        inputField.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                const cameraIcon = card.querySelector('.camera-icon');

                reader.onload = function(e) {
                    card.querySelector('img').src = e.target.result;
                    cameraIcon.style.display = 'none'; // Hide the camera icon
                }

                reader.readAsDataURL(this.files[0]);
            }
        });
    });

    // Function to check if there's any file selected
    function checkFilesSelected() {
        const fileInputs = document.querySelectorAll('.clickable-card input[type=file]');

        for (let i = 0; i < fileInputs.length; i++) {
            if (fileInputs[i].files.length === 0) {
                return false;
            }
        }

        return true;
    }

    // Event listener for form submission
    document.querySelector('form').addEventListener('submit', function(e) {
        if (!checkFilesSelected()) {
            e.preventDefault(); // Prevent form submission
            alert('Please select both ID card front and back images.');
        }
    });


    // Get states
    function getStates(countryId) {
        console.log(countryId);

        // Get request to get states
        let baseUrl = "{{ url('/') }}";
        let url = `${baseUrl}/address/states/${countryId}`;

        // AJAX get request
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                console.log(response);
                // $('#state').html(response);
                // Empty state select
                $('#state').empty();
                $('#city').empty();
                // Add option Select State
                $('#state').append(`<option value="" disabled selected>Select State</option>`);
                $('#city').append(`<option value="" disabled selected>Select City</option>`);
                // Load to state select as options using loop
                response.forEach(function(state) {
                    if (state.id == selectedState) {
                        $('#state').append(
                            `<option value="${state.id}" selected>${state.name}</option>`);
                    } else {
                        $('#state').append(`<option value="${state.id}">${state.name}</option>`);
                    }

                })
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    // // Get cities
    function getCities(stateId) {
        console.log(stateId);
        // Get request to get cities
        let baseUrl = "{{ url('/') }}";
        let url = `${baseUrl}/address/cities/${stateId}`;

        // AJAX get request
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                console.log(response);

                // Empty city select
                $('#city').empty();
                // Add option Select City
                $('#city').append(`<option value="" disabled selected>Select City</option>`);
                // Load to city select as options using loop
                response.forEach(function(city) {
                    if (city.id == selectedCity) {
                        $('#city').append(
                            `<option value="${city.id}" selected>${city.name}</option>`);
                    } else {
                        $('#city').append(`<option value="${city.id}">${city.name}</option>`);
                    }
                })
            }
        })
    }
</script>
