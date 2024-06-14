{{-- A form to create new password reset email --}}
<form action="{{ route('admin.frontendSettings.privacyPolicy.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <h5>Privacy Policy</h5>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="privacyPolicyBody">Body</label>
                        <input id="privacyPolicyBody" type="hidden" name="value"
                            value="{{ old('value', $privacyPolicy->value ?? '') }}">
                        <trix-editor input="privacyPolicyBody" class="trix-content"></trix-editor>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
