<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Images</h5>
    </div>
    <div class="card-body">
        <div class="">
            <p class="mb-0">Start Booking Image</p>
            <div class="d-flex align-items-center justify-content-between mb-3">
                <img src="{{ $bookingDelivery->start_booking_image ? asset('images/bookings/' . $bookingDelivery->start_booking_image) : asset('images/bookings/default.png') }}"
                    alt="Truck" height="150">
            </div>
        </div>
        <div class="">
            <p class="mb-0">Complete Booking Image</p>
            <div class="d-flex align-items-center justify-content-between mb-3">
                <img src="{{ $bookingDelivery->complete_booking_image ? asset('images/bookings/' . $bookingDelivery->complete_booking_image) : asset('images/bookings/default.png') }}"
                    alt="Truck" height="150">
            </div>
        </div>
    </div>
</div>
