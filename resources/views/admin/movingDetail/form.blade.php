{{-- No of Rooms Form --}}
<div class="row">

    {{-- List of $movingDetailCategories --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="moving_detail_category_id">Category</label>
            <select class="form-control @error('moving_detail_category_id') is-invalid @enderror"
                id="moving_detail_category_id" name="moving_detail_category_id" required>
                <option value="" selected disabled>Select Category</option>
                @foreach ($movingDetailCategories as $category)
                    <option value="{{ $category['id'] }}"
                        {{ old('moving_detail_category_id', $movingDetail['moving_detail_category_id'] ?? '') == $category['id'] ? 'selected' : '' }}>
                        {{ $category['name'] }}
                    </option>
                @endforeach
            </select>
            @error('moving_detail_category_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Name --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="name">Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                value="{{ old('name', $movingDetail['name'] ?? '') }}" placeholder="Enter Name" required>
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Weight --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="weight">Weight</label>
            <div class="input-group">
                <input type="text" id="weight" class="form-control" placeholder="Weight" name="weight"
                    value="{{ old('weight', $movingDetail['weight'] ?? '') }}" aria-describedby="weight"
                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                    onchange="calculateVolume()" required>
                <span class="input-group-text text-uppercase" id="weight">Kgs</span>
            </div>
            @error('weight')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>


    {{-- Volume --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="volume">Volume</label>
            <div class="input-group">
                <input type="text" id="volume" class="form-control" placeholder="Volume" name="volume"
                    value="{{ old('volume', $movingDetail['volume'] ?? '') }}" aria-describedby="volume"
                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" readonly>
                <span class="input-group-text text-uppercase" id="volume">Cu Ft</span>
            </div>
            @error('volume')
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
                rows="3" placeholder="Enter Description" required>{{ old('description', $movingDetail['description'] ?? '') }}</textarea>
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
            {{ isset($movingDetail) ? 'Update' : 'Add' }}
        </button>
    </div>


</div>

<script>
    function calculateVolume() {
        // Update volume from weight field -> 1 Kg = 0.041443624614197 Cu Ft
        if ($("#weight").val() == '') {
            $("#volume").val(0);
        }

        $("#volume").val($("#weight").val() * (0.041443624614197));
    }
</script>
