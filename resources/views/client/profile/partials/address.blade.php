<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Personal</h5>
    </div>
    <form action="{{ route('client.update.address') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="row">
                {{-- Suite --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="suite" class="form-label">Suite</label>
                        <input type="text" class="form-control" id="suite" name="suite"
                            value="{{ old('suite', $clientData['suite'] ?? '') }}" placeholder="Suite " required>
                        @error('suite')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                {{-- Street --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="street" class="form-label">Street</label>
                        <input type="text" class="form-control" id="street" name="street"
                            value="{{ old('street', $clientData['street'] ?? '') }}" placeholder="Street" required>
                        @error('street')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                {{-- City --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" id="city" name="city"
                            value="{{ old('city', $clientData['city'] ?? '') }}" placeholder="City" required>

                        @error('city')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                {{-- State --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="state" class="form-label">State</label>
                        <input type="text" class="form-control" id="state" name="state"
                            value="{{ old('state', $clientData['state'] ?? '') }}" placeholder="State " required>
                        @error('state')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                {{-- Country --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" class="form-control" id="country" name="country"
                            value="{{ old('country', $clientData['country'] ?? '') }}" placeholder="Country " required>
                        @error('country')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                {{-- Zip Code --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="zip_code" class="form-label">Zip Code</label>
                        <input type="text" class="form-control" id="zip_code" name="zip_code"
                            value="{{ old('zip_code', $clientData['zip_code'] ?? '') }}" placeholder="Zip Code "
                            required>
                        @error('zip_code')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                {{-- Row End Here --}}
            </div>
            {{-- Button to Submit --}}
            <div class="row">
                <div class="col-md-12 text-right">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </form>
</div>
