<div class="row">
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="insurance_api_enable">Insurance API Enable</label>
            <select class="form-control @error('insurance_api_enable') is-invalid @enderror" id="insurance_api_enable"
                name="insurance_api_enable" required>
                <option value="1"
                    {{ old('insurance_api_enable', $insuranceApi['insurance_api_enable'] ?? '') == 1 ? 'selected' : '' }}>
                    Yes
                </option>
                <option value="0"
                    {{ old('insurance_api_enable', $insuranceApi['insurance_api_enable'] ?? '') == 0 ? 'selected' : '' }}>
                    No
                </option>
            </select>
            @error('insurance_api_enable')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>


    {{-- API Identifier --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="insurance_api_identifier">API Identifier</label>
            <input type="text" class="form-control @error('insurance_api_identifier') is-invalid @enderror"
                id="insurance_api_identifier" name="insurance_api_identifier"
                value="{{ old('insurance_api_identifier', $insuranceApi['insurance_api_identifier'] ?? '') }}"
                placeholder="Enter API Identifier" required>
            @error('insurance_api_identifier')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- API Secret Key --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="insurance_api_secret_key">API Secret Key</label>
            <input type="text" class="form-control @error('insurance_api_secret_key') is-invalid @enderror"
                id="insurance_api_secret_key" name="insurance_api_secret_key"
                value="{{ old('insurance_api_secret_key', $insuranceApi['insurance_api_secret_key'] ?? '') }}"
                placeholder="Enter API Secret Key" required>
            @error('insurance_api_secret_key')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Submit Button --}}
    <div class="col-md-12 text-right">
        <button type="submit" class="btn btn-primary btn-block">
            {{ isset($insuranceApi) ? 'Update' : 'Add' }}
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
