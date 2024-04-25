{{-- Sub Admin Form --}}
<div class="row">
    {{-- Website Logo --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <div class="image-selection">
                <div class="mx-auto" style="max-width: 150px;">
                    <img id="website_logo_preview"
                        src="{{ isset($systemSettings['website_logo']) && $systemSettings['website_logo'] !== null ? asset('images/logo/' . $systemSettings['website_logo']) : asset('images/logo/default.png') }}"
                        alt="website_logo" class=" border w-100"
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

    {{-- Website Favicon --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <div class="image-selection">
                <div class="mx-auto" style="max-width: 150px;">
                    <img id="website_favicon_preview"
                        src="{{ isset($systemSettings['website_favicon']) && $systemSettings['website_favicon'] !== null ? asset('images/logo/' . $systemSettings['website_favicon']) : asset('images/logo/default.png') }}"
                        alt="website_favicon" class=" border w-100"
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
                <option value="1"
                    {{ old('auto_assign_driver', $systemSettings['auto_assign_driver'] ?? '') == '1' ? 'selected' : '' }}>
                    Yes
                </option>
                <option value="0"
                    {{ old('auto_assign_driver', $systemSettings['auto_assign_driver'] ?? '') == '0' ? 'selected' : '' }}>
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
                <option value="en"
                    {{ old('language', $systemSettings['language'] ?? '') == 'en' ? 'selected' : '' }}>
                    English
                </option>
                <option value="ar"
                    {{ old('language', $systemSettings['language'] ?? '') == 'ar' ? 'selected' : '' }}>
                    عربي
                </option>
            </select>
            @error('language')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>


    {{-- Submit Button --}}
    <div class="col-md-12 text-right">
        <button type="submit" class="btn btn-primary btn-block">
            {{ isset($systemSettings) ? 'Update' : 'Submit' }}
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
