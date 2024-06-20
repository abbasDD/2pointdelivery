{{-- A form to create new feedback email --}}
<form action="{{ route('admin.emailTemplates.feedback.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <h5>Feedback</h5>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="subject">Subject</label>
                        <input type="text" class="form-control" name="subject" id="subject"
                            placeholder="Enter Subject" value="{{ $feedbackEmail->subject ?? '' }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="feedbackEmailBody">Body</label>
                        <input id="feedbackEmailBody" type="hidden" name="body"
                            value="{{ old('body', $feedbackEmail->body ?? '') }}">
                        <trix-editor input="feedbackEmailBody" class="trix-content"></trix-editor>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
