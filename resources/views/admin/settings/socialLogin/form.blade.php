<div class="row">

    {{-- Google Heading --}}
    <div class="col-md-12">
        <h5>Google</h5>
    </div>
    {{-- Google Enabled --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="google_enabled">Google Enabled</label>
            <select class="form-control @error('google_enabled') is-invalid @enderror" id="google_enabled"
                name="google_enabled" onchange="googleEnabled(this.value)">
                <option value="yes"
                    {{ old('google_enabled', isset($socialLoginSettings['google_enabled']) && $socialLoginSettings['google_enabled'] == 'yes' ?? '') == 'yes' ? 'selected' : '' }}>
                    Yes
                </option>
                <option value="no"
                    {{ old('google_enabled', isset($socialLoginSettings['google_enabled']) && $socialLoginSettings['google_enabled'] == 'no' ?? '') == 'no' ? 'selected' : '' }}>
                    No
                </option>
            </select>
            @error('google_enabled')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    {{-- Google Client ID --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="google_client_id">Google Client ID</label>
            <input type="text" class="form-control @error('google_client_id') is-invalid @enderror"
                id="google_client_id" name="google_client_id"
                value="{{ old('google_client_id', isset($socialLoginSettings['google_client_id']) && $socialLoginSettings['google_client_id'] ? $socialLoginSettings['google_client_id'] : '') }}"
                placeholder="Enter Google Client ID">
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
                value="{{ old('google_secret_id', isset($socialLoginSettings['google_secret_id']) && $socialLoginSettings['google_secret_id'] ? $socialLoginSettings['google_secret_id'] : '') }}"
                placeholder="Enter Google Secret ID">
            @error('google_secret_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- google_redirect_uri --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="google_redirect_uri">Google Redirect URI</label>
            <input type="text" class="form-control @error('google_redirect_uri') is-invalid @enderror"
                id="google_redirect_uri" name="google_redirect_uri"
                value="{{ old('google_redirect_uri', isset($socialLoginSettings['google_redirect_uri']) && $socialLoginSettings['google_redirect_uri'] ? $socialLoginSettings['google_redirect_uri'] : '') }}"
                placeholder="Enter Google Redirect URI">
            @error('google_redirect_uri')
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


    {{-- Facebook Heading --}}
    <div class="col-md-12">
        <h5>Facebook</h5>
    </div>
    {{-- Facebook Enabled --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="facebook_enabled">Facebook Enabled</label>
            <select class="form-control @error('facebook_enabled') is-invalid @enderror" id="facebook_enabled"
                name="facebook_enabled" onchange="facebookEnabled(this.value)">
                <option value="yes"
                    {{ old('facebook_enabled', isset($socialLoginSettings['facebook_enabled']) && $socialLoginSettings['facebook_enabled'] == 'yes' ?? '') == 'yes' ? 'selected' : '' }}>
                    Yes
                </option>
                <option value="no"
                    {{ old('facebook_enabled', isset($socialLoginSettings['facebook_enabled']) && $socialLoginSettings['facebook_enabled'] == 'no' ?? '') == 'no' ? 'selected' : '' }}>
                    No
                </option>
            </select>
            @error('facebook_enabled')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Submit Button --}}
    <div class="col-md-12 text-right">
        <button type="submit" class="btn btn-primary btn-block">
            {{ isset($socialLoginSettings) ? 'Update' : 'Add' }}
        </button>
    </div>
</div>


<script>
    // Google Enabled Function
    function googleEnabled(value) {
        // alert(value);
        // if value is yes then add required attributes else remove
        if (value == 'yes') {
            document.getElementById('google_client_id').required = true;
            document.getElementById('google_secret_id').required = true;
        } else {
            document.getElementById('google_client_id').required = false;
            document.getElementById('google_secret_id').required = false;
        }
    }
</script>
