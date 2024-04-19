{{-- Sub Admin Form --}}
<div class="row">

    {{-- Website Name --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="website_name">Website Name</label>
            <input type="text" class="form-control @error('website_name') is-invalid @enderror" id="website_name"
                name="website_name" value="{{ old('website_name', $settings['website_name'] ?? '') }}"
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
                <option value="" selected disabled>Select Currency</option>
                <option value="usd" {{ old('currency', $settings['currency'] ?? '') == 'usd' ? 'selected' : '' }}>USD
                </option>
                <option value="gbp" {{ old('currency', $settings['currency'] ?? '') == 'gbp' ? 'selected' : '' }}>
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
                <option value="" selected disabled>Select Auto Assign Driver</option>
                <option value="1"
                    {{ old('auto_assign_driver', $settings['auto_assign_driver'] ?? '') == '1' ? 'selected' : '' }}>
                    Yes
                </option>
                <option value="0"
                    {{ old('auto_assign_driver', $settings['auto_assign_driver'] ?? '') == '0' ? 'selected' : '' }}>
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
                <option value="" selected disabled>Select Language</option>
                <option value="en" {{ old('language', $settings['language'] ?? '') == 'en' ? 'selected' : '' }}>
                    English
                </option>
                <option value="ar" {{ old('language', $settings['language'] ?? '') == 'ar' ? 'selected' : '' }}>
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
            {{ isset($settings) ? 'Update' : 'Submit' }}
        </button>
    </div>
</div>
