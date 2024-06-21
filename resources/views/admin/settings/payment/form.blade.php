<div class="row">
    {{-- COD Heading --}}
    <div class="col-md-12">
        <h5>Cash On Delivery</h5>
    </div>
    {{-- COD Enabled --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="cod_enabled">COD Enabled</label>
            <select class="form-control @error('cod_enabled') is-invalid @enderror" id="cod_enabled" name="cod_enabled">

                <option value="yes"
                    {{ old('cod_enabled', isset($paymentSettings['cod_enabled']) && $paymentSettings['cod_enabled'] == 'yes' ?? '') == 'yes' ? 'selected' : '' }}>
                    Yes
                </option>
                <option value="no"
                    {{ old('cod_enabled', isset($paymentSettings['cod_enabled']) && $paymentSettings['cod_enabled'] == 'no' ?? '') == 'no' ? 'selected' : '' }}>
                    No
                </option>
            </select>
            @error('cod_enabled')
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

    {{-- Paypal Heading --}}
    <div class="col-md-12">
        <h5>Paypal</h5>
    </div>
    {{-- Paypal Enabled --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="paypal_enabled">Paypal Enabled</label>
            <select class="form-control @error('paypal_enabled') is-invalid @enderror" id="paypal_enabled"
                name="paypal_enabled" onchange="paypalEnabled(this.value)">
                <option value="yes"
                    {{ old('paypal_enabled', isset($paymentSettings['paypal_enabled']) && $paymentSettings['paypal_enabled'] == 'yes' ?? '') == 'yes' ? 'selected' : '' }}>
                    Yes
                </option>
                <option value="no"
                    {{ old('paypal_enabled', isset($paymentSettings['paypal_enabled']) && $paymentSettings['paypal_enabled'] == 'no' ?? '') == 'no' ? 'selected' : '' }}>
                    No
                </option>
            </select>
            @error('paypal_enabled')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    {{-- Paypal Client ID --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="paypal_client_id">Paypal Client ID</label>
            <input type="text" class="form-control @error('paypal_client_id') is-invalid @enderror"
                id="paypal_client_id" name="paypal_client_id"
                value="{{ old('paypal_client_id', isset($paymentSettings['paypal_client_id']) && $paymentSettings['paypal_client_id'] ? $paymentSettings['paypal_client_id'] : '') }}"
                placeholder="Enter Paypal Client ID">
            @error('paypal_client_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Paypal Secret ID --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="paypal_secret_id">Paypal Secret ID</label>
            <input type="text" class="form-control @error('paypal_secret_id') is-invalid @enderror"
                id="paypal_secret_id" name="paypal_secret_id"
                value="{{ old('paypal_secret_id', isset($paymentSettings['paypal_secret_id']) && $paymentSettings['paypal_secret_id'] ? $paymentSettings['paypal_secret_id'] : '') }}"
                placeholder="Enter Paypal Secret ID">
            @error('paypal_secret_id')
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

    {{-- Stripe Heading --}}
    <div class="col-md-12">
        <h5>Stripe</h5>
    </div>
    {{-- Stripe Enabled --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="stripe_enabled">Stripe Enabled</label>
            <select class="form-control @error('stripe_enabled') is-invalid @enderror" id="stripe_enabled"
                name="stripe_enabled" onchange="stripeEnabled(this.value)">
                <option value="yes"
                    {{ old('stripe_enabled', isset($paymentSettings['stripe_enabled']) && $paymentSettings['stripe_enabled'] == 'yes' ?? '') == 'yes' ? 'selected' : '' }}>
                    Yes
                </option>
                <option value="no"
                    {{ old('stripe_enabled', isset($paymentSettings['stripe_enabled']) && $paymentSettings['stripe_enabled'] == 'no' ?? '') == 'no' ? 'selected' : '' }}>
                    No
                </option>
            </select>
            @error('stripe_enabled')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Stripe Publishable Key --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="stripe_publishable_key">Stripe Publishable Key</label>
            <input type="text" class="form-control @error('stripe_publishable_key') is-invalid @enderror"
                id="stripe_publishable_key" name="stripe_publishable_key"
                value="{{ old('stripe_publishable_key', isset($paymentSettings['stripe_publishable_key']) && $paymentSettings['stripe_publishable_key'] ? $paymentSettings['stripe_publishable_key'] : '') }}"
                placeholder="Enter Stripe Publishable Key">
            @error('stripe_publishable_key')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    {{-- Stripe Secret Key --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="stripe_secret_key">Stripe Secret Key</label>
            <input type="text" class="form-control @error('stripe_secret_key') is-invalid @enderror"
                id="stripe_secret_key" name="stripe_secret_key"
                value="{{ old('stripe_secret_key', isset($paymentSettings['stripe_secret_key']) && $paymentSettings['stripe_secret_key'] ? $paymentSettings['stripe_secret_key'] : '') }}"
                placeholder="Enter Stripe Secret Key">
            @error('stripe_secret_key')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Submit Button --}}
    <div class="col-md-12 text-right">
        <button type="submit" class="btn btn-primary btn-block">
            {{ isset($paymentSettings) ? 'Update' : 'Add' }}
        </button>
    </div>
</div>


<script>
    // Paypal Enabled Function
    function paypalEnabled(value) {
        // alert(value);
        // if value is 1 then add required attributes else remove
        if (value == 'yes') {
            document.getElementById('paypal_client_id').required = true;
            document.getElementById('paypal_secret_id').required = true;
        } else {
            document.getElementById('paypal_client_id').required = false;
            document.getElementById('paypal_secret_id').required = false;
        }
    }

    // Stripe Enabled Function
    function stripeEnabled(value) {
        // alert(value);
        // if value is 1 then add required attributes else remove
        if (value == 'yes') {
            document.getElementById('stripe_publishable_key').required = true;
            document.getElementById('stripe_secret_key').required = true;
        } else {
            document.getElementById('stripe_publishable_key').required = false;
            document.getElementById('stripe_secret_key').required = false;
        }
    }
</script>
