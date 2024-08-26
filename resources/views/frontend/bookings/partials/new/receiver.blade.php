{{-- Receiver Details --}}
<div class="row">
    {{-- Heading --}}
    <div class="col-md-12">
        <div class="mb-3">
            <h5>Receiver Details</h5>
        </div>
    </div>
    {{-- Receiver Name --}}
    <div class="col-md-4">
        <div class="mb-3">
            <label for="receiver_name">Receiver Name</label>
            <input type="text" class="form-control" id="receiver_name" name="receiver_name"
                placeholder="Enter receiver name" required>
        </div>
    </div>
    {{-- Receiver Email --}}
    <div class="col-md-4">
        <div class="mb-3">
            <label for="receiver_email">Receiver Email</label>
            <input type="email" class="form-control" id="receiver_email" name="receiver_email"
                placeholder="Enter receiver email">
        </div>
    </div>
    {{-- Receiver Phone --}}
    <div class="col-md-4">
        <div class="mb-3">
            <label for="receiver_phone">Receiver Phone</label>
            <input type="text" class="form-control" id="receiver_phone" name="receiver_phone"
                placeholder="Enter receiver phone" required>
        </div>
    </div>
    {{-- Delivery Note --}}
    <div class="col-md-12">
        <div class="mb-3">
            <label for="deliveryNote">Delivery Note</label>
            <textarea class="form-control" id="deliveryNote" name="delivery_note" rows="3" placeholder="Enter delivery note"></textarea>
        </div>
    </div>
</div>
