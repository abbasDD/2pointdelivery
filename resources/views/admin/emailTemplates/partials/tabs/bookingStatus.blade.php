{{-- A form to create new password reset email --}}
<form action="{{ route('admin.emailTemplates.bookingStatus.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <h5>Booking Status</h5>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="subject">Subject</label>
                        <input type="text" class="form-control" name="subject" id="subject"
                            placeholder="Enter Subject" value="{{ $bookingStatusEmail->subject ?? '' }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="bookingStatusBody">Body</label>
                        <input id="bookingStatusBody" type="hidden" name="body"
                            value="{{ old('body', $bookingStatusEmail->body ?? '') }}">
                        <trix-editor input="bookingStatusBody" class="trix-content"></trix-editor>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>