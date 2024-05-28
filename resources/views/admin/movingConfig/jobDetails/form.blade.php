{{-- No of Rooms Form --}}
<div class="row">
    {{-- Name --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="name">Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                value="{{ old('name', $jobDetails['name'] ?? '') }}" placeholder="Enter Name" required>
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Price --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="price">Price</label>
            <div class="input-group">
                <input type="text" id="price" class="form-control" placeholder="Price" name="price"
                    value="{{ old('price', $jobDetails['price'] ?? '') }}" aria-describedby="price"
                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
                <span class="input-group-text text-uppercase" id="price">$</span>
            </div>
            @error('price')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Decription --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                rows="3" placeholder="Enter Description" required>{{ old('description', $jobDetails['description'] ?? '') }}</textarea>
            @error('description')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Helper Fee --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="helper_fee">Helper Fee</label>
            <div class="input-group">
                <input type="text" id="helper_fee" class="form-control" placeholder="Helper Fee" name="helper_fee"
                    value="{{ old('helper_fee', $jobDetails['helper_fee'] ?? '') }}" aria-describedby="helper_fee"
                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
                <span class="input-group-text text-uppercase" id="helper_fee">$</span>
            </div>
            @error('helper_fee')
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
                <option value="">Select Status</option>
                <option value="1" {{ old('is_active', $jobDetails['is_active'] ?? '') == 1 ? 'selected' : '' }}>
                    Active
                </option>
                <option value="0" {{ old('is_active', $jobDetails['is_active'] ?? '') == 0 ? 'selected' : '' }}>
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
            {{ isset($jobDetails) ? 'Update' : 'Add' }}
        </button>
    </div>
</div>
