{{-- A form to create new refundNotification email --}}
<form action="{{ route('admin.emailTemplates.refundNotification.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <h5>Refund Notification</h5>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="subject">Subject</label>
                        <input type="text" class="form-control" name="subject" id="subject"
                            placeholder="Enter Subject" value="{{ $refundNotificationEmail->subject ?? '' }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="refundNotificationEmailBody">Body</label>
                        <input id="refundNotificationEmailBody" type="hidden" name="body"
                            value="{{ old('body', $refundNotificationEmail->body ?? '') }}">
                        <trix-editor input="refundNotificationEmailBody" class="trix-content"></trix-editor>


                        {{-- Mentioned Variables --}}
                        <p class="mt-3"> [Customer], [Your name] </p>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
