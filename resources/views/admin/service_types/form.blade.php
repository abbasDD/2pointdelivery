{{-- Form --}}
<div class="row">
    {{-- Service Image --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <div class="image-selection">
                <div class="mx-auto" style="max-width: 150px;">
                    <img id="image_img"
                        src="{{ isset($serviceType) && $serviceType->image !== null ? asset('images/service_types/' . $serviceType->image) : asset('images/service_types/default.png') }}"
                        alt="image" class="p-3 border w-100" onclick="document.getElementById('image').click()">
                    <input type="file" name="image" id="image" class="d-none" accept="image/*"
                        @if (!isset($serviceType)) required @endif>
                </div>
            </div>
            @if ($errors->has('image'))
                <span class="invalid-feedback" role="alert">
                    <strong>Image is required</strong>
                </span>
            @endif
        </div>
    </div>
    {{-- Select Services --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="type">Service Type</label>
            <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                <option value="" selected disabled>Choose Service Type</option>
                <option value="delivery" {{ old('type', $serviceType['type'] ?? '') == 'delivery' ? 'selected' : '' }}>
                    Delivery</option>
                <option value="moving" {{ old('type', $serviceType['type'] ?? '') == 'moving' ? 'selected' : '' }}>
                    Moving</option>
            </select>
            @error('type')
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
            {{-- Add hidden field of id in form if not empty --}}
            @isset($serviceType)
                <input type="hidden" name="id" value="{{ $serviceType['id'] }}">
            @endisset
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                value="{{ old('name', $serviceType['name'] ?? '') }}" placeholder="Enter Name" required>
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
                rows="3">{{ old('description', $serviceType['description'] ?? '') }}</textarea>
            @error('description')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>


    {{-- Submit Button --}}
    <div class="col-md-12 text-right">
        <button type="submit" class="btn btn-primary btn-block">
            {{ isset($serviceType) ? 'Update' : 'Add' }}
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
