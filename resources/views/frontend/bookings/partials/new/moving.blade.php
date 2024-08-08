{{-- Moving Package Details --}}
<div id="movingPackageDetails" class="row d-none">
    {{-- Floor Size --}}
    <div class="col-md-6" id="floor_size_div">
        <label for="packageHeight">Floor Size</label>
        <div class="input-group mb-3">
            <input type="text" class="form-control moving-field" placeholder="Floor Size" name="floor_size"
                aria-describedby="floor_size"
                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
            <span class="input-group-text text-uppercase" id="floor_size">SQM</span>
        </div>
    </div>
    {{-- No Of Hours --}}
    <div class="col-md-6" id="no_of_hours_div">
        <label for="packageHeight">No of Hours</label>
        <div class="input-group mb-3">
            <input type="text" class="form-control moving-field" placeholder="No of Hours" name="no_of_hours"
                aria-describedby="no_of_hours"
                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
            <span class="input-group-text text-uppercase" id="no_of_hours">Hours</span>
        </div>
    </div>
    {{-- No of Rooms --}}
    <div class="col-md-6" id="no_of_rooms_div">
        <label for="floorPlan">No of Rooms</label>
        <div class="input-group mb-3">
            <select class="form-control moving-field" name="no_of_rooms" aria-label="No of Rooms">
                @forelse($no_of_rooms as $noOfRoom)
                    <option value="{{ $noOfRoom->id }}">{{ $noOfRoom->name }}</option>
                @empty
                    <option value="1">Studio</option>
                @endforelse
            </select>
        </div>
    </div>
    {{-- Floor Plan --}}
    <div class="col-md-6" id="floor_plan_div">
        <label for="floorPlan">Floor Plan</label>
        <div class="input-group mb-3">
            <select class="form-control moving-field" name="floor_plan" aria-label="Floor Plan">
                @forelse($floor_plans as $floor_plan)
                    <option value="{{ $floor_plan->id }}">{{ $floor_plan->name }}</option>
                @empty
                    <option value="1">Ground</option>
                @endforelse
            </select>
        </div>
    </div>
    {{-- Floor Access --}}
    <div class="col-md-6" id="floor_assess_div">
        <label for="floorAssess">Floor Access</label>
        <div class="input-group mb-3">
            <select class="form-control moving-field" name="floor_assess" aria-label="Floor Access">
                @forelse($floor_assess as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @empty
                    <option value="1">Ground</option>
                @endforelse
            </select>
        </div>
    </div>
    {{-- Job Details --}}
    <div class="col-md-12" id="job_details_div">
        <label for="jobDetails">Job Details</label>
        <div class="row form-group mx-3 mb-3">
            @forelse($job_details as $item)
                <div class="col-md-4 form-check">
                    <input class="form-check-input" type="checkbox" name="job_details[]" value="{{ $item->uuid }}">
                    <label class="form-check-label" for="{{ $item->uuid }}">
                        {{ $item->name }}
                    </label>
                </div>
            @empty
                <div class="col-md-4 form-check">
                    <input class="form-check-input" type="checkbox" name="job_details[]" value="loading" id="loading">
                    <label class="form-check-label" for="loading">
                        Loading
                    </label>
                </div>
            @endforelse



        </div>
    </div>

    {{-- Include a Moving Options Multiple Select Field --}}
    @include('frontend.bookings.partials.new.moving-details')

</div>
