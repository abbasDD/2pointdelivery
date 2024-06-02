<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Images</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p class="mb-2">Start Booking</p>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <img src="{{ $bookingPayment->start_booking_image ? asset('images/bookings/' . $bookingPayment->start_booking_image) : asset('images/bookings/default.png') }}"
                        alt="Truck" class="w-100 image-popup">
                </div>
            </div>
            <div class="col-md-6">
                <p class="mb-2">Start Signature</p>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <img src="{{ $bookingPayment->signatureStart ? asset('images/bookings/' . $bookingPayment->signatureStart) : asset('images/bookings/default.png') }}"
                        alt="Truck" class="w-100 image-popup" id="signatureImage">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <p class="mb-2">Complete Booking</p>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <img src="{{ $bookingPayment->complete_booking_image ? asset('images/bookings/' . $bookingPayment->complete_booking_image) : asset('images/bookings/default.png') }}"
                        alt="Truck" class="w-100 image-popup">
                </div>
            </div>
            <div class="col-md-6">
                <p class="mb-2">Completed Signature</p>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <img src="{{ $bookingPayment->signatureCompleted ? asset('images/bookings/' . $bookingPayment->signatureCompleted) : asset('images/bookings/default.png') }}"
                        alt="Truck" class="w-100 image-popup">
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div id="imageModal" class="modal imageModal">
    <span class="close">&times;</span>
    <img class="modal-content" id="modalImage">
</div>

<script>
    // Get all images with class 'image-popup'
    const images = document.querySelectorAll('.image-popup');

    // Get the modal
    const modal = document.getElementById('imageModal');

    // Get the image and insert it inside the modal
    const modalImg = document.getElementById("modalImage");

    // Loop through each image and attach click event listener
    images.forEach(image => {
        image.addEventListener('click', function() {
            modal.style.display = "block";
            modalImg.src = this.src;
        });
    });

    // Get the <span> element that closes the modal
    const span = document.getElementsByClassName("close")[0];

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }
</script>
