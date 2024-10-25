<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title mb-0">Personal</h5>
        {{-- Show Referral Code with copy button --}}
        @if (isset(auth()->user()->referral_code))
            <div class="d-flex align-items-center">
                <p class="mb-0 fs-18" onclick="copyToClipboard('{{ auth()->user()->referral_code }}')">
                    <span class="badge bg-primary">{{ auth()->user()->referral_code }}</span>
                </p>
            </div>
        @endif
    </div>
    <form action="{{ route('helper.update.personal') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            {{-- Profile Image --}}
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group mb-3">
                        <div class="image-selection">
                            <div class="mx-auto" style="max-width: 150px;">
                                <img id="profile_image_preview"
                                    src="{{ isset($helperData['profile_image']) && $helperData['profile_image'] !== null ? asset('images/users/' . $helperData['profile_image']) : asset('images/users/default.png') }}"
                                    alt="profile_image" class="p-3 border w-100 p-3"
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
                            {{ old('company_enabled', $helperData['company_enabled'] ?? '') == 0 ? 'selected' : '' }}>
                            Individual</option>
                        <option value="1"
                            {{ old('company_enabled', $helperData['company_enabled'] ?? '') == 1 ? 'selected' : '' }}>
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
                            value="{{ old('first_name', $helperData['first_name'] ?? '') }}" placeholder="First Name"
                            required>
                    </div>
                </div>
                {{-- Middle Name --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="middle_name" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="middle_name" name="middle_name"
                            value="{{ old('middle_name', $helperData['middle_name'] ?? '') }}"
                            placeholder="Middle Name (optional)">
                    </div>
                </div>
                {{-- Last Name --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name"
                            value="{{ old('last_name', $helperData['last_name'] ?? '') }}" placeholder="Last Name"
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
                            value="{{ old('phone_no', $helperData['phone_no'] ?? '') }}" placeholder="Phone Number"
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
                        <select class="form-control @error('gender') is-invalid @enderror" id="gender"
                            name="gender" required>
                            <option value="" selected disabled>Choose Gender</option>
                            <option value="male"
                                {{ old('gender', $helperData['gender'] ?? '') == 'male' ? 'selected' : '' }}>
                                Male</option>
                            <option value="female"
                                {{ old('gender', $helperData['gender'] ?? '') == 'female' ? 'selected' : '' }}>
                                Female</option>
                            <option value="other"
                                {{ old('gender', $helperData['gender'] ?? '') == 'other' ? 'selected' : '' }}>
                                Other</option>
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
                        <div class="form-group">
                            <label for="date_of_birth">Date of Birth</label>
                            <input class="form-control" type="text" name="date_of_birth"
                                value="{{ old('date_of_birth', isset($helperData->date_of_birth) ? $helperData->date_of_birth : '') }}"
                                placeholder="Enter Issue Date" required>
                            @error('date_of_birth')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <script>
                            var dateFormat = convertDateFormat("<?php echo config('date_format') ?? 'd-m-Y'; ?>");

                            // Initialize the date picker with the correct format
                            $('#date_of_birth').datepicker({
                                dateFormat: dateFormat
                            });
                        </script>
                    </div>
                </div>

                {{-- Service Badge ID --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="service_badge_id" class="form-label">Service Badge ID</label>
                        <input type="text" class="form-control" id="service_badge_id" name="service_badge_id"
                            value="{{ old('service_badge_id', $helperData['service_badge_id'] ?? '') }}"
                            placeholder="Service Badge ID (optional)">
                        @error('service_badge_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                {{-- Service Available for --}}
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <label>Service Available for <span class="text-danger">*</span></label>
                        <div class="d-block">
                            <div class="row">
                                @foreach ($services as $service)
                                    <div class="col-md-3">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox"
                                                id="service-{{ $service->id }}" name="services[]"
                                                value="{{ $service->id }}"
                                                @if (in_array($service->id, $helperServiceIds)) checked @endif>
                                            <label class="custom-control-label"
                                                for="service-{{ $service->id }}">{{ $service->name }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('services')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            @if ($errors->has('services'))
                                <span class="help-block text-danger">
                                    <strong>Please select atleast one service</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>


                {{-- Row End Here --}}
            </div>

            {{-- Show only if original_user_id is null which means user is the team owner --}}
            @if (session('original_user_id') == null)
                {{-- Button to Submit --}}
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            @endif

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

    // Copy to clip board
    function copyToClipboard(element) {
        var $temp = element;
        // Caopy this newPassword value to clipboard
        navigator.clipboard.writeText($temp);
        // Trigger Notification
        triggerToast('Success', 'Password copied to clipboard');


    }
</script>
