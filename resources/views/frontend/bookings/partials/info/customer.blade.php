{{-- Customer Detail --}}

@if ($booking->client_user_id && $clientData)
    {{--  Customer Detail  --}}
    <div class="card mb-3">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-sm-8">
                    <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
                        <p class="mb-0">Customer Name:</p>
                        <h6 class="mb-0">{{ $clientData->first_name . ' ' . $clientData->last_name }}</h6>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <p class="mb-0">Customer Phone:</p>
                        <h6 class="mb-0">{{ $clientData->phone }}</h6>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <p class="mb-0">Gender</p>
                        <h6 class="mb-0">{{ $clientData->gender }}</h6>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="text-center">
                        <img src="{{ $clientData->profile_image ? asset('images/users/' . $clientData->profile_image) : asset('images/users/default.png') }}"
                            alt="Truck" height="150">
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="text-center">
        <h6> No Customer Information Available </h6>
    </div>
@endif
