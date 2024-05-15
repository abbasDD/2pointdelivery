{{-- Vehicle Detail --}}

@if (isset($vehicleTypeData))
    {{-- Delivery Vehicle Detail  --}}
    <div class="card mb-3">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <p class="mb-0">Vehicle type:</p>
                        <h6 class="mb-0">{{ $vehicleTypeData->name }}</h6>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <p class="mb-0">Description:</p>
                        <h6 class="mb-0">{{ $vehicleTypeData->description ? $vehicleTypeData->description : 'N/A' }}
                        </h6>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <p class="mb-0">Model:</p>
                        <h6 class="mb-0">2018</h6>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <p class="mb-0">Number:</p>
                        <h6 class="mb-0">RIL 123</h6>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="text-center">
                        <img src="{{ $vehicleTypeData->image ? asset('images/vehicle_types/' . $vehicleTypeData->image) : asset('images/vehicle_types/bike.png') }}"
                            alt="Truck" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="text-center">
        <h6> No Vehicle Available </h6>
    </div>
@endif
