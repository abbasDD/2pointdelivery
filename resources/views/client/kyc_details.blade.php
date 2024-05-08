@extends('client.layouts.app')

@section('title', 'KYC Details')

@section('content')

    <div class="container p-3 mb-5">

        <h4>KYC Details</h4>

        <form id="kycForm" action="{{ route('client.kyc.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="front_image">ID Card Front:</label>
                    <div class="form-group card clickable-card text-center" data-toggle="tooltip" data-placement="bottom"
                        title="Click to upload ID card front image">
                        @if (!isset($kycDetails['front_image']))
                            <i class="fa fa-camera fa-3x m-3 camera-icon"></i>
                        @endif
                        <input type="file" class="d-none" id="front_image" name="front_image" accept="image/*" required>
                        <img src="{{ isset($kycDetails['front_image']) ? asset('/images/kyc/' . $kycDetails['front_image']) : '' }}"
                            class="img-fluid mx-auto d-block p-2 selected-image h-100" alt="ID Card front">
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="back_image">ID Card Back:</label>
                    <div class="form-group card clickable-card text-center" data-toggle="tooltip" data-placement="bottom"
                        title="Click to upload ID card back image">
                        @if (!isset($kycDetails['back_image']))
                            <i class="fa fa-camera fa-3x m-3 camera-icon"></i>
                        @endif
                        <input type="file" class="d-none" id="back_image" name="back_image" accept="image/*" required>
                        <img src="{{ isset($kycDetails['back_image']) ? asset('/images/kyc/' . $kycDetails['back_image']) : '' }}"
                            class="img-fluid mx-auto d-block p-2 selected-image h-100" alt="ID Card back">
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="id_type">ID Card Type:</label>
                        <select class="form-control" id="id_type" name="id_type" required>
                            <option value="" disabled selected>Select ID Card Type</option>
                            <option value="residence ID"
                                {{ old('id_type', $kycDetails->id_type ?? '') == 'residence ID' ? 'selected' : '' }}>
                                Residence ID</option>
                            <option value="drivers license"
                                {{ old('id_type', $kycDetails->id_type ?? '') == 'drivers license' ? 'selected' : '' }}>
                                Drivers License</option>
                            <option value="insurance card"
                                {{ old('id_type', $kycDetails->id_type ?? '') == 'insurance card' ? 'selected' : '' }}>
                                Insurance Card</option>
                            <option value="passport"
                                {{ old('id_type', $kycDetails->id_type ?? '') == 'passport' ? 'selected' : '' }}>Passport
                            </option>
                            <option value="voters ID"
                                {{ old('id_type', $kycDetails->id_type ?? '') == 'voters ID' ? 'selected' : '' }}>Voters
                                ID</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="id_number">ID Number:</label>
                        <input class="form-control" type="text" id="id_number" name="id_number"
                            placeholder="Enter ID Number" value="{{ old('id_number', $kycDetails->id_number ?? '') }}"
                            required>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="country">Country Name:</label>
                        <input class="form-control" type="text" id="country" name="country"
                            placeholder="Enter Country Name" value="{{ old('country', $kycDetails->country ?? '') }}"
                            required>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="state">State Name:</label>
                        <input class="form-control" type="text" id="state" name="state"
                            placeholder="Enter State Name" value="{{ old('state', $kycDetails->state ?? '') }}" required>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="city">Issue City:</label>
                        <input class="form-control" type="text" id="city" name="city"
                            placeholder="City of Issuance" value="{{ old('city', $kycDetails->city ?? '') }}" required>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="issue_date">Issue Date:</label>
                        <input class="form-control" type="date" id="issue_date" name="issue_date"
                            value="{{ old('issue_date', $kycDetails->issue_date ?? '') }}" required>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="expiry_date">Expiry Date:</label>
                        <input class="form-control" type="date" id="expiry_date" name="expiry_date"
                            value="{{ old('expiry_date', $kycDetails->expiry_date ?? '') }}" required>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group" style="float: right">
                    <button class="btn btn-primary" type="submit">Update KYC</button>
                </div>
            </div>
        </form>

    </div>


    <script>
        const cardClickable = document.querySelectorAll('.clickable-card');

        cardClickable.forEach(card => {
            card.addEventListener('click', function() {
                this.querySelector('input[type=file]').click();
            });

            card.querySelector('input[type=file]').addEventListener('change', function(e) {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();

                    const cameraIcon = card.querySelector('.camera-icon');

                    reader.onload = function(e) {
                        card.querySelector('img').src = e.target.result;
                        cameraIcon.style.display = 'none'; // Hide the camera icon
                    }

                    reader.readAsDataURL(this.files[0]);
                }
            });
        });
    </script>

@endsection
