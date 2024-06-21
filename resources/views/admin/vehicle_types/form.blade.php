{{-- Vehicle Type Form --}}
<div class="row">
    {{-- Vehicle Image --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <div class="image-selection">
                <div class="mx-auto" style="max-width: 150px;">
                    <img id="image_img"
                        src="{{ isset($vehicle_type) && $vehicle_type->image !== null ? asset('images/vehicle_types/' . $vehicle_type->image) : asset('images/vehicle_types/default.png') }}"
                        alt="image" class="p-3 border w-100" onclick="document.getElementById('image').click()">
                    <input type="file" name="image" id="image" class="d-none" accept="image/*"
                        @if (!isset($vehicle_type)) required @endif>
                </div>
            </div>
            @if ($errors->has('image'))
                <span class="invalid-feedback" role="alert">
                    <strong>Image is required</strong>
                </span>
            @endif
        </div>
    </div>
    {{-- Vehicle Name --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="name">Vehicle Name</label>
            {{-- Add hidden field of id in form if not empty --}}
            @isset($vehicle_type)
                <input type="hidden" name="id" value="{{ $vehicle_type['id'] }}">
            @endisset
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                value="{{ old('name', $vehicle_type['name'] ?? '') }}" placeholder="Enter Vehicle Name" required>
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
                placeholder="Enter Description" rows="3" required>{{ old('description', $vehicle_type['description'] ?? '') }}</textarea>
            @error('description')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    {{-- price_type --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="price_type">Price Type</label>
            <select class="form-control @error('price_type') is-invalid @enderror" id="price_type" name="price_type">
                <option value="" selected disabled>Choose Price Type</option>
                <option value="km" @if (old('price_type', $vehicle_type['price_type'] ?? '') == 'km') selected @endif>KM</option>
                <option value="hour" @if (old('price_type', $vehicle_type['price_type'] ?? '') == 'hour') selected @endif>Hour</option>
                <option value="day" @if (old('price_type', $vehicle_type['price_type'] ?? '') == 'day') selected @endif>Day</option>
            </select>
            @error('price_type')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Vehicle Price --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="price">Vehicle Price</label>
            <div class="input-group">
                <input type="text" class="form-control @error('price') is-invalid @enderror" id="price"
                    name="price" value="{{ old('price', $vehicle_type['price'] ?? '') }}"
                    placeholder="Enter Vehicle Price" pattern="\d+(\.\d{0,2})?" inputmode="decimal"
                    oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/^(\d*\.?\d{0,2}).*$/g, '$1');"
                    required>
                <span class="input-group-text text-uppercase" id="price">$</span>
            </div>
            @error('price')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Service Available for --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label>Service Available for <span class="text-danger">*</span></label>
            <div class="d-block">
                @foreach ($services as $service)
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" id="service-{{ $service->id }}"
                            name="services[]" value="{{ $service->id }}"
                            @if (isset($vehicle_type) && in_array($service->id, $vehicle_type->service_types->pluck('id')->toArray())) checked @endif>
                        <label class="custom-control-label"
                            for="service-{{ $service->id }}">{{ $service->name }}</label>
                    </div>
                @endforeach
                @error('services')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                @if ($errors->has('services'))
                    <span class="help-block text-danger">
                        <strong>Please select atleast one service</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Submit Button --}}
    <div class="col-md-12 text-right">
        <button type="submit" class="btn btn-primary btn-block">
            {{ isset($vehicle_type) ? 'Update' : 'Add' }}
        </button>
    </div>
</div>

<script>
    document.querySelector('#image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file.');
            event.target.value = null;
            return;
        }

        const reader = new FileReader();
        reader.onload = (event) => {
            document.querySelector('#image_img').src = event.target.result;
        }

        reader.readAsDataURL(file);
    });
</script>
