<nav>
    <div class="nav nav-tabs nav-fill justify-content-between" id="nav-tab" role="tablist">
        {{-- Details --}}
        <button class="nav-link active" id="nav-details-tab" data-bs-toggle="tab" data-bs-target="#nav-details"
            type="button" role="tab" aria-controls="nav-details" aria-selected="true">Details</button>
        {{-- Vehicle --}}
        <button class="nav-link" id="nav-vehicle-tab" data-bs-toggle="tab" data-bs-target="#nav-vehicle" type="button"
            role="tab" aria-controls="nav-vehicle" aria-selected="false">Vehicle</button>
        {{-- Show driver if type is delivery --}}
        @if ($booking->booking_type == 'delivery')
            {{-- Driver --}}
            <button class="nav-link" id="nav-driver-tab" data-bs-toggle="tab" data-bs-target="#nav-driver"
                type="button" role="tab" aria-controls="nav-driver" aria-selected="false">Driver</button>
        @endif

        {{-- Show driver if type is moving --}}
        @if ($booking->booking_type == 'moving')
            {{-- Movers --}}
            <button class="nav-link" id="nav-movers-tab" data-bs-toggle="tab" data-bs-target="#nav-movers"
                type="button" role="tab" aria-controls="nav-movers" aria-selected="false">Movers
                ({{ $booking->moverCount }})
            </button>
        @endif
        {{-- Customer --}}
        <button class="nav-link" id="nav-customer-tab" data-bs-toggle="tab" data-bs-target="#nav-customer"
            type="button" role="tab" aria-controls="nav-customer" aria-selected="false">Customer
        </button>
        {{-- Receipent --}}
        <button class="nav-link" id="nav-receipent-tab" data-bs-toggle="tab" data-bs-target="#nav-receipent"
            type="button" role="tab" aria-controls="nav-receipent" aria-selected="false">Receipent
        </button>
    </div>
</nav>
<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane my-3 fade show active " id="nav-details" role="tabpanel" aria-labelledby="nav-details-tab">
        {{-- Order Details --}}
        @include('frontend.bookings.partials.info.details')
    </div>
    <div class="tab-pane my-3 fade" id="nav-vehicle" role="tabpanel" aria-labelledby="nav-vehicle-tab">
        {{-- Vehicle Detail --}}
        @include('frontend.bookings.partials.info.vehicle')
    </div>
    {{-- Show driver if type is delivery --}}
    @if ($booking->booking_type == 'delivery')
        <div class="tab-pane my-3 fade" id="nav-driver" role="tabpanel" aria-labelledby="nav-driver-tab">
            {{-- Driver Detail --}}
            @include('frontend.bookings.partials.info.driver')
        </div>
    @endif
    {{-- Show driver if type is moving --}}
    @if ($booking->booking_type == 'moving')
        <div class="tab-pane my-3 fade" id="nav-movers" role="tabpanel" aria-labelledby="nav-movers-tab">
            {{-- Movers Detail --}}
            @include('frontend.bookings.partials.info.movers')
        </div>
    @endif
    <div class="tab-pane my-3 fade" id="nav-customer" role="tabpanel" aria-labelledby="nav-customer-tab">
        {{-- Receipent Detail --}}
        @include('frontend.bookings.partials.info.customer')
    </div>
    <div class="tab-pane my-3 fade" id="nav-receipent" role="tabpanel" aria-labelledby="nav-receipent-tab">
        {{-- Receipent Detail --}}
        @include('frontend.bookings.partials.info.receipent')
    </div>
</div>
