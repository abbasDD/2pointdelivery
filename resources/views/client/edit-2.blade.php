@extends('client.layouts.app')

@section('title', 'Edit Profile')

@section('content')

    <div class="row mb-5 p-3">
        <div class="col-md-12 mx-auto d-grid align-items-center justify-content-center">
            <div class="text-center">
                <form method="POST" action="{{ route('client.update_profile') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row justify-content-center">
                        <div class="col-md-4 image-selection">
                            <div class="mx-auto position-relative mb-5" style="max-width: 150px;">
                                <img id="avatar_img" src="{{ asset('images/default-user.jpg') }}" alt="avatar"
                                    class="rounded-circlep-3 border w-100">
                                <input type="file" name="avatar" id="avatar" class="d-none" accept="image/*"
                                    required>
                                <a href="javascript:void(0)" onclick="document.getElementById('avatar').click()"
                                    class="btn btn-outline-primary btn-sm position-absloute border rounded-circle">
                                    <i class="fa fa-camera" aria-hidden="true"></i>
                                </a>
                                @error('avatar')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-md-4">
                            <input id="first_name" type="text"
                                class="form-control @error('first_name') is-invalid @enderror" name="first_name"
                                value="{{ old('first_name') }}" placeholder="First Name" required autocomplete="first_name"
                                autofocus>

                            @error('first_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <input id="middle_name" type="text"
                                class="form-control @error('middle_name') is-invalid @enderror" name="middle_name"
                                value="{{ old('middle_name') }}" placeholder="Middle Name" autocomplete="middle_name">

                            @error('middle_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <input id="last_name" type="text"
                                class="form-control @error('last_name') is-invalid @enderror" name="last_name"
                                value="{{ old('last_name') }}" placeholder="Last Name" required autocomplete="last_name">

                            @error('last_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-6">
                            <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror"
                                required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            @error('gender')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input type="date" name="date_of_birth" id="date_of_birth"
                                class="form-control @error('date_of_birth') is-invalid @enderror"
                                placeholder="Date of Birth" required min="<?php echo date('Y-m-d', strtotime('-1 year')); ?>">

                            @error('date_of_birth')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-md-6">
                            <input id="tax_id" type="text" name="tax_id"
                                class="form-control @error('tax_id') is-invalid @enderror" placeholder="Tax ID" required>
                            @error('tax_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input id="phone_no" type="tel" name="phone_no"
                                class="form-control @error('phone_no') is-invalid @enderror" placeholder="Phone Number"
                                required>
                            @error('phone_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-md-4">
                            <input id="suite" type="text" class="form-control @error('suite') is-invalid @enderror"
                                name="suite" value="{{ old('suite') }}" placeholder="Suite" required
                                autocomplete="suite">

                            @error('suite')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <input id="street" type="text"
                                class="form-control @error('street') is-invalid @enderror" name="street"
                                value="{{ old('street') }}" placeholder="Street" required autocomplete="street">

                            @error('street')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <input id="city" type="text" class="form-control @error('city') is-invalid @enderror"
                                name="city" value="{{ old('city') }}" placeholder="City" required
                                autocomplete="city">

                            @error('city')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <input id="state" type="text"
                                class="form-control @error('state') is-invalid @enderror" name="state"
                                value="{{ old('state') }}" placeholder="State" required autocomplete="state">

                            @error('state')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <input id="country" type="text"
                                class="form-control @error('country') is-invalid @enderror" name="country"
                                value="{{ old('country') }}" placeholder="Country" required autocomplete="country">

                            @error('country')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <input id="zip_code" type="text"
                                class="form-control @error('zip_code') is-invalid @enderror" name="zip_code"
                                value="{{ old('zip_code') }}" placeholder="Zip Code" required autocomplete="zip_code">

                            @error('zip_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="form-group" style="float: right">
                            <button class="btn btn-primary" type="button">Update Details</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('#avatar').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (!file.type.startsWith('image/')) {
                alert('Please select an image file.');
                event.target.value = null;
                return;
            }

            const reader = new FileReader();
            reader.onload = (event) => {
                document.querySelector('#avatar_img').src = event.target.result;
            }

            reader.readAsDataURL(file);
        });
    </script>

@endsection
