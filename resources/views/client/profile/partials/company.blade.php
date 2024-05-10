<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Company</h5>
    </div>
    <form action="{{ route('client.update.company') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            {{-- Company Logo --}}
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group mb-3">
                        <div class="image-selection">
                            <div class="mx-auto" style="max-width: 150px;">
                                <img id="company_logo_preview"
                                    src="{{ isset($clientCompanyData['company_logo']) && $clientCompanyData['company_logo'] !== null ? asset('images/company/' . $clientCompanyData['company_logo']) : asset('images/company/default.png') }}"
                                    alt="company_logo" class=" border w-100 p-3"
                                    onclick="document.getElementById('company_logo').click()">
                                <input type="file" name="company_logo" id="company_logo" class="d-none"
                                    accept="image/*">
                            </div>
                        </div>
                        @if ($errors->has('company_logo'))
                            <span class="invalid-feedback" role="alert">
                                <strong>Profile Image is required</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            {{-- Company --}}
            <div class="row">
                {{-- Company Alias --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="company_alias" class="form-label">Company Alias</label>
                        <input type="text" class="form-control" id="company_alias" name="company_alias"
                            value="{{ old('company_alias', $clientCompanyData['company_alias'] ?? '') }}"
                            placeholder="Company Alias" required>

                        @error('company_alias')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                {{-- Legal Name --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="legal_name" class="form-label">Legal Name</label>
                        <input type="text" class="form-control" id="legal_name" name="legal_name"
                            value="{{ old('legal_name', $clientCompanyData['legal_name'] ?? '') }}"
                            placeholder="Legal Name" required>

                        @error('legal_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                {{-- Industry --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="industry" class="form-label">Industry</label>
                        <input type="text" class="form-control @error('industry') is-invalid @enderror"
                            id="industry" name="industry" placeholder="Industry"
                            value="{{ old('industry', $clientCompanyData['industry'] ?? '') }}" required>
                        @error('industry')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                {{-- Company Number --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="company_number" class="form-label">Company Number</label>
                        <input type="text" class="form-control" id="company_number" name="company_number"
                            value="{{ old('company_number', $clientCompanyData['company_number'] ?? '') }}"
                            placeholder="Company Number (optional)">
                        @error('company_number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                {{-- GST Number Optional --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="gst_number" class="form-label">GST Number</label>
                        <input type="text" class="form-control" id="gst_number" name="gst_number"
                            value="{{ old('gst_number', $clientCompanyData['gst_number'] ?? '') }}"
                            placeholder="GST Number (optional)">
                        @error('gst_number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                {{-- HST Number --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="hst_number" class="form-label">HST Number</label>
                        <input type="text" class="form-control" id="hst_number" name="hst_number"
                            value="{{ old('hst_number', $clientCompanyData['hst_number'] ?? '') }}"
                            placeholder="HST Number (optional)">
                        @error('hst_number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                {{-- Email --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="{{ old('email', $clientCompanyData['email'] ?? '') }}" placeholder="Email"
                            required>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                {{-- Business Phone --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="business_phone" class="form-label">Business Phone</label>
                        <input type="text" class="form-control" id="business_phone" name="business_phone"
                            value="{{ old('business_phone', $clientCompanyData['business_phone'] ?? '') }}"
                            placeholder="Business Phone" required>
                        @error('business_phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                {{-- Row End Here --}}
            </div>
            {{-- Address --}}

            <div class="row">
                {{-- Suite --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="suite" class="form-label">Suite</label>
                        <input type="text" class="form-control" id="suite" name="suite"
                            value="{{ old('suite', $clientCompanyData['suite'] ?? '') }}" placeholder="Suite "
                            required>
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
                            value="{{ old('street', $clientCompanyData['street'] ?? '') }}" placeholder="Street"
                            required>
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
                        <select class="form-control @error('country') is-invalid @enderror" id="companyCountry"
                            name="country" onchange="getCompanyStates(this.value)" required>
                            <option value="">Select Country</option>
                            @foreach ($addressData['countries'] as $country)
                                <option value="{{ $country->id }}"
                                    {{ old('country', $clientCompanyData['country'] ?? '') == $country->id ? 'selected' : '' }}>
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
                        <select class="form-control @error('state') is-invalid @enderror" id="companyState"
                            name="state" onchange="getCompanyCities(this.value)" required>
                            <option value="" selected disabled>Select State</option>
                            @foreach ($addressData['companyStates'] as $state)
                                <option value="{{ $state->id }}"
                                    {{ old('state', $clientCompanyData['state'] ?? '') == $state->id ? 'selected' : '' }}>
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
                        <select class="form-control @error('city') is-invalid @enderror" id="companyCity"
                            name="city" required>
                            <option value="" selected disabled>Select City</option>
                            @foreach ($addressData['companyCities'] as $city)
                                <option value="{{ $city->id }}"
                                    {{ old('city', $clientCompanyData['city'] ?? '') == $city->id ? 'selected' : '' }}>
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
                            value="{{ old('zip_code', $clientCompanyData['zip_code'] ?? '') }}"
                            placeholder="Zip Code " required>
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
    function getCompanyStates(countryId) {
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
                $('#companyState').empty();
                $('#companyCity').empty();
                // Add option Select State
                $('#companyState').append(`<option value="" disabled selected>Select State</option>`);
                $('#companyCity').append(`<option value="" disabled selected>Select City</option>`);
                // Load to state select as options using loop
                response.forEach(function(state) {
                    console.log(state);
                    $('#companyState').append(
                        `<option value="${state.id}">${state.name}</option>`);

                })
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    // // Get cities
    function getCompanyCities(stateId) {
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
                $('#companyCity').empty();
                // Add option Select City
                $('#companyCity').append(`<option value="" disabled selected>Select City</option>`);
                // Load to city select as options using loop
                response.forEach(function(city) {
                    $('#companyCity').append(`<option value="${city.id}">${city.name}</option>`);
                })
            }
        })
    }
</script>
