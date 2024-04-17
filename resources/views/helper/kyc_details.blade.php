@extends('helper.layouts.app')

@section('title', 'KYC Details')

@section('content')

    <div class="container p-3 mb-5">

        <h4>KYC Details</h4>

        <form id="kycForm">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="idCardType">ID Card Front:</label>
                    <div class="form-group card clickable-card text-center" data-toggle="tooltip" data-placement="bottom"
                        title="Click to upload ID card front image">
                        <i class="fa fa-camera fa-3x m-3 camera-icon"></i>
                        <input type="file" class="d-none" id="idFrontImage" name="idFrontImage" required>
                        <img src="" class="img-fluid mx-auto d-block p-2 selected-image h-100" alt="ID Card front">
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="idCardType">ID Card Back:</label>
                    <div class="form-group card clickable-card text-center" data-toggle="tooltip" data-placement="bottom"
                        title="Click to upload ID card back image">
                        <i class="fa fa-camera fa-3x m-3 camera-icon"></i>
                        <input type="file" class="d-none" id="idBackImage" name="idBackImage" required>
                        <img src="" class="img-fluid mx-auto d-block p-2 selected-image h-100" alt="ID Card back">
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="idCardType">ID Card Type:</label>
                        <select class="form-control" id="idCardType" name="idCardType" required>
                            <option value="" disabled selected>Select ID Card Type</option>
                            <option value="residence ID">Residence ID</option>
                            <option value="drivers license">Drivers License</option>
                            <option value="insurance card">Insurance Card</option>
                            <option value="passport">Passport</option>
                            <option value="voters ID">Voters ID</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="idNumber">ID Number:</label>
                        <input class="form-control" type="text" id="idNumber" name="idNumber"
                            placeholder="Enter ID Number" required>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="policyNumber">Policy Number:</label>
                        <input class="form-control" type="text" id="policyNumber" name="policyNumber"
                            placeholder="Enter Policy Number" required>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="issueCity">Issue City:</label>
                        <input class="form-control" type="text" id="issueCity" name="issueCity"
                            placeholder="City of Issuance" required>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="issueDate">Issue Date:</label>
                        <input class="form-control" type="date" id="issueDate" name="issueDate" required>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="expiryDate">Expiry Date:</label>
                        <input class="form-control" type="date" id="expiryDate" name="expiryDate" required>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group" style="float: right">
                    <button class="btn btn-primary" type="button">Update Details</button>
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
