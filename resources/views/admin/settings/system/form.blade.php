{{-- Sub Admin Form --}}
<div class="row">
    {{-- Website Logo --}}
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label for="website_name">Website Logo</label>
            <div class="image-selection">
                <div class="mx-auto" style="max-width: 150px;">
                    <img id="website_logo_preview"
                        src="{{ isset($systemSettings['website_logo']) && $systemSettings['website_logo'] !== null ? asset('images/logo/' . $systemSettings['website_logo']) : asset('images/logo/default.png') }}"
                        alt="website_logo" class="p-3 border w-100"
                        onclick="document.getElementById('website_logo').click()">
                    <input type="file" name="website_logo" id="website_logo" class="d-none" accept="image/*">
                </div>
            </div>
            @if ($errors->has('website_logo'))
                <span class="invalid-feedback" role="alert">
                    <strong>Website Logo is required</strong>
                </span>
            @endif
        </div>
    </div>
    {{-- White Logo --}}
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label for="website_name">White Logo</label>
            <div class="image-selection">
                <div class="mx-auto" style="max-width: 150px;">
                    <img id="white_logo_preview"
                        src="{{ isset($systemSettings['white_logo']) && $systemSettings['white_logo'] !== null ? asset('images/logo/' . $systemSettings['white_logo']) : asset('images/logo/default.png') }}"
                        alt="white_logo" class="p-3 border w-100"
                        onclick="document.getElementById('white_logo').click()">
                    <input type="file" name="white_logo" id="white_logo" class="d-none" accept="image/*">
                </div>
            </div>
            @if ($errors->has('white_logo'))
                <span class="invalid-feedback" role="alert">
                    <strong>Website Logo is required</strong>
                </span>
            @endif
        </div>
    </div>

    {{-- Website Favicon --}}
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label for="website_name">Website Favicon</label>
            <div class="image-selection">
                <div class="mx-auto" style="max-width: 150px;">
                    <img id="website_favicon_preview"
                        src="{{ isset($systemSettings['website_favicon']) && $systemSettings['website_favicon'] !== null ? asset('images/logo/' . $systemSettings['website_favicon']) : asset('images/logo/default.png') }}"
                        alt="website_favicon" class="p-3 border w-100"
                        onclick="document.getElementById('website_favicon').click()">
                    <input type="file" name="website_favicon" id="website_favicon" class="d-none" accept="image/*">
                </div>
            </div>
            @if ($errors->has('website_favicon'))
                <span class="invalid-feedback" role="alert">
                    <strong>Website Favicon is required</strong>
                </span>
            @endif
        </div>
    </div>

    {{-- Website Name --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="website_name">Website Name</label>
            <input type="text" class="form-control @error('website_name') is-invalid @enderror" id="website_name"
                name="website_name" value="{{ old('website_name', $systemSettings['website_name'] ?? '') }}"
                placeholder="Enter Website Name" required>
            @error('website_name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    {{-- Currency Selection --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="currency">Currency</label>
            <select class="form-control @error('currency') is-invalid @enderror" id="currency" name="currency"
                required>
                <option value="" disabled>Select Currency</option>
                <option value="cad"
                    {{ old('currency', $systemSettings['currency'] ?? '') == 'cad' ? 'selected' : '' }}>CAD
                </option>
                <option value="usd"
                    {{ old('currency', $systemSettings['currency'] ?? '') == 'usd' ? 'selected' : '' }}>USD
                </option>
                <option value="gbp"
                    {{ old('currency', $systemSettings['currency'] ?? '') == 'gbp' ? 'selected' : '' }}>
                    GBP
                </option>
            </select>
            @error('currency')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Auto Assign Driver --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="auto_assign_driver">Auto Assign Driver</label>
            <select class="form-control @error('auto_assign_driver') is-invalid @enderror" id="auto_assign_driver"
                name="auto_assign_driver" required>
                <option value="" disabled>Select Auto Assign Driver</option>
                <option value="yes"
                    {{ old('auto_assign_driver', $systemSettings['auto_assign_driver'] ?? '') == 'yes' ? 'selected' : '' }}>
                    Yes
                </option>
                <option value="no"
                    {{ old('auto_assign_driver', $systemSettings['auto_assign_driver'] ?? '') == 'no' ? 'selected' : '' }}>
                    No
                </option>
            </select>
            @error('auto_assign_driver')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Default Language --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="language">Default Language</label>
            <select class="form-control @error('language') is-invalid @enderror" id="language" name="language"
                required>
                <option value="" disabled>Select Language</option>
                {{-- English --}}
                <option value="en"
                    {{ old('language', $systemSettings['language'] ?? '') == 'en' ? 'selected' : '' }}>
                    English
                </option>
                {{-- French --}}
                <option value="fr"
                    {{ old('language', $systemSettings['language'] ?? '') == 'fr' ? 'selected' : '' }}>
                    French
                </option>
                {{-- Swedish --}}
                <option value="sv"
                    {{ old('language', $systemSettings['language'] ?? '') == 'sv' ? 'selected' : '' }}>
                    Swedish
                </option>
            </select>
            @error('language')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>


    {{-- Dimension Selection --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="dimension">Dimension</label>
            <select class="form-control @error('dimension') is-invalid @enderror" id="dimension" name="dimension"
                required>
                <option value="" disabled>Select Dimension</option>
                <option value="cm"
                    {{ old('dimension', $systemSettings['dimension'] ?? '') == 'cm' ? 'selected' : '' }}>
                    Metric (cm)
                </option>
                <option value="inch"
                    {{ old('dimension', $systemSettings['dimension'] ?? '') == 'inch' ? 'selected' : '' }}>
                    Imperial (inch)
                </option>
            </select>
            @error('dimension')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Wight Selection --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="weight">Weight</label>
            <select class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight"
                required>
                <option value="" disabled>Select Weight</option>
                <option value="kg" {{ old('weight', $systemSettings['weight'] ?? '') == 'kg' ? 'selected' : '' }}>
                    Kg
                </option>
                <option value="lbs"
                    {{ old('weight', $systemSettings['weight'] ?? '') == 'lbs' ? 'selected' : '' }}>
                    Lbs
                </option>
            </select>
            @error('weight')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Package Value Declare --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="declare_package_value">Declare Package Value</label>
            <select class="form-control @error('declare_package_value') is-invalid @enderror"
                id="declare_package_value" name="declare_package_value" required>
                <option value="" disabled>Select Declare Package Value</option>
                <option value="yes"
                    {{ old('declare_package_value', $systemSettings['declare_package_value'] ?? '') == 'yes' ? 'selected' : '' }}>
                    Yes
                </option>
                <option value="no"
                    {{ old('declare_package_value', $systemSettings['declare_package_value'] ?? '') == 'no' ? 'selected' : '' }}>
                    No
                </option>
            </select>
            @error('declare_package_value')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Insurance Value Declare --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="insurance">Insurance</label>
            <select class="form-control @error('insurance') is-invalid @enderror" id="insurance" name="insurance"
                required>
                <option value="" disabled>Select Insurance</option>
                <option value="enabled"
                    {{ old('insurance', strval($systemSettings['insurance']) ?? '') == 'enabled' ? 'selected' : '' }}>
                    Enabled
                </option>
                <option value="disabled"
                    {{ old('insurance', strval($systemSettings['insurance']) ?? '') == 'disabled' ? 'selected' : '' }}>
                    Disabled
                </option>
            </select>
            @error('insurance')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Timezone Selection --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="timezone">Timezone</label>
            <select class="form-control @error('timezone') is-invalid @enderror" id="timezone" name="timezone"
                required>
                <option value="" disabled>Select Timezone</option>
                @foreach ($timezones as $timezone)
                    <option value="{{ $timezone }}"
                        {{ old('timezone', $systemSettings['timezone'] ?? '') == $timezone ? 'selected' : '' }}>
                        {{ $timezone }}
                    </option>
                @endforeach
            </select>
            @error('timezone')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Date Format --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="date_format">Date Format</label>
            <select class="form-control @error('date_format') is-invalid @enderror" id="date_format"
                name="date_format" required>
                <option value="" disabled>Select Date Format</option>
                <option value="Y-m-d"
                    {{ old('date_format', $systemSettings['date_format'] ?? '') == 'Y-m-d' ? 'selected' : '' }}>
                    YYYY-MM-DD (e.g., 2024-08-30)
                </option>
                <option value="d-m-Y"
                    {{ old('date_format', $systemSettings['date_format'] ?? '') == 'd-m-Y' ? 'selected' : '' }}>
                    DD-MM-YYYY (e.g., 30-08-2024)
                </option>
                <option value="m-d-Y"
                    {{ old('date_format', $systemSettings['date_format'] ?? '') == 'm-d-Y' ? 'selected' : '' }}>
                    MM-DD-YYYY (e.g., 08-30-2024)
                </option>
            </select>
            @error('date_format')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Time Format --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="time_format">Time Format</label>
            <select class="form-control @error('time_format') is-invalid @enderror" id="time_format"
                name="time_format" required>
                <option value="" disabled>Select Time Format</option>
                <option value="h:i A"
                    {{ old('time_format', $systemSettings['time_format'] ?? '') == 'h:i A' ? 'selected' : '' }}>
                    12-hour (e.g., 02:30 PM)
                </option>
                <option value="H:i"
                    {{ old('time_format', $systemSettings['time_format'] ?? '') == 'H:i' ? 'selected' : '' }}>
                    24-hour (e.g., 14:30)
                </option>
            </select>
            @error('time_format')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>


    {{-- Submit Button --}}
    <div class="col-md-12 text-right">
        <button type="submit" class="btn btn-primary btn-block">
            {{ isset($systemSettings) ? 'Update' : 'Add' }}
        </button>
    </div>
</div>


<script>
    // Website Logo JS
    document.querySelector('#website_logo').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file.');
            event.target.value = null;
            return;
        }

        const reader = new FileReader();
        reader.onload = (event) => {
            document.querySelector('#website_logo_preview').src = event.target.result;
        }

        reader.readAsDataURL(file);
    });

    // White Logo JS
    document.querySelector('#white_logo').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file.');
            event.target.value = null;
            return;
        }

        const reader = new FileReader();
        reader.onload = (event) => {
            document.querySelector('#white_logo_preview').src = event.target.result;
        }

        reader.readAsDataURL(file);
    });


    // Website Favicon JS
    document.querySelector('#website_favicon').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file.');
            event.target.value = null;
            return;
        }

        const reader = new FileReader();
        reader.onload = (event) => {
            document.querySelector('#website_favicon_preview').src = event.target.result;
        }

        reader.readAsDataURL(file);
    });
</script>
