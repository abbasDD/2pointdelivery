{{-- Receiver Detail --}}

@if ($booking)
    {{--  Receiver Detail  --}}
    <div class="card mb-3">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <div class="d-block d-md-flex align-items-center justify-content-between mb-3">
                        <p class="mb-0">Receiver Name:</p>
                        <h6 class="mb-0">{{ $booking->receiver_name ? $booking->receiver_name : '-' }}</h6>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <p class="mb-0">Receiver Phone:</p>
                        <h6 class="mb-0">{{ $booking->receiver_phone ? $booking->receiver_phone : '-' }}</h6>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <p class="mb-0">Receiver Email</p>
                        <h6 class="mb-0">{{ $booking->receiver_email ? $booking->receiver_email : '-' }}</h6>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <p class="mb-0">Delivery Note</p>
                        <h6 class="mb-0">{{ $booking->delivery_note ? $booking->delivery_note : '-' }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="text-center">
        <h6> No Receiver Information Available </h6>
    </div>
@endif
