{{-- Sub Admin Form --}}
<div class="row">
    {{-- Select Services --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="service_type_id">Service Type</label>
            <select class="form-control @error('service_type_id') is-invalid @enderror" id="service_type_id"
                name="service_type_id" required>
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

    {{-- Category Name --}}
    <div class="col-md-6">
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
                rows="3">{{ old('description', $serviceCategory['description'] ?? '') }}</textarea>
            @error('description')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>


    {{-- Secureship API --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="is_secureship_enabled">Secureship API</label>
            <select class="form-control @error('is_secureship_enabled') is-invalid @enderror" id="is_secureship_enabled"
                name="is_secureship_enabled" onchange="secureshipAPI(this)" required>
                <option value="" selected disabled>Choose Status</option>
                <option value="1"
                    {{ old('is_secureship_enabled', $serviceCategory['is_secureship_enabled'] ?? '') == 1 ? 'selected' : '' }}>
                    Enabled
                </option>
                <option value="0"
                    {{ old('is_secureship_enabled', $serviceCategory['is_secureship_enabled'] ?? '') == 0 ? 'selected' : '' }}>
                    Disabled
                </option>
            </select>
            @error('is_secureship_enabled')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Base Price --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="base_price">Base Price</label>
            <input type="text" class="form-control @error('base_price') is-invalid @enderror" id="base_price"
                name="base_price" value="{{ old('base_price', $serviceCategory['base_price'] ?? '') }}"
                placeholder="Enter Base Price" required pattern="[0-9]*">
            @error('base_price')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Minimum KM Price --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="min_km_price">Minimum KM Price</label>
            <input type="text" class="form-control @error('min_km_price') is-invalid @enderror" id="min_km_price"
                name="min_km_price" value="{{ old('min_km_price', $serviceCategory['min_km_price'] ?? '') }}"
                placeholder="Enter Price Per KM" required pattern="[0-9]*">
            @error('min_km_price')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Price Per KM --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="price_per_km">Price Per KM</label>
            <input type="text" class="form-control @error('price_per_km') is-invalid @enderror" id="price_per_km"
                name="price_per_km" value="{{ old('price_per_km', $serviceCategory['price_per_km'] ?? '') }}"
                placeholder="Enter Price Per KM" required pattern="[0-9]*">
            @error('price_per_km')
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
                <option value="" selected disabled>Choose Status</option>
                <option value="1"
                    {{ old('is_active', $serviceCategory['is_active'] ?? '') == 1 ? 'selected' : '' }}>
                    Active
                </option>
                <option value="0"
                    {{ old('is_active', $serviceCategory['is_active'] ?? '') == 0 ? 'selected' : '' }}>
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
            {{ isset($serviceCategory) ? 'Update' : 'Submit' }}
        </button>
    </div>
</div>


<script>
    function secureshipAPI(element) {
        if (element.value == 1) {
            document.getElementById("base_price").disabled = false;
            document.getElementById("price_per_km").disabled = false;
            document.getElementById("min_km_price").disabled = false;
        } else {
            document.getElementById("base_price").disabled = true;
            document.getElementById("price_per_km").disabled = true;
            document.getElementById("min_km_price").disabled = true;
        }
    }
</script>
