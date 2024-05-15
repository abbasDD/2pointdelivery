<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title mb-0">Personal</h5>
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
                {{-- Account Type --}}
                <div class="col-md-12 mb-3">
                    <select id="company_enabled" class="form-control @error('company_enabled') is-invalid @enderror"
                        name="company_enabled" required autocomplete="company_enabled" autofocus>
                        <option value="" selected disabled>Choose Account Type</option>
                        <option value="0"
                            {{ old('company_enabled', $clientData['company_enabled'] ?? '') == 0 ? 'selected' : '' }}>
                            Individual</option>
                        <option value="1"
                            {{ old('company_enabled', $clientData['company_enabled'] ?? '') == 1 ? 'selected' : '' }}>
                            Company</option>
                    </select>
                    @error('company_enabled')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
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
                {{-- Email --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ auth()->user()->email }}" placeholder="Email" readonly>

                    </div>
                </div>
                {{-- Phone Number --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone_no" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone_no" name="phone_no"
                            value="{{ old('phone_no', $clientData['phone_no'] ?? '') }}" placeholder="Phone Number"
                            required>

                        @error('phone_no')
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
</script>
