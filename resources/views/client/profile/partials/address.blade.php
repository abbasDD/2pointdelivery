<script>
    // Set default value to  country, state and city
    var selectedCountry = {{ old('country', $clientData['country'] ?? 0) }};
    var selectedState = {{ old('state', $clientData['state'] ?? 0) }};
    var selectedCity = {{ old('city', $clientData['city'] ?? 0) }};
</script>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Address</h5>
    </div>
    <form action="{{ route('client.update.address') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row">
                {{-- Suite --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="suite" class="form-label">Suite</label>
                        <input type="text" class="form-control" id="suite" name="suite"
                            value="{{ old('suite', $clientData['suite'] ?? '') }}" placeholder="Suite " required>
                        @error('suite')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                {{-- Street --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="street" class="form-label">Street</label>
                        <input type="text" class="form-control" id="street" name="street"
                            value="{{ old('street', $clientData['street'] ?? '') }}" placeholder="Street" required>
                        @error('street')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                {{-- Select Country --}}
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="country">Country</label>
                        <select class="form-control @error('country') is-invalid @enderror" id="country"
                            name="country" onchange="getStates(this.value)" required>
                            <option value="" selected disabled>Select Country</option>
                            @foreach ($addressData['countries'] as $country)
                                <option value="{{ $country->id }}"
                                    {{ old('country', $clientData['country'] ?? '') == $country->id ? 'selected' : '' }}>
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
                            @foreach ($addressData['clientStates'] as $state)
                                <option value="{{ $state->id }}"
                                    {{ old('state', $clientData['state'] ?? '') == $state->id ? 'selected' : '' }}>
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
                        <select class="form-control @error('city') is-invalid @enderror" id="city" name="city"
                            required>
                            <option value="" selected disabled>Select City</option>
                            @foreach ($addressData['clientCities'] as $city)
                                <option value="{{ $city->id }}"
                                    {{ old('city', $clientData['city'] ?? '') == $city->id ? 'selected' : '' }}>
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

                {{-- Zip Code --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="zip_code" class="form-label">Zip Code</label>
                        <input type="text" class="form-control" id="zip_code" name="zip_code"
                            value="{{ old('zip_code', $clientData['zip_code'] ?? '') }}" placeholder="Zip Code "
                            required>
                        @error('zip_code')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                {{-- Row End Here --}}
            </div>
            {{-- Button to Submit --}}
            <div class="row">
                <div class="col-md-12 text-right">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </form>
</div>



<script>
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
