{{-- Receiver Details --}}
<div class="row">
    {{-- Receiver Name --}}
    <div class="col-md-6">
        <div class="mb-3">
            <label for="receiverName">Receiver Name</label>
            <input type="text" class="form-control" id="receiverName" name="receiver_name"
                placeholder="Enter receiver name" required>
        </div>
    </div>
    {{-- Receiver Email --}}
    <div class="col-md-6">
        <div class="mb-3">
            <label for="receiverEmail">Receiver Email</label>
            <input type="email" class="form-control" id="receiverEmail" name="receiver_email"
                placeholder="Enter receiver email">
        </div>
    </div>
    {{-- Receiver Phone --}}
    <div class="col-md-6">
        <div class="mb-3">
            <label for="receiverPhone">Receiver Phone</label>
            <input type="text" class="form-control" id="receiverPhone" name="receiver_phone"
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
