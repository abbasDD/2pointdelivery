<nav>
    <div class="nav nav-tabs nav-fill justify-content-between" id="nav-tab" role="tablist">
        <button class="nav-link active" id="nav-details-tab" data-bs-toggle="tab" data-bs-target="#nav-details"
            type="button" role="tab" aria-controls="nav-details" aria-selected="true">Order Details</button>
        <button class="nav-link" id="nav-vehicle-tab" data-bs-toggle="tab" data-bs-target="#nav-vehicle" type="button"
            role="tab" aria-controls="nav-vehicle" aria-selected="false">Vehicle</button>
        <button class="nav-link" id="nav-driver-tab" data-bs-toggle="tab" data-bs-target="#nav-driver" type="button"
            role="tab" aria-controls="nav-driver" aria-selected="false">Driver Information</button>
        <button class="nav-link" id="nav-customer-tab" data-bs-toggle="tab" data-bs-target="#nav-customer"
            type="button" role="tab" aria-controls="nav-customer" aria-selected="false">Customer
            Information</button>
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
    <div class="tab-pane my-3 fade" id="nav-driver" role="tabpanel" aria-labelledby="nav-driver-tab">
        {{-- Driver Detail --}}
        @include('frontend.bookings.partials.info.driver')
    </div>
    <div class="tab-pane my-3 fade" id="nav-customer" role="tabpanel" aria-labelledby="nav-customer-tab">
        {{-- Customer Detail --}}
        @include('frontend.bookings.partials.info.customer')
    </div>
</div>