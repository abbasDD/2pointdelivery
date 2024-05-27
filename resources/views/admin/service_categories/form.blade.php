{{-- Sub Admin Form --}}
<div class="row">
    {{-- Select Services --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="service_type_id">Service Type</label>
            <select class="form-control @error('service_type_id') is-invalid @enderror" id="service_type_id"
                name="service_type_id" onchange="getServiceType()" required>
                <option value="" selected disabled>Choose Service Type</option>
                @foreach ($serviceTypes as $serviceType)
                    <option value="{{ $serviceType->id }}"
                        {{ old('service_type_id', $serviceCategory->service_type_id ?? '') == $serviceType->id ? 'selected' : '' }}>
                        {{ $serviceType->name }}
                    </option>
                @endforeach
            </select>
            @error('service_type_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Select Vehicle Type --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="vehicle_type_id">Vehicle Type</label>
            <select class="form-control @error('vehicle_type_id') is-invalid @enderror" id="vehicle_type_id"
                name="vehicle_type_id" required>
                <option value="" selected disabled>Choose Vehicle Type</option>
                @foreach ($vehicleTypes as $vehicleType)
                    <option value="{{ $vehicleType->id }}"
                        {{ old('vehicle_type_id', $serviceCategory->vehicle_type_id ?? '') == $vehicleType->id ? 'selected' : '' }}>
                        {{ $vehicleType->name }}
                    </option>
                @endforeach
            </select>
            @error('vehicle_type_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Category Name --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="name">Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                value="{{ old('name', $serviceCategory['name'] ?? '') }}" placeholder="Enter Name" required>
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Description --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                rows="3" placeholder="Enter Description">{{ old('description', $serviceCategory['description'] ?? '') }}</textarea>
            @error('description')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>


    {{-- Secureship API --}}
    <div class="col-md-12 delivery-field">
        <div class="form-group mb-3">
            <label for="is_secureship_enabled">Secureship API</label>
            <select class="form-control input-field @error('is_secureship_enabled') is-invalid @enderror"
                id="is_secureship_enabled" name="is_secureship_enabled" onchange="secureshipAPI()" required>
                {{-- <option value="" selected disabled>Choose Status</option> --}}
                <option value="0"
                    {{ old('is_secureship_enabled', $serviceCategory['is_secureship_enabled'] ?? '') == 0 ? 'selected' : '' }}>
                    Disabled
                </option>
                <option value="1"
                    {{ old('is_secureship_enabled', $serviceCategory['is_secureship_enabled'] ?? '') == 1 ? 'selected' : '' }}>
                    Enabled
                </option>
            </select>
            @error('is_secureship_enabled')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="row disable-prices {{ $serviceCategory['is_secureship_enabled'] ? 'd-none' : '' }}">
        {{-- Base Price --}}
        <div class="col-md-6" id="base_price_div">
            <div class="form-group mb-3">
                <label for="base_price">Base Price</label>
                <div class="input-group">
                    <input type="text" class="form-control input-field @error('base_price') is-invalid @enderror"
                        id="base_price" name="base_price"
                        value="{{ old('base_price', $serviceCategory['base_price'] ?? '') }}"
                        placeholder="Enter Base Price" required pattern="\d*" inputmode="numeric"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    <span class="input-group-text text-uppercase" id="base_price">$</span>
                </div>
                @error('base_price')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        {{-- Base Price Distance --}}
        <div class="col-md-6" id="base_distance_div">
            <div class="form-group mb-3">
                <label for="base_distance">Base Distance</label>
                <div class="input-group">
                    <input type="text" class="form-control input-field @error('base_distance') is-invalid @enderror"
                        id="base_distance" name="base_distance"
                        value="{{ old('base_distance', $serviceCategory['base_distance'] ?? '') }}"
                        placeholder="Enter Distance for Base Price" required pattern="[0-9]*"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    <span class="input-group-text text-uppercase" id="base_distance">Km</span>
                </div>
                @error('base_distance')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        {{-- Extra Distance Price --}}
        <div class="col-md-6" id="extra_distance_price_div">
            <div class="form-group mb-3">
                <label for="extra_distance_price">Extra Distance Price</label>
                <div class="input-group">
                    <input type="text"
                        class="form-control input-field @error('extra_distance_price') is-invalid @enderror"
                        id="extra_distance_price" name="extra_distance_price"
                        value="{{ old('extra_distance_price', $serviceCategory['extra_distance_price'] ?? '') }}"
                        placeholder="Enter Extra Distance Price" required pattern="[0-9]*"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    <span class="input-group-text text-uppercase" id="extra_distance_price">$</span>
                </div>
                @error('extra_distance_price')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        {{-- Base Weight --}}
        <div class="col-md-6" id="base_weight_div">
            <div class="form-group mb-3">
                <label for="base_weight">Base Weight</label>
                <div class="input-group">
                    <input type="text" class="form-control input-field @error('base_weight') is-invalid @enderror"
                        id="base_weight" name="base_weight"
                        value="{{ old('base_weight', $serviceCategory['base_weight'] ?? '') }}"
                        placeholder="Enter Base Weight" required pattern="[0-9]*"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    <span class="input-group-text text-uppercase" id="base_weight">Kgs</span>
                </div>
                @error('base_weight')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        {{-- Exta Weight Price --}}
        <div class="col-md-6" id="extra_weight_price_div">
            <div class="form-group mb-3">
                <label for="extra_weight_price">Base Weight Price</label>
                <div class="input-group">
                    <input type="text"
                        class="form-control input-field @error('extra_weight_price') is-invalid @enderror"
                        id="extra_weight_price" name="extra_weight_price"
                        value="{{ old('extra_weight_price', $serviceCategory['extra_weight_price'] ?? '') }}"
                        placeholder="Enter Base Weight Price" required pattern="[0-9]*"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    <span class="input-group-text text-uppercase" id="extra_weight_price">$</span>
                </div>
                @error('extra_weight_price')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        {{-- Helper Fee --}}
        <div class="col-md-6" id="helper_fee_div">
            <div class="form-group mb-3">
                <label for="helper_fee">Helper Fee</label>
                <div class="input-group">
                    <input type="text" class="form-control input-field @error('helper_fee') is-invalid @enderror"
                        id="helper_fee" name="helper_fee"
                        value="{{ old('helper_fee', $serviceCategory['helper_fee'] ?? '') }}"
                        placeholder="Enter Helper Fee" required pattern="[0-9]*"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    <span class="input-group-text text-uppercase" id="helper_fee">$</span>
                </div>
                @error('helper_fee')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        {{-- Volume Enabled --}}
        <div class="col-md-6 delivery-field" id="volume_enabled_div">
            <div class="form-group mb-3">
                <label for="volume_enabled">Volume Enabled</label>
                <select class="form-control input-field @error('volume_enabled') is-invalid @enderror"
                    id="volume_enabled" name="volume_enabled" required>
                    {{-- <option value="" selected disabled>Choose Volume Status</option> --}}
                    <option value="1"
                        {{ old('volume_enabled', $serviceCategory['volume_enabled'] ?? '') == 1 ? 'selected' : '' }}>
                        Enabled
                    </option>
                    <option value="0"
                        {{ old('volume_enabled', $serviceCategory['volume_enabled'] ?? '') == 0 ? 'selected' : '' }}>
                        Disabled
                    </option>
                </select>
                @error('volume_enabled')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>


        {{-- moving_price_type --}}
        <div class="col-md-6 moving-field">
            <div class="form-group mb-3">
                <label for="moving_price_type">Moving Price Type</label>
                <select class="form-control input-field @error('moving_price_type') is-invalid @enderror"
                    id="moving_price_type" name="moving_price_type">
                    {{-- <option value="" selected disabled>Choose Price Type</option> --}}
                    <option value="hour" @if (old('moving_price_type', $serviceCategory['moving_price_type'] ?? '') == 'hour') selected @endif>No of Hours</option>
                    <option value="sqm" @if (old('moving_price_type', $serviceCategory['moving_price_type'] ?? '') == 'day') selected @endif>Floor Size (SQM)</option>
                </select>
                @error('moving_price_type')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>



    {{-- Submit Button --}}
    <div class="col-md-12 text-right">
        <button type="submit" class="btn btn-primary btn-block">
            {{ isset($serviceCategory->id) ? 'Update' : 'Add' }}
        </button>
    </div>
</div>


<script>
    // Store serviceTypes to javascript

    let serviceTypes = {!! json_encode($serviceTypes) !!};

    function secureshipAPI() {
        let element = document.getElementById('is_secureship_enabled');
        if (element.value == 0) {
            // Add Prices fields DIV
            $(".disable-prices").removeClass("d-none");
            // Add Required Attributes
            $(".input-field").attr("required", "required");
        } else {
            // Remove Prices fields DIV
            $(".disable-prices").addClass("d-none");
            // Remove Required Attributes
            $(".input-field").removeAttr("required");
        }
    }

    // Call on load as well 

    window.onload = function() {
        secureshipAPI();
        getServiceType();
    }


    // getServiceType

    function getServiceType() {
        // Get input value of vehicle_type_id 
        let serviceTypeID = document.getElementById('service_type_id').value;
        console.log(serviceTypeID);

        // Get Service Type where index is equal to serviceTypeID
        var index = 0;
        for (let i = 0; i < serviceTypes.length; i++) {
            if (serviceTypes[i].id == serviceTypeID) {
                index = i;
                break;
            }
        }

        // Check if type is moving
        if (serviceTypes[index].type == 'moving') {
            // Add d-none class to delivery-field class
            $(".delivery-field").addClass("d-none");
            $(".moving-field").removeClass("d-none");

            $(".disable-prices").removeClass("d-none");
            $(".disable-prices input").attr("required", "required");
        } else {
            // Remove d-none class from delivery-field
            $(".delivery-field").removeClass("d-none");
            $(".moving-field").addClass("d-none");

            secureshipAPI();
        }
    }


    // // Adjust required field
    // function requiredField() {
    //     // Get input value of is_secureship_enabled
    //     let element = document.getElementById('is_secureship_enabled');
    //     // If enabled remove required from all fields
    //     if (element.value == 1) {
    //         // Add Required Attributes
    //         $(".input-field").removeAttr("required");
    //     }
    // }
</script>
