<div class="row">
    {{-- Google Heading --}}
    <div class="col-md-12">
        <h5>Google</h5>
    </div>
    {{-- Google Client ID --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="google_client_id">Google Client ID</label>
            <input type="text" class="form-control @error('google_client_id') is-invalid @enderror"
                id="google_client_id" name="google_client_id"
                value="{{ old('google_client_id', $AuthenticationSettings['google_client_id'] ?? '') }}"
                placeholder="Enter Google Client ID" required>
            @error('google_client_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Google Secret ID --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="google_secret_id">Google Secret ID</label>
            <input type="text" class="form-control @error('google_secret_id') is-invalid @enderror"
                id="google_secret_id" name="google_secret_id"
                value="{{ old('google_secret_id', $AuthenticationSettings['google_secret_id'] ?? '') }}"
                placeholder="Enter Google Secret ID" required>
            @error('google_secret_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Callback Url --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="callback_url">Callback Url</label>
            <input type="text" class="form-control @error('callback_url') is-invalid @enderror" id="callback_url"
                name="callback_url" value="{{ old('callback_url', $AuthenticationSettings['callback_url'] ?? '') }}"
                placeholder="Enter Callback Url" required>
            @error('callback_url')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Seprator --}}
    <div class="col-md-12">
        <hr>
    </div>


    {{-- Submit Button --}}
    <div class="col-md-12 text-right">
        <button type="submit" class="btn btn-primary btn-block">
            {{ isset($AuthenticationSettings) ? 'Update' : 'Add' }}
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
