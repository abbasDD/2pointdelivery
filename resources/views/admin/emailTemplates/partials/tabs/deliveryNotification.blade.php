{{-- A form to create new password reset email --}}
<form action="{{ route('admin.emailTemplates.deliveryNotification.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <h5>Delivery Notification</h5>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="subject">Subject</label>
                        <input type="text" class="form-control" name="subject" id="subject"
                            placeholder="Enter Subject" value="{{ $deliveryNotificationEmail->subject ?? '' }}"
                            required>
                    </div>
                    <div class="form-group mb-3">
                        {{-- Style to hide file upload --}}
                        <style>
                            .trix-button--icon-attach {
                                display: none !important;
                            }
                        </style>
                        <label for="deliveryNotificationBody">Body</label>
                        <input id="deliveryNotificationBody" type="hidden" name="body"
                            value="{{ old('body', $deliveryNotificationEmail->body ?? '') }}">
                        <trix-editor input="deliveryNotificationBody" class="trix-content"></trix-editor>

                        {{-- Mentioned Variables --}}
                        <p class="mt-3"> [Customer name], [Tracking number], [Order number], [time and date], [payment
                            method], [Delivery data provided by the customer], [company name] </p>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
