{{-- Address Book Form --}}
<div class="row">
    {{-- Pickup Location --}}
    <div class="col-md-6">
        <div class="mb-3">
            <label for="pickup_address">Pickup Location</label>
            <input id="pickup_address" class="form-control" type="text" name="pickup_address"
                placeholder="Enter pickup location" value="{{ $addressBook->pickup_address }}" required>
            <input type="hidden" id="pickup_latitude" name="pickup_latitude"
                value="{{ $addressBook->pickup_latitude }}" required>
            <input type="hidden" id="pickup_longitude" name="pickup_longitude"
                value="{{ $addressBook->pickup_longitude }}" required>
        </div>
    </div>
    {{-- Delivery Location --}}
    <div class="col-md-6">
        <div class="mb-3">
            <label for="dropoff_address">Delivery Location</label>
            <input id="dropoff_address" class="form-control" type="text" name="dropoff_address"
                placeholder="Enter delivery location" value="{{ $addressBook->dropoff_address }}" required>
            <input type="hidden" id="dropoff_latitude" name="dropoff_latitude"
                value="{{ $addressBook->dropoff_latitude }}" required>
            <input type="hidden" id="dropoff_longitude" name="dropoff_longitude"
                value="{{ $addressBook->dropoff_longitude }}" required>
        </div>
    </div>

    {{-- Load JS of Map --}}
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ config('google_map_api_key') ?? 'Your API Key' }}&libraries=places">
    </script>
    <script>
        var options = {
            componentRestrictions: {
                country: ["ca"]
            }
        };

        var pickupAutocomplete = new google.maps.places.Autocomplete(document.getElementById('pickup_address'), options);
        var deliveryAutocomplete = new google.maps.places.Autocomplete(document.getElementById('dropoff_address'),
            options);

        pickupAutocomplete.addListener('place_changed', function() {
            var place = pickupAutocomplete.getPlace();
            document.getElementById('pickup_latitude').value = place.geometry.location.lat();
            document.getElementById('pickup_longitude').value = place.geometry.location.lng();
        });
        deliveryAutocomplete.addListener('place_changed', function() {
            var place = deliveryAutocomplete.getPlace();
            document.getElementById('dropoff_latitude').value = place.geometry.location.lat();
            document.getElementById('dropoff_longitude').value = place.geometry.location.lng();
        });
    </script>
</div>

{{-- Receiver Details --}}
<div class="row">
    {{-- Receiver Name --}}
    <div class="col-md-6">
        <div class="mb-3">
            <label for="receiver_name">Receiver Name</label>
            <input type="text" class="form-control" id="receiver_name" name="receiver_name"
                value="{{ $addressBook->receiver_name }}" placeholder="Enter receiver name" required>
        </div>
    </div>
    {{-- Receiver Email --}}
    <div class="col-md-6">
        <div class="mb-3">
            <label for="receiver_email">Receiver Email</label>
            <input type="email" class="form-control" id="receiver_email" name="receiver_email"
                value="{{ $addressBook->receiver_email }}" placeholder="Enter receiver email">
        </div>
    </div>
    {{-- Receiver Phone --}}
    <div class="col-md-6">
        <div class="mb-3">
            <label for="receiver_phone">Receiver Phone</label>
            <input type="text" class="form-control" id="receiver_phone" name="receiver_phone"
                value="{{ $addressBook->receiver_phone }}" placeholder="Enter receiver phone" required>
        </div>
    </div>
</div>

{{-- Submit Button --}}
<div class="row">
    <div class="col-md-12 text-right">
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </div>
</div>
