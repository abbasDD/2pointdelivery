<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title mb-0">Vehicle</h5>
    </div>
    {{-- IF vehicleTypes is empty show error --}}
    @if (count($vehicleTypes) == 0)
        <div class="card-body">
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">No Vehicle Types Found</h4>
                <p class="mb-0">Please add vehicle types</p>
            </div>
        </div>
    @else
        <form action="{{ route('helper.update.vehicle') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                {{-- Vehicle Detail --}}
                <div class="row">

                    {{-- Vehicle Type --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="vehicle_number" class="form-label">Vehicle Type</label>
                            <select id="vehicle_type_id"
                                class="form-control @error('vehicle_type_id') is-invalid @enderror"
                                name="vehicle_type_id" required autocomplete="vehicle_type_id" autofocus>
                                <option value="" selected disabled>Choose Vehicle Type</option>
                                @foreach ($vehicleTypes as $vehicleType)
                                    <option value="{{ $vehicleType->id }}"
                                        {{ old('vehicle_type_id', $vehicleData['vehicle_type_id'] ?? '') == $vehicleType->id ? 'selected' : '' }}>
                                        {{ $vehicleType->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('vehicle_type_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    {{-- Vehicle Number --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="vehicle_number" class="form-label">Vehicle Number</label>
                            <input type="text" class="form-control" id="vehicle_number" name="vehicle_number"
                                value="{{ old('vehicle_number', $vehicleData['vehicle_number'] ?? '') }}"
                                placeholder="Vehicle Number" required>
                        </div>
                        @error('vehicle_number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    {{-- Vehicle Make --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="vehicle_make" class="form-label">Vehicle Make</label>
                            <input type="text" class="form-control" id="vehicle_make" name="vehicle_make"
                                value="{{ old('vehicle_make', $vehicleData['vehicle_make'] ?? '') }}"
                                placeholder="Vehicle Make" required>
                            @error('vehicle_make')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    {{-- Vehicle Model --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="vehicle_model" class="form-label">Vehicle Model</label>
                            <input type="text" class="form-control" id="vehicle_model" name="vehicle_model"
                                value="{{ old('vehicle_model', $vehicleData['vehicle_model'] ?? '') }}"
                                placeholder="Vehicle Model" required>
                            @error('vehicle_model')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    {{-- Vehicle Color --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="vehicle_color" class="form-label">Vehicle Color</label>
                            <input type="text" class="form-control" id="vehicle_color" name="vehicle_color"
                                value="{{ old('vehicle_color', $vehicleData['vehicle_color'] ?? '') }}"
                                placeholder="Vehicle Color" required>
                            @error('vehicle_color')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    {{-- Vehicle Year --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="vehicle_year" class="form-label">Vehicle Year</label>
                            <input type="text" class="form-control" id="vehicle_year" name="vehicle_year"
                                value="{{ old('vehicle_year', $vehicleData['vehicle_year'] ?? '') }}"
                                placeholder="Vehicle Year" required>
                            @error('vehicle_year')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- Row End Here --}}
                </div>
                {{-- Button to Submit --}}
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            </div>
        </form>
    @endif
</div>


<script>
    // Profile Image JS
    document.querySelector('#profile_image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file.');
            event.target.value = null;
            return;
        }

        const reader = new FileReader();
        reader.onload = (event) => {
            document.querySelector('#profile_image_preview').src = event.target.result;
        }

        reader.readAsDataURL(file);
    });
</script>
