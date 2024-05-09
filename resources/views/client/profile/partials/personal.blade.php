<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title mb-0">Personal</h5>
        @if (!$clientData->company_enabled)
            <button type="button" class="btn btn-primary btn-sm" onclick="requestCompany()">
                Request Company
            </button>
        @endif
    </div>
    <form action="{{ route('client.update.personal') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            {{-- Profile Image --}}
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group mb-3">
                        <div class="image-selection">
                            <div class="mx-auto" style="max-width: 150px;">
                                <img id="profile_image_preview"
                                    src="{{ isset($clientData['profile_image']) && $clientData['profile_image'] !== null ? asset('images/users/' . $clientData['profile_image']) : asset('images/users/default.png') }}"
                                    alt="profile_image" class=" border w-100 p-3"
                                    onclick="document.getElementById('profile_image').click()">
                                <input type="file" name="profile_image" id="profile_image" class="d-none"
                                    accept="image/*">
                            </div>
                        </div>
                        @if ($errors->has('profile_image'))
                            <span class="invalid-feedback" role="alert">
                                <strong>Profile Image is required</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            {{-- Personal --}}
            <div class="row">
                {{-- FIrst Name --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name"
                            value="{{ old('first_name', $clientData['first_name'] ?? '') }}" placeholder="First Name"
                            required>
                    </div>
                </div>
                {{-- Middle Name --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="middle_name" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="middle_name" name="middle_name"
                            value="{{ old('middle_name', $clientData['middle_name'] ?? '') }}"
                            placeholder="Middle Name (optional)">
                    </div>
                </div>
                {{-- Last Name --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name"
                            value="{{ old('last_name', $clientData['last_name'] ?? '') }}" placeholder="Last Name"
                            required>

                        @error('last_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                {{-- Gender --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender"
                            required>
                            <option value="" selected disabled>Choose Gender</option>
                            <option value="male"
                                {{ old('gender', $clientData['gender'] ?? '') == 'male' ? 'selected' : '' }}>
                                Male</option>
                            <option value="female"
                                {{ old('gender', $clientData['gender'] ?? '') == 'female' ? 'selected' : '' }}>
                                Female</option>
                        </select>
                        @error('gender')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                {{-- Date of Birth --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                            id="date_of_birth" name="date_of_birth"
                            value="{{ old('date_of_birth', $clientData['date_of_birth'] ?? '') }}" required>
                        @error('date_of_birth')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                {{-- Tax ID --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tax_id" class="form-label">Tax ID</label>
                        <input type="text" class="form-control" id="tax_id" name="tax_id"
                            value="{{ old('tax_id', $clientData['tax_id'] ?? '') }}" placeholder="Tax ID (optional)">
                        @error('tax_id')
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
                {{-- City --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" id="city" name="city"
                            value="{{ old('city', $clientData['city'] ?? '') }}" placeholder="City" required>

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
                            value="{{ old('state', $clientData['state'] ?? '') }}" placeholder="State " required>
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
                            value="{{ old('country', $clientData['country'] ?? '') }}" placeholder="Country "
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

{{-- Request Company Modal --}}
<div class="modal fade" id="requestCompanyModal" tabindex="-1" aria-labelledby="requestCompanyModalLabel
    "
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requestCompanyModalLabel">Request Company</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('client.company.request') }}" method="POST">
                <div class="modal-body">
                    @csrf
                    {{-- Company Alias --}}
                    <div class="mb-3">
                        <label for="company_alias" class="form-label">Company Alias</label>
                        <input type="text" class="form-control" id="company_alias" name="company_alias"
                            placeholder="Company Alias" required>
                    </div>
                    {{-- Legal Name --}}
                    <div class="mb-3">
                        <label for="legal_name" class="form-label">Legal Name</label>
                        <input type="text" class="form-control" id="legal_name" name="legal_name"
                            placeholder="Legal Name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" onclick="sendRequestCompany()">Request</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    // Profile Image JS
    document.querySelector('#profile_image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file.');
            event.target.value = null;
            return;
        }

        const reader = new FileReader();
        reader.onload = (event) => {
            document.querySelector('#profile_image_preview').src = event.target.result;
        }

        reader.readAsDataURL(file);
    });

    // Show Request Company Modal
    function requestCompany() {
        $('#requestCompanyModal').modal('show');
    }
</script>
