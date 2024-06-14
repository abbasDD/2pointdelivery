<div class="row">

    {{-- Google Heading --}}
    <div class="col-md-12">
        <h5>SMTP</h5>
    </div>
    {{-- Email Enabled --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="smtp_enabled">SMTP Enabled</label>
            <select class="form-control @error('smtp_enabled') is-invalid @enderror" id="smtp_enabled" name="smtp_enabled"
                onchange="smtpEnabled(this.value)">
                <option value="yes"
                    {{ old('smtp_enabled', isset($smtpSettings['smtp_enabled']) && $smtpSettings['smtp_enabled'] == 'yes' ?? '') == 'yes' ? 'selected' : '' }}>
                    Yes
                </option>
                <option value="no"
                    {{ old('smtp_enabled', isset($smtpSettings['smtp_enabled']) && $smtpSettings['smtp_enabled'] == 'no' ?? '') == 'no' ? 'selected' : '' }}>
                    No
                </option>
            </select>
            @error('smtp_enabled')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    {{-- SMTP Host --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="smtp_host">SMTP Host</label>
            <input type="text" class="form-control smtp_required @error('smtp_host') is-invalid @enderror"
                id="smtp_host" name="smtp_host"
                value="{{ old('smtp_host', isset($smtpSettings['smtp_host']) && $smtpSettings['smtp_host'] ? $smtpSettings['smtp_host'] : '') }}"
                placeholder="Enter SMTP Host">
            @error('smtp_host')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- SMTP Port --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="smtp_port">SMTP Port</label>
            <input type="text" class="form-control smtp_required @error('smtp_port') is-invalid @enderror"
                id="smtp_port" name="smtp_port"
                value="{{ old('smtp_port', isset($smtpSettings['smtp_port']) && $smtpSettings['smtp_port'] ? $smtpSettings['smtp_port'] : '') }}"
                placeholder="Enter SMTP Port">
            @error('smtp_port')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- SMTP Username --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="smtp_username">SMTP Username</label>
            <input type="text" class="form-control smtp_required @error('smtp_username') is-invalid @enderror"
                id="smtp_username" name="smtp_username"
                value="{{ old('smtp_username', isset($smtpSettings['smtp_username']) && $smtpSettings['smtp_username'] ? $smtpSettings['smtp_username'] : '') }}"
                placeholder="Enter SMTP Username">
            @error('smtp_username')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- SMTP Password --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="smtp_password">SMTP Password</label>
            <input type="text" class="form-control smtp_required @error('smtp_password') is-invalid @enderror"
                id="smtp_password" name="smtp_password"
                value="{{ old('smtp_password', isset($smtpSettings['smtp_password']) && $smtpSettings['smtp_password'] ? $smtpSettings['smtp_password'] : '') }}"
                placeholder="Enter SMTP Password">
            @error('smtp_password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- SMTP Encryption --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="smtp_encryption">SMTP Encryption</label>
            <input type="text" class="form-control @error('smtp_encryption') is-invalid @enderror"
                id="smtp_encryption" name="smtp_encryption"
                value="{{ old('smtp_encryption', isset($smtpSettings['smtp_encryption']) && $smtpSettings['smtp_encryption'] ? $smtpSettings['smtp_encryption'] : '') }}"
                placeholder="Enter SMTP Encryption">
            @error('smtp_encryption')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- smtp_from_email --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="smtp_from_email">SMTP From Email</label>
            <input type="text" class="form-control @error('smtp_from_email') is-invalid @enderror"
                id="smtp_from_email" name="smtp_from_email"
                value="{{ old('smtp_from_email', isset($smtpSettings['smtp_from_email']) && $smtpSettings['smtp_from_email'] ? $smtpSettings['smtp_from_email'] : '') }}"
                placeholder="Enter SMTP From Email">
            @error('smtp_from_email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- smtp_from_name --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="smtp_from_name">SMTP From Name</label>
            <input type="text" class="form-control @error('smtp_from_name') is-invalid @enderror" id="smtp_from_name"
                name="smtp_from_name"
                value="{{ old('smtp_from_name', isset($smtpSettings['smtp_from_name']) && $smtpSettings['smtp_from_name'] ? $smtpSettings['smtp_from_name'] : '') }}"
                placeholder="Enter SMTP From Name">
            @error('smtp_from_name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Submit Button --}}
    <div class="col-md-12 text-right">
        <button type="submit" class="btn btn-primary btn-block">
            {{ isset($smtpSettings) ? 'Update' : 'Add' }}
        </button>
    </div>
</div>


<script>
    // Google Enabled Function
    function smtpEnabled(value) {
        // alert(value);
        // if value is yes then add required attributes else remove
        if (value == 'yes') {
            document.getElementByClassName('smtp_required').required = true;
        } else {
            document.getElementById('smtp_required').required = false;
        }
    }
</script>
