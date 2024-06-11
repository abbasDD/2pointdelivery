{{-- Mover Detail --}}

@if ($booking->helper_user_id || $booking->helper_user_id2)
    {{-- Delivery Mover Detail  --}}
    <div class="row align-items-center">
        <div class="col-md-6">
            {{-- First Mover --}}
            @if (isset($helperData))
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
                            <h5>Mover 01 </h5>
                            <img src="{{ $helperData->image ? asset('images/users/' . $helperData->image) : asset('images/users/default.png') }}"
                                alt="User" width="50">
                        </div>
                        <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
                            <p class="mb-0">Mover Name:</p>
                            <h6 class="mb-0">{{ $helperData->first_name . ' ' . $helperData->last_name }}</h6>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <p class="mb-0">Mover Phone:</p>
                            <h6 class="mb-0">{{ $helperData->phone_no }}</h6>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <p class="mb-0">Gender</p>
                            <h6 class="mb-0">{{ $helperData->gender }}</h6>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-md-6">
            {{-- Second Mover --}}
            @if (isset($helperData2))
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
                            <h5>Mover 02 </h5>
                            <img src="{{ $helperData2->image ? asset('images/users/' . $helperData2->image) : asset('images/users/default.png') }}"
                                alt="User" width="50">
                        </div>
                        <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
                            <p class="mb-0">Mover Name:</p>
                            <h6 class="mb-0">{{ $helperData2->first_name . ' ' . $helperData2->last_name }}</h6>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <p class="mb-0">Mover Phone:</p>
                            <h6 class="mb-0">{{ $helperData2->phone_no }}</h6>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <p class="mb-0">Gender</p>
                            <h6 class="mb-0">{{ $helperData2->gender }}</h6>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center">
                    <h6> Mover 02 have not been assigned </h6>
                </div>
            @endif
        </div>
    </div>
@else
    <div class="text-center">
        <h6> No Mover Information Available </h6>
    </div>
@endif
