<style>
    .tracking .blinker {
        border: 7px solid #e9f8ea;
        animation: blink 1s;
        animation-iteration-count: infinite;
    }

    @keyframes blink {
        50% {
            border-color: #fff;
        }
    }
</style>

<div class="card">
    <div class="row mb-0">
        <div class="col-md-12 col-lg-12">
            <div id="tracking-pre"></div>
            <div id="tracking" class="tracking">
                <div class="tracking-list">
                    {{-- Pending --}}
                    <div class="{{ $booking->status == 'pending' ? 'tracking-item' : 'tracking-item-pending' }}">
                        <div
                            class="tracking-icon {{ $booking->status == 'pending' ? 'status-current' : 'status-intransit' }}">
                            <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas"
                                data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 512 512" data-fa-i2svg="">
                                <path fill="currentColor"
                                    d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
                            </svg>
                        </div>
                        <div class="tracking-date"><img
                                src="https://raw.githubusercontent.com/shajo/portfolio/a02c5579c3ebe185bb1fc085909c582bf5fad802/delivery.svg"
                                class="img-responsive" alt="order-placed" /></div>
                        <div class="tracking-content">Order Placed
                            <span>{{ $bookingDelivery->created_at ? app('dateHelper')->formatTimestamp($bookingDelivery->created_at, 'd M Y H:i') : 'Expected' }}</span>
                        </div>
                    </div>
                    {{-- Accepted --}}
                    <div class="{{ $booking->status == 'accepted' ? 'tracking-item' : 'tracking-item-pending' }}">
                        <div
                            class="tracking-icon {{ $booking->status == 'accepted' ? 'status-current' : 'status-intransit' }}">
                            <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas"
                                data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 512 512" data-fa-i2svg="">
                                <path fill="currentColor"
                                    d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
                            </svg>
                        </div>
                        <div class="tracking-date"><img
                                src="https://raw.githubusercontent.com/shajo/portfolio/a02c5579c3ebe185bb1fc085909c582bf5fad802/delivery.svg"
                                class="img-responsive" alt="order-placed" /></div>
                        <div class="tracking-content">Order Assigned
                            <span>{{ $bookingDelivery->accepted_at ? app('dateHelper')->formatTimestamp($bookingDelivery->accepted_at, 'd M Y H:i') : 'Expected' }}</span>
                        </div>
                    </div>
                    {{-- Started --}}
                    <div class="{{ $booking->status == 'started' ? 'tracking-item' : 'tracking-item-pending' }}">
                        <div
                            class="tracking-icon {{ $booking->status == 'started' ? 'status-current' : 'status-intransit' }}">
                            <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas"
                                data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 512 512" data-fa-i2svg="">
                                <path fill="currentColor"
                                    d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
                            </svg>
                        </div>
                        <div class="tracking-date"><img
                                src="https://raw.githubusercontent.com/shajo/portfolio/a02c5579c3ebe185bb1fc085909c582bf5fad802/delivery.svg"
                                class="img-responsive" alt="order-placed" /></div>
                        <div class="tracking-content">Package Received
                            <span>{{ $bookingDelivery->start_booking_at ? app('dateHelper')->formatTimestamp($bookingDelivery->start_booking_at, 'd M Y H:i') : 'Expected' }}</span>
                        </div>
                    </div>
                    {{-- In Transit --}}
                    <div class="{{ $booking->status == 'in_transit' ? 'tracking-item' : 'tracking-item-pending' }}">
                        <div
                            class="tracking-icon {{ $booking->status == 'in_transit' ? 'status-current' : 'status-intransit' }}">
                            <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas"
                                data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 512 512" data-fa-i2svg="">
                                <path fill="currentColor"
                                    d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
                            </svg>
                        </div>
                        <div class="tracking-date"><img
                                src="https://raw.githubusercontent.com/shajo/portfolio/a02c5579c3ebe185bb1fc085909c582bf5fad802/delivery.svg"
                                class="img-responsive" alt="order-placed" /></div>
                        <div class="tracking-content">Delivering
                            <span>{{ $bookingDelivery->start_intransit_at ? app('dateHelper')->formatTimestamp($bookingDelivery->start_intransit_at, 'd M Y H:i') : 'Expected' }}</span>
                        </div>
                    </div>
                    {{-- Completed --}}
                    <div class="{{ $booking->status == 'completed' ? 'tracking-item' : 'tracking-item-pending' }}">
                        <div
                            class="tracking-icon {{ $booking->status == 'completed' ? 'status-current' : 'status-intransit' }}">
                            <svg class="svg-inline--fa fa-circle fa-w-16" aria-hidden="true" data-prefix="fas"
                                data-icon="circle" role="img" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 512 512" data-fa-i2svg="">
                                <path fill="currentColor"
                                    d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path>
                            </svg>
                        </div>
                        <div class="tracking-date"><img
                                src="https://raw.githubusercontent.com/shajo/portfolio/a02c5579c3ebe185bb1fc085909c582bf5fad802/delivery.svg"
                                class="img-responsive" alt="order-placed" /></div>
                        <div class="tracking-content">Receipent Received
                            <span>{{ $bookingDelivery->complete_booking_at ? app('dateHelper')->formatTimestamp($bookingDelivery->complete_booking_at, 'd M Y H:i') : 'Expected' }}</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>