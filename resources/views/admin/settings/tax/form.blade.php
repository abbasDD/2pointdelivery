{{-- Country Form --}}
<div class="row">
    {{-- Select Country --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="country_id">Country</label>
            <select class="form-control @error('country') is-invalid @enderror" id="country_id" name="country_id"
                onchange="getStates(this.value)" required>
                <option value="" selected disabled>Select Country</option>
                @foreach ($countries as $country)
                    <option value="{{ $country->id }}"
                        {{ old('country_id', $taxCountry['country_id'] ?? '') == $country->id ? 'selected' : '' }}>
                        {{ $country->name }}</option>
                @endforeach
            </select>
            @error('country_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Select State --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="state_id">State</label>
            <select class="form-control @error('state_id') is-invalid @enderror" id="state_id" name="state_id"
                onchange="getCities(this.value)" required>
                <option value="" selected disabled>Select State</option>
            </select>
            @error('state_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Select City --}}
    {{-- <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="city_id">City</label>
            <select class="form-control @error('city_id') is-invalid @enderror" id="city_id" name="city_id" required>
                <option value="" selected disabled>Select City</option>
            </select>
            @error('city_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div> --}}

    {{-- GST Tax Rate --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="gst_rate">GST Rate</label>
            <div class="input-group">
                <input type="text" class="form-control @error('gst_rate') is-invalid @enderror" id="gst_rate"
                    name="gst_rate"
                    value="{{ old('gst_rate', isset($taxCountry['gst_rate']) ? number_format($taxCountry['gst_rate'], 2) : '') }}"
                    placeholder="Enter GST Rate"
                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
                <span class="input-group-text text-uppercase" id="gst_rate">%</span>
            </div>

            @error('gst_rate')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- PST Tax Rate --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="pst_rate">PST Rate</label>
            <div class="input-group">
                <input type="text" class="form-control @error('pst_rate') is-invalid @enderror" id="pst_rate"
                    name="pst_rate" value="{{ old('pst_rate', $taxCountry['pst_rate'] ?? '') }}"
                    placeholder="Enter PST Rate"
                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
                <span class="input-group-text text-uppercase" id="pst_rate">%</span>
            </div>
            @error('pst_rate')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- HST Tax Rate --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="hst_rate">HST Rate</label>
            <div class="input-group">
                <input type="text" class="form-control @error('hst_rate') is-invalid @enderror" id="hst_rate"
                    name="hst_rate" value="{{ old('hst_rate', $taxCountry['hst_rate'] ?? '') }}"
                    placeholder="Enter HST Rate"
                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
                <span class="input-group-text text-uppercase" id="hst_rate">%</span>
            </div>
            @error('hst_rate')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Status --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="is_active">Status</label>
            <select class="form-control @error('is_active') is-invalid @enderror" id="is_active" name="is_active"
                required>
                <option value="">Select Status</option>
                <option value="1" {{ old('is_active', $taxCountry['is_active'] ?? '') == 1 ? 'selected' : '' }}>
                    Active
                </option>
                <option value="0" {{ old('is_active', $taxCountry['is_active'] ?? '') == 0 ? 'selected' : '' }}>
                    Inactive
                </option>
            </select>
            @error('is_active')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Submit Button --}}
    <div class="col-md-12 text-right">
        <button type="submit" class="btn btn-primary btn-block">
            {{ isset($taxCountry) ? 'Update' : 'Submit' }}
        </button>
    </div>
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
                $('#state_id').empty();
                // Add option Select State
                $('#state_id').append(`<option value="" disabled selected>Select State</option>`);
                // Load to state select as options using loop
                response.forEach(function(state) {
                    $('#state_id').append(`<option value="${state.id}">${state.name}</option>`);
                })
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    // // Get cities
    // function getCities(stateId) {
    //     console.log(stateId);
    //     // Get request to get cities
    //     let baseUrl = "{{ url('/') }}";
    //     let url = `${baseUrl}/address/cities/${stateId}`;

    //     // AJAX get request
    //     $.ajax({
    //         url: url,
    //         type: 'GET',
    //         success: function(response) {
    //             console.log(response);

    //             // Empty city select
    //             $('#city_id').empty();
    //             // Add option Select City
    //             $('#city_id').append(`<option value="" disabled selected>Select City</option>`);
    //             // Load to city select as options using loop
    //             response.forEach(function(city) {
    //                 $('#city_id').append(`<option value="${city.id}">${city.name}</option>`);
    //             })
    //         }
    //     })
    // }

    // Load on page load
    $(document).ready(function() {
        getStates("{{ old('country_id', $taxCountry['country_id'] ?? '') }}");
        // getCities("{{ old('state_id', $taxCountry['state_id'] ?? '') }}");
    })
</script>
