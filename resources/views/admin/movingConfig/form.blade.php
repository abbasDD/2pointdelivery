{{-- Moving Config  Form --}}
<div class="row">
    {{-- no_of_room_price --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="no_of_room_price">No of Room Price</label>
            <input type="text" class="form-control @error('no_of_room_price') is-invalid @enderror"
                id="no_of_room_price" name="no_of_room_price"
                value="{{ old('no_of_room_price', $movingConfig['no_of_room_price'] ?? '') }}" placeholder="Enter Price"
                required oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
            @error('no_of_room_price')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    {{-- floor_plan_price --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="floor_plan_price">Floor Plan Price</label>
            <input type="text" class="form-control @error('floor_plan_price') is-invalid @enderror"
                id="floor_plan_price" name="floor_plan_price"
                value="{{ old('floor_plan_price', $movingConfig['floor_plan_price'] ?? '') }}" placeholder="Enter Price"
                required oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
            @error('floor_plan_price')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    {{-- floor_access_price --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="floor_access_price">Floor Access Price</label>
            <input type="text" class="form-control @error('floor_access_price') is-invalid @enderror"
                id="floor_access_price" name="floor_access_price"
                value="{{ old('floor_access_price', $movingConfig['floor_access_price'] ?? '') }}"
                placeholder="Enter Price" required
                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
            @error('floor_access_price')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    {{-- job_details_price --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="job_details_price">Job Details Price</label>
            <input type="text" class="form-control @error('job_details_price') is-invalid @enderror"
                id="job_details_price" name="job_details_price"
                value="{{ old('job_details_price', $movingConfig['job_details_price'] ?? '') }}"
                placeholder="Enter Price" required
                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
            @error('job_details_price')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    {{-- Submit Button --}}
    <div class="col-md-12 text-right">
        <button type="submit" class="btn btn-primary btn-block">
            {{ isset($movingConfig) ? 'Update' : 'Add' }}
        </button>
    </div>
</div>
