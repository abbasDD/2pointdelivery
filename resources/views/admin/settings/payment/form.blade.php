<div class="row">
    {{-- Paypal Heading --}}
    <div class="col-md-12">
        <h5>Paypal</h5>
    </div>
    {{-- Paypal Client ID --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="paypal_client_id">Paypal Client ID</label>
            <input type="text" class="form-control @error('paypal_client_id') is-invalid @enderror"
                id="paypal_client_id" name="paypal_client_id"
                value="{{ old('paypal_client_id', $paymentSettings['paypal_client_id'] ?? '') }}"
                placeholder="Enter Paypal Client ID" required>
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
                value="{{ old('paypal_secret_id', $paymentSettings['paypal_secret_id'] ?? '') }}"
                placeholder="Enter Paypal Secret ID" required>
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
    {{-- Stripe Publishable Key --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="stripe_publishable_key">Stripe Publishable Key</label>
            <input type="text" class="form-control @error('stripe_publishable_key') is-invalid @enderror"
                id="stripe_publishable_key" name="stripe_publishable_key"
                value="{{ old('stripe_publishable_key', $paymentSettings['stripe_publishable_key'] ?? '') }}"
                placeholder="Enter Stripe Publishable Key" required>
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
                value="{{ old('stripe_secret_key', $paymentSettings['stripe_secret_key'] ?? '') }}"
                placeholder="Enter Stripe Secret Key" required>
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
