{{-- Moving Details List --}}
<div class="col-md-12">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <label class="mb-0" for="movingDetails">Moving Details</label>
        <button type="button" class="btn btn-primary btn-sm" onclick="openMovingDetailModal()">Add</button>
    </div>
    <div class="row form-group mx-3 mb-3">
        {{-- Show a js array selectedMovingDetailsID value in textarea --}}
        {{-- <textarea class="form-control moving-field" name="moving_details" id="movingDetails" rows="3"
            placeholder="Moving details" disabled></textarea> --}}
        <div class="col-md-4">
            <p> No of Items : <span id="movingDetailsFormTotalItems">0</span></p>
        </div>
        <div class="col-md-4">
            <p> Total Weight : <span id="movingDetailsFormTotalWeight">0</span> Kgs</p>
        </div>
        <div class="col-md-4">
            <p> Total Volume : <span id="movingDetailsFormTotalVolume">0</span> cbm</p>
        </div>
    </div>
</div>

{{-- Moving Details Modal --}}
<div class="modal fade" id="movingDetailModal" tabindex="-1" aria-labelledby="movingDetailModalLabel"
    aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="movingDetailModalLabel">Moving Details</h5>
            </div>
            <div class="modal-body">
                {{-- Include movin details form --}}
                @include('frontend.bookings.partials.new.moving-details-form')

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" onclick="updateMovingDetailsFormSummary()" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>


<script>
    // openMovingDetailModal
    function openMovingDetailModal() {
        $('#movingDetailModal').modal('show');
    }
    // Update on Save button
    function updateMovingDetailsFormSummary() {
        document.getElementById('movingDetailsFormTotalItems').innerHTML = movingDetailsTotalItems;
        document.getElementById('movingDetailsFormTotalWeight').innerHTML = movingDetailsTotalWeight;
        document.getElementById('movingDetailsFormTotalVolume').innerHTML = movingDetailsTotalVolume;
        // Hide Modal
        $('#movingDetailModal').modal('hide');
    }
</script>
