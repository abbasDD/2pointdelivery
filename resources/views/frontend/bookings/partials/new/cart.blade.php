{{-- Cart Payment Data --}}
<div class="card mb-5">
    <div class="card-header">
        <h5 class="mb-0">Payment Details</h5>
    </div>
    <div class="card-body flex-grow-1">
        <div class="calculated-amount">
            <div class="item">
                <h6>Base Price</h6>
                <p>$<span id="base-price-value">0</span></p>
            </div>
            <div class="item">
                <h6>Distance Price</h6>
                <p>$<span id="distance-price-value">0</span></p>
            </div>
            <div class="item">
                <h6>Priority Price</h6>
                <p>$<span id="priority-price-value">0</span></p>
            </div>
            <div class="item">
                <h6>Vehicle Price</h6>
                <p>$<span id="vehicle-price-value">0</span></p>
            </div>
            <div class="item moving d-none">
                <h6>Floor Price</h6>
                <p>$<span id="floor-price-value">0</span></p>
            </div>
            <div class="item delivery d-none">
                <h6>Weight Price</h6>
                <p>$<span id="weight-price-value">0</span></p>
            </div>
            {{-- <div class="item delivery d-none">
                <h6>Helper Fee</h6>
                <p>$<span id="helper-fee-value">0</span></p>
            </div> --}}
            <div class="item">
                <h6>Order Distance</h6>
                <p><span id="booking-distance-value">0</span> KM</p>
            </div>
            <hr>
            <div class="item delivery d-none">
                <h6>Tax Price</h6>
                <p>$<span id="tax-price-value">0</span></p>
            </div>
            <div class="item">
                <h6>Amount to Pay</h6>
                <p>$<span id="amount-to-pay-value">45</span></p>
            </div>
        </div>
    </div>
</div>
