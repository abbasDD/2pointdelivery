<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Company</h5>
    </div>
    <form action="{{ route('helper.update.company') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            {{-- Company Logo --}}
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group mb-3">
                        <div class="image-selection">
                            <div class="mx-auto" style="max-width: 150px;">
                                <img id="company_logo_preview"
                                    src="{{ isset($helperCompanyData['company_logo']) && $helperCompanyData['company_logo'] !== null ? asset('images/company/' . $helperCompanyData['company_logo']) : asset('images/company/default.png') }}"
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
                            value="{{ old('company_alias', $helperCompanyData['company_alias'] ?? '') }}"
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
                            value="{{ old('legal_name', $helperCompanyData['legal_name'] ?? '') }}"
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
                            value="{{ old('industry', $helperCompanyData['industry'] ?? '') }}" required>
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
                            value="{{ old('company_number', $helperCompanyData['company_number'] ?? '') }}"
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
                            value="{{ old('gst_number', $helperCompanyData['gst_number'] ?? '') }}"
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
                            value="{{ old('hst_number', $helperCompanyData['hst_number'] ?? '') }}"
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
                            value="{{ old('email', $helperCompanyData['email'] ?? '') }}" placeholder="Email"
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
                            value="{{ old('business_phone', $helperCompanyData['business_phone'] ?? '') }}"
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
                            value="{{ old('suite', $helperCompanyData['suite'] ?? '') }}" placeholder="Suite "
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
                            value="{{ old('street', $helperCompanyData['street'] ?? '') }}" placeholder="Street"
                            required>
                        @error('street')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                {{-- City --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" id="city" name="city"
                            value="{{ old('city', $helperCompanyData['city'] ?? '') }}" placeholder="City" required>

                        @error('city')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                {{-- State --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="state" class="form-label">State</label>
                        <input type="text" class="form-control" id="state" name="state"
                            value="{{ old('state', $helperCompanyData['state'] ?? '') }}" placeholder="State "
                            required>
                        @error('state')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                {{-- Country --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" class="form-control" id="country" name="country"
                            value="{{ old('country', $helperCompanyData['country'] ?? '') }}" placeholder="Country "
                            required>
                        @error('country')
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
                            value="{{ old('zip_code', $helperCompanyData['zip_code'] ?? '') }}"
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
    // Profile Image JS
    document.querySelector('#company_logo').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file.');
            event.target.value = null;
            return;
        }

        const reader = new FileReader();
        reader.onload = (event) => {
            document.querySelector('#company_logo_preview').src = event.target.result;
        }

        reader.readAsDataURL(file);
    });
</script>
