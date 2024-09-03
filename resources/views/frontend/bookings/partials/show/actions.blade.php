{{-- Action Buttons --}}
<div class="d-flex gap-2">
    {{-- Report an Issue --}}
    @if ($clientView && isset($clientData) && $clientData->user_id == auth()->user()->id)
        <div class="">
            <a href="#" class="btn btn-danger"><i class="fa fa-bug" aria-hidden="true"></i> <span
                    class="d-none d-md-inline"> Report an Issue</span></a>
        </div>
    @endif
    {{-- If draft then ask client to payment --}}
    @if ($clientView && isset($clientData) && $clientData->user_id == auth()->user()->id && $booking->status == 'draft')
        <div class="">
            <a href="{{ route('client.booking.payment', $booking->id) }}" class="btn btn-success"><i class="fa fa-dollar"
                    aria-hidden="true"></i> <span class="d-none d-md-inline"> Pay
                    Now</span></a>
        </div>
    @endif
    {{-- If pending and helper then ask client to accept --}}
    @if ($helperView && auth()->user()->helper_enabled && $booking->status == 'pending')
        <div class="">
            <a href="{{ route('helper.booking.accept', $booking->id) }}" class="btn btn-success"><i class="fa fa-check"
                    aria-hidden="true"></i> <span class="d-none d-md-inline"> Accept</span></a>
        </div>
    @endif
    {{-- If auth user is helper and status accepted then ask to start --}}
    @if (
        $helperView &&
            auth()->user()->helper_enabled &&
            ($booking->helper_user_id == auth()->user()->id || $booking->helper_user_id2 == auth()->user()->id) &&
            $booking->status == 'accepted')
        <div class="">
            <a onclick="startBooking('{{ $booking->id }}')" class="btn btn-success"><i class="fa fa-bicycle"
                    aria-hidden="true"></i> <span class="d-none d-md-inline"> Start</span></a>
        </div>
    @endif
    {{-- If auth user is helper and status started then ask to in_transit --}}
    @if (
        $helperView &&
            auth()->user()->helper_enabled &&
            ($booking->helper_user_id == auth()->user()->id || $booking->helper_user_id2 == auth()->user()->id) &&
            $booking->status == 'started')
        <div class="">
            <a onclick="inTransitBooking('{{ $booking->id }}')" class="btn btn-success"><i class="fa fa-bicycle"
                    aria-hidden="true"></i> <span class="d-none d-md-inline"> In Transit</span></a>
        </div>
    @endif
    {{-- If auth user is helper and status in_transit then ask to complete --}}
    @if (
        $helperView &&
            auth()->user()->helper_enabled &&
            ($booking->helper_user_id == auth()->user()->id || $booking->helper_user_id2 == auth()->user()->id) &&
            $booking->status == 'in_transit')
        <div class="">
            <a onclick="completeBooking('{{ $booking->id }}')" class="btn btn-success"><i class="fa fa-bicycle"
                    aria-hidden="true"></i> <span class="d-none d-md-inline"> Complete</span></a>
        </div>
    @endif
    {{-- If auth user is helper and status is not completed then helper can mark incomplete --}}
    @if (
        $helperView &&
            auth()->user()->helper_enabled &&
            ($booking->helper_user_id == auth()->user()->id || $booking->helper_user_id2 == auth()->user()->id) &&
            ($booking->status != 'completed' &&
                $booking->status != 'incomplete' &&
                $booking->status != 'cancelled' &&
                $booking->status != 'pending'))
        <div class="">
            <a onclick="inCompleteBooking('{{ $booking->id }}')" class="btn btn-danger"><i class="fa fa-xmark"
                    aria-hidden="true"></i> <span class="d-none d-md-inline"> Incomplete</span></a>
        </div>
    @endif

    {{-- If booking is complete and client then ask client to review booking --}}
    @if ($clientView && $booking->status == 'completed' && !isset($booking->review))
        <div class="">
            <a onclick="reviewBooking('{{ $booking->id }}')" class="btn btn-success"><i class="fa fa-star"
                    aria-hidden="true"></i> <span class="d-none d-md-inline"> Review</span></a>
        </div>
    @endif
</div>

@if (
    $helperView &&
        auth()->user()->helper_enabled &&
        ($booking->helper_user_id == auth()->user()->id || $booking->helper_user_id2 == auth()->user()->id) &&
        $booking->status == 'accepted')
    {{-- Start Booking Modal --}}
    <div class="modal fade" id="startBookingModal" tabindex="-1" aria-labelledby="startBookingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="startBookingModalLabel">Start Booking</h5>
                </div>
                <form action="{{ route('helper.booking.start') }}" method="POST" id="startBookingForm"
                    enctype="multipart/form-data">
                    <div class="modal-body">
                        <p>Please upload client signed image to start booking</p>
                        {{-- hidden booking id --}}
                        <input type="hidden" name="id" value="{{ $booking->id }}">
                        {{-- Start Booking Image --}}
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <div class="image-selection">
                                        <div class="mx-auto" style="max-width: 150px;">
                                            <img id="start_booking_image_preview"
                                                src="{{ asset('images/bookings/default.png') }}"
                                                alt="start_booking_image" class="p-3 border w-100 p-3"
                                                onclick="document.getElementById('start_booking_image').click()">
                                            <input type="file" name="start_booking_image" id="start_booking_image"
                                                class="d-none" accept="image/*" required>
                                        </div>
                                    </div>
                                    @if ($errors->has('start_booking_image'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Profile Image is required</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            {{-- Signature Start Pad JS --}}
                            @include('frontend.bookings.partials.show.signatureStart')

                        </div>
                        @csrf
                        {{-- Upload Image --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary"
                            onclick="startBooking('{{ $booking->id }}')">Start
                            Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@if (
    $helperView &&
        auth()->user()->helper_enabled &&
        ($booking->helper_user_id == auth()->user()->id || $booking->helper_user_id2 == auth()->user()->id) &&
        $booking->status == 'started')
    {{-- inTransitBooking Modal --}}
    <div class="modal fade" id="inTransitBookingModal" tabindex="-1" aria-labelledby="inTransitBookingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="inTransitBookingModalLabel">In Transit Booking</h5>
                </div>
                <form action="{{ route('helper.booking.inTransit') }}" method="POST">
                    <div class="modal-body">
                        <p>The package is ready to be delivered</p>
                        @csrf
                        {{-- hidden booking id --}}
                        <input type="hidden" name="id" value="{{ $booking->id }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary"
                            onclick="inTransitBooking('{{ $booking->id }}')">In
                            Transit
                            Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@if (
    $helperView &&
        auth()->user()->helper_enabled &&
        ($booking->helper_user_id == auth()->user()->id || $booking->helper_user_id2 == auth()->user()->id) &&
        $booking->status == 'in_transit')
    {{-- Complete Booking Modal --}}
    <div class="modal fade" id="completeBookingModal" tabindex="-1" aria-labelledby="completeBookingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="completeBookingModalLabel">Complete Booking</h5>
                </div>
                <form action="{{ route('helper.booking.complete') }}" method="POST" id="completeBookingForm"
                    enctype="multipart/form-data">
                    <div class="modal-body">
                        <p>Please upload client signed image to complete booking</p>
                        {{-- hidden booking id --}}
                        <input type="hidden" name="id" value="{{ $booking->id }}">
                        {{-- Complete Booking Image --}}
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <div class="image-selection">
                                        <div class="mx-auto" style="max-width: 150px;">
                                            <img id="complete_booking_image_preview"
                                                src="{{ asset('images/bookings/default.png') }}"
                                                alt="complete_booking_image" class="p-3 border w-100 p-3"
                                                onclick="document.getElementById('complete_booking_image').click()">
                                            <input type="file" name="complete_booking_image"
                                                id="complete_booking_image" class="d-none" accept="image/*" required>
                                        </div>
                                    </div>
                                    @if ($errors->has('complete_booking_image'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>Profile Image is required</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Signature Start Pad JS --}}
                            @include('frontend.bookings.partials.show.signatureCompleted')
                        </div>
                        @csrf
                        {{-- Upload Image --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary"
                            onclick="completeBooking('{{ $booking->id }}')">Complete
                            Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif


@if (
    $helperView &&
        auth()->user()->helper_enabled &&
        ($booking->helper_user_id == auth()->user()->id || $booking->helper_user_id2 == auth()->user()->id) &&
        ($booking->status != 'completed' && $booking->status != 'incomplete'))
    {{-- inCompleteBooking Modal --}}
    <div class="modal fade" id="inCompleteBookingModal" tabindex="-1" aria-labelledby="inCompleteBookingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="inCompleteBookingModalLabel">In Complete Booking</h5>
                </div>
                <form action="{{ route('helper.booking.incomplete') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        {{-- hidden booking id --}}
                        <input type="hidden" name="id" value="{{ $booking->id }}">
                        <label for="incomplete_reason">Write a reason</label>
                        <textarea class="form-control" name="incomplete_reason" id="incomplete_reason" rows="3"
                            placeholder="Enter reason" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Mark Incomplete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

{{-- reviewBookingModal --}}
@if ($clientView && $booking->status == 'completed' && !isset($booking->review))
    <div class="modal fade" id="reviewBookingModal" tabindex="-1" aria-labelledby="reviewBookingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewBookingModalLabel">Review Booking</h5>
                </div>
                <form action="{{ route('client.booking.review') }}" method="POST">
                    <div class="modal-body">
                        @csrf
                        {{-- hidden booking id --}}
                        <input type="hidden" name="id" value="{{ $booking->id }}">
                        {{-- Stars Selection --}}
                        <div class="form-group review-form mb-3 d-grid">
                            <label for="review">Rate Service</label>
                            <div class="star-rating" id="review">
                                <input type="radio" id="star5" name="rating" value="5" required /><label
                                    for="star5">&#9733;</label>
                                <input type="radio" id="star4" name="rating" value="4" /><label
                                    for="star4">&#9733;</label>
                                <input type="radio" id="star3" name="rating" value="3" /><label
                                    for="star3">&#9733;</label>
                                <input type="radio" id="star2" name="rating" value="2" /><label
                                    for="star2">&#9733;</label>
                                <input type="radio" id="star1" name="rating" value="1" /><label
                                    for="star1">&#9733;</label>
                            </div>
                        </div>
                        <label for="review">Write a review</label>
                        <textarea class="form-control" name="review" id="review" rows="3" placeholder="Enter review" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif


<script>
    // Show Start Booking Modal
    function startBooking(id) {
        $('#startBookingModal').modal('show');
    }

    @if (
        $helperView &&
            auth()->user()->helper_enabled &&
            ($booking->helper_user_id == auth()->user()->id || $booking->helper_user_id2 == auth()->user()->id) &&
            $booking->status == 'accepted')
        // Start Booking Image JS
        document.querySelector('#start_booking_image').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (!file.type.startsWith('image/')) {
                alert('Please select an image file.');
                event.target.value = null;
                return;
            }

            const reader = new FileReader();
            reader.onload = (event) => {
                document.querySelector('#start_booking_image_preview').src = event.target.result;
            }

            reader.readAsDataURL(file);
        });
    @endif

    // Show In Transit Booking Modal
    function inTransitBooking(id) {
        $('#inTransitBookingModal').modal('show');
    }

    // Show Complete Booking Modal
    function completeBooking(id) {
        $('#completeBookingModal').modal('show');
    }
    @if (
        $helperView &&
            auth()->user()->helper_enabled &&
            ($booking->helper_user_id == auth()->user()->id || $booking->helper_user_id2 == auth()->user()->id) &&
            $booking->status == 'in_transit')

        // Complete Booking Image JS
        document.querySelector('#complete_booking_image').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (!file.type.startsWith('image/')) {
                alert('Please select an image file.');
                event.target.value = null;
                return;
            }

            const reader = new FileReader();
            reader.onload = (event) => {
                document.querySelector('#complete_booking_image_preview').src = event.target.result;
            }

            reader.readAsDataURL(file);
        });
    @endif

    // inCompleteBooking
    function inCompleteBooking(id) {
        $('#inCompleteBookingModal').modal('show');
    }

    // reviewBooking
    function reviewBooking(id) {
        $('#reviewBookingModal').modal('show');
    }
</script>
