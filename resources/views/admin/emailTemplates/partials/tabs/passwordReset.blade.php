{{-- A form to create new password reset email --}}
<form action="{{ route('admin.emailTemplates.passwordReset.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <h5>Password Reset Email</h5>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="subject">Subject</label>
                        <input type="text" class="form-control" name="subject" id="subject"
                            placeholder="Enter Subject" value="{{ $passwordResetEmail->subject ?? '' }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="passwordResetBody">Body</label>
                        <input id="passwordResetBody" type="hidden" name="body"
                            value="{{ old('body', $passwordResetEmail->body ?? '') }}">
                        <trix-editor input="passwordResetBody" class="trix-content"></trix-editor>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
