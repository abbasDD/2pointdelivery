{{-- A form to create new welcome email --}}
<form action="{{ route('admin.emailTemplates.welcome.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <h5>Welcome Email</h5>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="subject">Subject</label>
                        <input type="text" class="form-control" name="subject" id="subject"
                            placeholder="Enter Subject" value="{{ $welcomeEmail->subject ?? '' }}" required>
                    </div>
                    {{-- <div class="form-group mb-3">
                        <label for="body">Body</label>
                        <textarea class="form-control" name="body" id="body" rows="10" placeholder="Enter Body" required>{{ $welcomeEmail->body ?? '' }}</textarea>
                    </div> --}}

                    <div class="form-group mb-3">
                        <label for="welcomeEmailBody">Body</label>
                        <input id="welcomeEmailBody" type="hidden" name="body"
                            value="{{ old('body', $welcomeEmail->body ?? '') }}">
                        <trix-editor input="welcomeEmailBody" class="trix-content"></trix-editor>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
