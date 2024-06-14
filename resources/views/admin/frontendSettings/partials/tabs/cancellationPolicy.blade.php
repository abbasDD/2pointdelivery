{{-- A form to create new welcome email --}}
<form action="{{ route('admin.frontendSettings.cancellationPolicy.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <h5>Cancellation Policy</h5>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="cancellationPolicyBody">Body</label>
                        <input id="cancellationPolicyBody" type="hidden" name="value"
                            value="{{ old('value', $cancellationPolicy->value ?? '') }}">
                        <trix-editor input="cancellationPolicyBody" class="trix-content"></trix-editor>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
