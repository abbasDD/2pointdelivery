<div class="row">

    {{-- SecureShip API Enable --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="secureship_api_enable">Secureship API Enable</label>
            <select class="form-control @error('secureship_api_enable') is-invalid @enderror" id="secureship_api_enable"
                name="secureship_api_enable" required>
                <option value="1"
                    {{ old('secureship_api_enable', $secureshipApi['secureship_api_enable'] ?? '') == 1 ? 'selected' : '' }}>
                    Yes
                </option>
                <option value="0"
                    {{ old('secureship_api_enable', $secureshipApi['secureship_api_enable'] ?? '') == 0 ? 'selected' : '' }}>
                    No
                </option>
            </select>
            @error('secureship_api_enable')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- secureship_api_key --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="secureship_api_key">Secureship API Key</label>
            <input type="text" class="form-control @error('secureship_api_key') is-invalid @enderror"
                id="secureship_api_key" name="secureship_api_key"
                value="{{ old('secureship_api_key', $secureshipApi['secureship_api_key'] ?? '') }}"
                placeholder="Enter Secureship API Key" required>
            @error('secureship_api_key')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- secureship_fee --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="secureship_fee">Secureship Fee</label>
            <input type="text" class="form-control @error('secureship_fee') is-invalid @enderror" id="secureship_fee"
                name="secureship_fee" value="{{ old('secureship_fee', $secureshipApi['secureship_fee'] ?? '') }}"
                placeholder="Enter Secureship Fee" required>
            @error('secureship_fee')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>


    {{-- Submit Button --}}
    <div class="col-md-12 text-right">
        <button type="submit" class="btn btn-primary btn-block">
            {{ isset($secureshipApi) ? 'Update' : 'Add' }}
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
