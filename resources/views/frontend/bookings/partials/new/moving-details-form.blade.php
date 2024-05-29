{{-- moving-details-form --}}
<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        @foreach ($movingDetails as $key => $item)
            <button class="nav-link {{ $key == 0 ? 'active' : '' }}" id="nav-{{ $item->name }}-tab" data-bs-toggle="tab"
                data-bs-target="#nav-{{ $item->name }}" type="button" role="tab"
                aria-controls="nav-{{ $item->name }}" aria-selected="true">{{ $item->name }}</button>
        @endforeach
    </div>
</nav>
<div class="tab-content" id="nav-tabContent">
    @foreach ($movingDetails as $key => $item)
        <div class="tab-pane fade {{ $key == 0 ? 'show active' : '' }}" id="nav-{{ $item->name }}" role="tabpanel"
            aria-labelledby="nav-{{ $item->name }}-tab">
            {{-- Create a table to show data --}}
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Weight <span class="fs-xxs">(Kgs)</span></th>
                        <th>Volume <span class="fs-xxs">(Cms)</span></th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($item->movingDetails as $key => $value)
                        <tr>
                            <td id="name_{{ $value->uuid }}">{{ $value->name }}</td>
                            <td id="weight_{{ $value->uuid }}">{{ $value->weight }}</td>
                            <td id="volume_{{ $value->uuid }}">{{ $value->volume }}</td>
                            <td id="quantity_{{ $value->uuid }}">0</td>
                            <td>
                                {{-- Add quantity --}}
                                <button type="button" class="btn btn-sm btn-primary"
                                    onclick="addQuantity({{ $value->uuid }})">
                                    <i class="fas fa-plus"></i>
                                </button>
                                {{-- Subtract quantity --}}
                                <button type="button" class="btn btn-sm btn-danger"
                                    onclick="subtractQuantity({{ $value->uuid }})">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    @endforeach
</div>

{{-- Show calculate summary of weight --}}
<div class="row">
    <h5>Summary</h5>
    <div class="col-md-4">
        <p> No of Items : <span id="movingDetailsTotalItems">0</span></p>
    </div>
    <div class="col-md-4">
        <p> Total Weight : <span id="movingDetailsTotalWeight">0</span> Kgs</p>
    </div>
    <div class="col-md-4">
        <p> Total Volume : <span id="movingDetailsTotalVolume">0</span> cbm</p>
    </div>

</div>

<script>
    function updateMovingDetailsSummary() {
        document.getElementById('movingDetailsTotalItems').innerHTML = movingDetailsTotalItems;
        document.getElementById('movingDetailsTotalWeight').innerHTML = movingDetailsTotalWeight;
        document.getElementById('movingDetailsTotalVolume').innerHTML = movingDetailsTotalVolume;

    }
    // addQuantity
    function addQuantity(uuid) {
        document.getElementById('quantity_' + uuid).innerHTML++;
        // Add to total Items
        movingDetailsTotalItems = movingDetailsTotalItems + 1;
        // Add to total Weight
        movingDetailsTotalWeight = movingDetailsTotalWeight + parseInt(document.getElementById('weight_' + uuid)
            .innerHTML);
        // Add to total Volume
        movingDetailsTotalVolume = movingDetailsTotalVolume + parseInt(document.getElementById('volume_' + uuid)
            .innerHTML);
        // updateMovingDetailsSummary
        updateMovingDetailsSummary();

        // add uuid to selectedMovingDetailsID array
        selectedMovingDetailsID.push(uuid);

        console.log(selectedMovingDetailsID);

    }
    // subtractQuantity
    function subtractQuantity(uuid) {

        // subtract uuid to selectedMovingDetailsID array if that item uuid exist in array
        if (selectedMovingDetailsID.includes(uuid)) {
            const index = selectedMovingDetailsID.indexOf(uuid);
            if (index > -1) {
                selectedMovingDetailsID.splice(index, 1);

                // Only if quantity is more than 0
                if (document.getElementById('quantity_' + uuid).innerHTML > 0) {
                    // Subtract
                    document.getElementById('quantity_' + uuid).innerHTML--;
                }

                // Subtract from total Items
                if (movingDetailsTotalItems > 0) {
                    movingDetailsTotalItems = movingDetailsTotalItems - 1;
                }
                // Subtract from total Weight
                if (movingDetailsTotalWeight > 0) {
                    movingDetailsTotalWeight = movingDetailsTotalWeight - parseInt(document.getElementById('weight_' +
                            uuid)
                        .innerHTML);
                }
                // Subtract from total Volume
                if (movingDetailsTotalVolume > 0) {
                    movingDetailsTotalVolume = movingDetailsTotalVolume - parseInt(document.getElementById('volume_' +
                            uuid)
                        .innerHTML);
                }
            }

        }


        // updateMovingDetailsSummary
        updateMovingDetailsSummary();

        console.log(selectedMovingDetailsID);

    }
</script>
