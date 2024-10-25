{{-- Action Buttons --}}
<div class="d-flex gap-2">
    {{-- Report an Issue --}}
    @if (in_array($booking->status, ['accepted', 'started', 'in_transit']))
        <div class="">
            <a href="#" class="btn btn-danger"><i class="fa fa-bug" aria-hidden="true"></i> <span
                    class="d-none d-md-inline"> Report an Issue</span></a>
        </div>
    @endif
    {{-- If draft then ask client to payment --}}
    @if ($clientData->user_id == auth()->user()->id && $booking->status == 'draft')
        <div class="">
            <a href="{{ route('client.booking.payment', $booking->id) }}" class="btn btn-success"><i class="fa fa-dollar"
                    aria-hidden="true"></i> <span class="d-none d-md-inline"> Pay
                    Now</span></a>
        </div>
    @endif

    {{-- If booking is complete and client then ask client to review booking --}}
    @if ($booking->status == 'completed' && !isset($booking->review))
        <div class="">
            <a onclick="reviewBooking('{{ $booking->id }}')" class="btn btn-success"><i class="fa fa-star"
                    aria-hidden="true"></i> <span class="d-none d-md-inline"> Review</span></a>
        </div>
    @endif

    <div class="">
        <a class="btn btn-success" href="{{ route('booking-invoice-pdf', $booking->id) }}" target="_blank"><i
                class="fa fa-file" aria-hidden="true"></i> <span class="d-none d-md-inline">
                Invoice</span></a>
    </div>

    <div class="">
        <a href="{{ route('label', $booking->id) }}" class="btn btn-success" target="_blank"><i class="fa fa-file"
                aria-hidden="true"></i> <span class="d-none d-md-inline">
                Shipping</span></a>
    </div>
</div>

{{-- reviewBookingModal --}}
@if ($booking->status == 'completed' && !isset($booking->review))
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
    // reviewBooking
    function reviewBooking(id) {
        $('#reviewBookingModal').modal('show');
    }
</script>
