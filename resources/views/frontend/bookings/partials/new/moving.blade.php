{{-- Moving Package Details --}}
<div id="movingPackageDetails" class="row d-none">
    {{-- Floor Size --}}
    <div class="col-md-6">
        <label for="packageHeight">Floor Size</label>
        <div class="input-group mb-3">
            <input type="text" class="form-control moving-field" placeholder="Floor Size" name="floor_size"
                aria-describedby="floor_size"
                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                onchange="updatePaymentAmount()">
            <span class="input-group-text text-uppercase" id="floor_size">SQ FT</span>
        </div>
    </div>
    {{-- Floor Plan --}}
    <div class="col-md-6">
        <label for="floorPlan">Floor Plan</label>
        <div class="input-group mb-3">
            <select class="form-control moving-field" name="floor_plan" aria-label="Floor Plan"
                onchange="updatePaymentAmount()">
                <option value="ground">Ground Floor</option>
                <option value="1st">1st Floor</option>
                <option value="2nd">2nd Floor</option>
                <option value="3rd">3rd Floor</option>
            </select>
        </div>
    </div>
    {{-- Floor Assess --}}
    <div class="col-md-6">
        <label for="floorAssess">Floor Assess</label>
        <div class="input-group mb-3">
            <select class="form-control moving-field" name="floor_assess" aria-label="Floor Assess"
                onchange="updatePaymentAmount()">
                <option value="elevator">Elevator</option>
                <option value="stairs">Stairs</option>
            </select>
        </div>
    </div>
    {{-- Job Details --}}
    <div class="col-md-12">
        <label for="jobDetails">Job Details</label>
        <div class="row form-group mx-3 mb-3">
            <div class="col-md-4 form-check">
                <input class="form-check-input" type="checkbox" name="job_details[]" value="packing" id="packing">
                <label class="form-check-label" for="packing">
                    Packing
                </label>
            </div>
            <div class="col-md-4 form-check">
                <input class="form-check-input" type="checkbox" name="job_details[]" value="loading" id="loading">
                <label class="form-check-label" for="loading">
                    Loading
                </label>
            </div>
            <div class="col-md-4 form-check">
                <input class="form-check-input" type="checkbox" name="job_details[]" value="off_loading"
                    id="off_loading">
                <label class="form-check-label" for="off_loading">
                    Off Loading
                </label>
            </div>
        </div>
    </div>
    {{-- Moving Details --}}
    <div class="col-md-12">
        <div class="mb-3">
            <label for="movingDetails">Moving Details</label>
            <textarea class="form-control moving-field" name="moving_details" id="movingDetails" rows="3"
                placeholder="Enter moving details"></textarea>
        </div>
    </div>
</div>
