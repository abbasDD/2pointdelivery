{{-- Vehicle Detail --}}

@if ($booking->helper_id)
    {{-- Delivery Vehicle Detail  --}}
    <div class="card mb-3">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <p class="mb-0">Vehicle type:</p>
                        <h6 class="mb-0">Bike</h6>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <p class="mb-0">Make:</p>
                        <h6 class="mb-0">Suzuki Motors</h6>
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
                        <img src="{{ asset('images/vehicles/bike.png') }}" alt="Truck" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
