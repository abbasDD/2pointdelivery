<div class="card mb-3">
    <div class="card-header">
        <h5 class="mb-0">Tracking Status</h5>
    </div>
    <div class="card-body">
        {{-- Order Status --}}
        <div class="progressdiv">
            <ul class="progressbar p-0">
                {{-- Pending --}}
                <li {{ $booking->status == 'pending' ? 'class=active' : '' }}>
                    <div class="d-flex">
                        <div class="text-right">
                            <h5>{{ $bookingDelivery->created_at ? app('dateHelper')->formatTimestamp($bookingDelivery->created_at, 'Y-m-d') : 'Expected' }}
                            </h5>
                            <p>{{ $bookingDelivery->created_at ? app('dateHelper')->formatTimestamp($bookingDelivery->created_at, 'H:i') : '-' }}
                            </p>
                        </div>
                        <div class="circle mx-3">
                        </div>
                    </div>
                    <div class="">
                        <h6>Order Booked</h6>
                        <p>6391 Washington</p>
                    </div>
                </li>
                {{-- Accepted --}}
                <li {{ $booking->status == 'accepted' ? 'class=active' : '' }}>
                    <div class="d-flex">
                        <div class="text-right">
                            <h5>{{ $bookingDelivery->accepted_at ? app('dateHelper')->formatTimestamp($bookingDelivery->accepted_at, 'Y-m-d') : 'Expected' }}
                            </h5>
                            <p>{{ $bookingDelivery->accepted_at ? app('dateHelper')->formatTimestamp($bookingDelivery->accepted_at, 'H:i') : '-' }}
                            </p>
                        </div>
                        <div class="circle mx-3">
                        </div>
                    </div>
                    <div class="">
                        <h6>Order Assigned</h6>
                        <p>6391 Washington</p>
                    </div>
                </li>
                {{-- Picked Up --}}
                <li {{ $booking->status == 'started' ? 'class=active' : '' }}>
                    <div class="d-flex">
                        <div class="text-right">
                            <h5>{{ $bookingDelivery->start_booking_at ? app('dateHelper')->formatTimestamp($bookingDelivery->start_booking_at, 'Y-m-d') : 'Expected' }}
                            </h5>
                            <p>{{ $bookingDelivery->start_booking_at ? app('dateHelper')->formatTimestamp($bookingDelivery->start_booking_at, 'H:i') : '-' }}
                            </p>
                        </div>
                        <div class="circle mx-3">
                        </div>
                    </div>
                    <div class="">
                        <h6>Package Received</h6>
                        <p>6391 Washington</p>
                    </div>
                </li>
                {{-- Delivered --}}
                <li {{ $booking->status == 'in_transit' ? 'class=active' : '' }}>
                    <div class="d-flex">
                        <div class="text-right">
                            <h5>{{ $bookingDelivery->start_intransit_at ? app('dateHelper')->formatTimestamp($bookingDelivery->start_intransit_at, 'Y-m-d') : 'Expected' }}
                            </h5>
                            <p>{{ $bookingDelivery->start_intransit_at ? app('dateHelper')->formatTimestamp($bookingDelivery->start_intransit_at, 'H:i') : '-' }}
                            </p>
                        </div>
                        <div class="circle mx-3">
                        </div>
                    </div>
                    <div class="">
                        <h6>Delivering</h6>
                        <p>6391 Washington</p>
                    </div>
                </li>
                <li {{ $booking->status == 'completed' ? 'class=active' : '' }}>
                    <div class="d-flex">
                        <div class="text-right">
                            <h5>{{ $bookingDelivery->complete_booking_at ? app('dateHelper')->formatTimestamp($bookingDelivery->complete_booking_at, 'Y-m-d') : 'Expected' }}
                            </h5>
                            <p>{{ $bookingDelivery->complete_booking_at ? app('dateHelper')->formatTimestamp($bookingDelivery->complete_booking_at, 'H:i') : '-' }}
                            </p>
                        </div>
                        <div class="circle mx-3">
                        </div>
                    </div>
                    <div class="">
                        <h6>Receipent Received</h6>
                        <p>6391 Washington</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
