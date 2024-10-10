{{-- Order Detail --}}

<div class="card mb-3">
    <div class="card-body">
        {{-- Service Type: --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <p class="mb-0">Service Type:</p>
            <h6 class="mb-0">{{ $booking->serviceType->name }}</h6>
        </div>
        {{-- Service Category: --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <p class="mb-0">Service Category:</p>
            <h6 class="mb-0">{{ $booking->serviceCategory->name }}</h6>
        </div>
        {{-- Pickup Address: --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <p class="mb-0">Pickup Address</p>
            <h6 class="mb-0">{{ $booking->pickup_address }}</h6>
        </div>
        {{-- Dropoff Address: --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <p class="mb-0">Dropoff Address</p>
            <h6 class="mb-0">{{ $booking->dropoff_address }}</h6>
        </div>
        @if ($booking->booking_type != 'secureship')
            {{-- Helper Fee: --}}
            <div class="d-flex align-items-center justify-content-between mb-3">
                <p class="mb-0">Helper Fee:</p>
                <h6 class="mb-0">${{ $bookingData->helper_fee }}</h6>
            </div>
        @endif
        {{-- Amount to Pay --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <p class="mb-0">Amount to Pay:</p>
            <h6 class="mb-0">${{ $booking->total_price }}</h6>
        </div>
        {{-- Payment Method --}}
        <div class="d-flex align-items-center justify-content-between mb-3">
            <p class="mb-0">Payment Method:</p>
            <h6 class="mb-0">{{ $bookingData->payment_method ?? 'N/A' }}</h6>
        </div>

        {{-- Incomplete Reason --}}
        @if ($booking->status == 'incomplete')
            <div class="d-flex align-items-center justify-content-between mb-3">
                <p class="mb-0">Incomplete Reason:</p>
                <h6 class="mb-0">{{ $bookingPayment->incomplete_reason ?? 'N/A' }}</h6>
            </div>
        @endif


        @if ($booking->booking_type == 'moving')
            {{-- Job Details --}}
            <div class="d-flex align-items-center justify-content-between mb-3">
                <p class="mb-0">Job Details:</p>
                <div class="">
                    @forelse ($booking_configs as $booking_config)
                        <h6 class="mb-0">{{ $booking_config->name }}</h6>
                    @empty
                        <h6 class="mb-0">N/A</h6>
                    @endforelse
                </div>
            </div>
        @endif
    </div>
</div>
