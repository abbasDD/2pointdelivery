{{-- Form --}}

<div class="form-group mb-3">
    <label for="google_map_api_key">Google Map API Key</label>
    <input type="text" class="form-control @error('google_map_api_key') is-invalid @enderror" id="google_map_api_key"
        name="google_map_api_key" value="{{ old('google_map_api_key', $systemSettings['google_map_api_key'] ?? '') }}"
        placeholder="Enter Map Key" required>
    @error('google_map_api_key')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
{{-- Submit Button --}}
<div class="text-right">
    <button type="submit" class="btn btn-primary">
        Update
    </button>
</div>
