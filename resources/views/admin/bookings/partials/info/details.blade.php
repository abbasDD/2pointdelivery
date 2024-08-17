{{-- Order Detail --}}

<div class="card mb-3">
    <div class="card-body">
        {{-- Package Value: --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <p class="mb-0">Service Type:</p>
            <h6 class="mb-0">{{ $booking->serviceType->name }}</h6>
        </div>
        {{-- Delivery Time: --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <p class="mb-0">Pickup Address</p>
            <h6 class="mb-0">{{ $booking->pickup_address }}</h6>
        </div>
        {{-- Receiver Details: --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <p class="mb-0">Dropoff Address</p>
            <h6 class="mb-0">{{ $booking->dropoff_address }}</h6>
        </div>
        {{-- Helper Fee: --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <p class="mb-0">Helper Fee:</p>
            <h6 class="mb-0">${{ $bookingData->helper_fee }}</h6>
        </div>
        {{-- Amount to Pay --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <p class="mb-0">Amount to Pay:</p>
            <h6 class="mb-0">${{ $booking->total_price }}</h6>
        </div>
        {{-- Payment Method --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <p class="mb-0">Payment Method:</p>
            <h6 class="mb-0">{{ $bookingData->payment_method }}</h6>
        </div>
    </div>
</div>
