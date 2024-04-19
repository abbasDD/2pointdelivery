{{-- Country Form --}}
<div class="row">
    {{-- Country Name --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="country">Country Name</label>
            <input type="text" class="form-control @error('country') is-invalid @enderror" id="country" name="country"
                value="{{ old('country', $taxCountry['country'] ?? '') }}" placeholder="Enter Country Name" required>
            @error('country')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- State Name --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="state">State Name</label>
            <input type="text" class="form-control @error('state') is-invalid @enderror" id="state"
                name="state" value="{{ old('state', $taxCountry['state'] ?? '') }}" placeholder="Enter State Name"
                required>
            @error('state')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Tax Type --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="tax_type">Tax Type</label>
            <select class="form-control @error('tax_type') is-invalid @enderror" id="tax_type" name="tax_type"
                required>
                <option value="">Select Tax Type</option>
                <option value="gst" {{ old('tax_type', $taxCountry['tax_type'] ?? '') == 'gst' ? 'selected' : '' }}>
                    GST
                </option>
                <option value="pst" {{ old('tax_type', $taxCountry['tax_type'] ?? '') == 'pst' ? 'selected' : '' }}>
                    PST
                </option>
                <option value="hst" {{ old('tax_type', $taxCountry['tax_type'] ?? '') == 'hst' ? 'selected' : '' }}>
                    HST
                </option>
            </select>
            @error('tax_type')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Tax Rate --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="tax_rate">Tax Rate</label>
            <input type="text" class="form-control @error('tax_rate') is-invalid @enderror" id="tax_rate"
                name="tax_rate" value="{{ old('tax_rate', $taxCountry['tax_rate'] ?? '') }}"
                placeholder="Enter Tax Rate" required>
            @error('tax_rate')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Status --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="is_active">Status</label>
            <select class="form-control @error('is_active') is-invalid @enderror" id="is_active" name="is_active"
                required>
                <option value="">Select Status</option>
                <option value="1" {{ old('is_active', $taxCountry['is_active'] ?? '') == 1 ? 'selected' : '' }}>
                    Active
                </option>
                <option value="0" {{ old('is_active', $taxCountry['is_active'] ?? '') == 0 ? 'selected' : '' }}>
                    Inactive
                </option>
            </select>
            @error('is_active')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Submit Button --}}
    <div class="col-md-12 text-right">
        <button type="submit" class="btn btn-primary btn-block">
            {{ isset($taxCountry) ? 'Update' : 'Submit' }}
        </button>
    </div>
</div>
