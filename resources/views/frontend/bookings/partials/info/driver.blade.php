{{-- Driver Detail --}}

@if ($booking->helper_user_id)
    {{-- Delivery Driver Detail  --}}
    <div class="card mb-3">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-sm-8">
                    <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
                        <p class="mb-0">Driver Name:</p>
                        <h6 class="mb-0">{{ $helperData->first_name . ' ' . $helperData->last_name }}</h6>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <p class="mb-0">Driver Phone:</p>
                        <h6 class="mb-0">{{ $helperData->phone }}</h6>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <p class="mb-0">Gender</p>
                        <h6 class="mb-0">{{ $helperData->gender }}</h6>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="text-center">
                        <img src="{{ $helperData->profile_image ? asset('images/users/' . $helperData->profile_image) : asset('images/users/default.png') }}"
                            alt="Truck" height="150">
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="text-center">
        <h6> No Driver Information Available </h6>
    </div>
@endif
