{{-- A form to create new welcome email --}}
<form action="{{ route('admin.frontendSettings.termsAndConditions.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <h5>Terms and Conditions</h5>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="termsAndConditionsBody">Body</label>
                        <input id="termsAndConditionsBody" type="hidden" name="value"
                            value="{{ old('value', $termsAndConditions->value ?? '') }}">
                        <trix-editor input="termsAndConditionsBody" class="trix-content"></trix-editor>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
