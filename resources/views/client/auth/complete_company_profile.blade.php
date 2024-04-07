@extends('layouts.app')

@section('title', 'Client Company Profile')

@section('content')
    <div class="authpage">
        <div class="row align-content-center">
            {{-- <div class="col-md-6 d-none d-md-block">
                <div class="bg-gradient vh-100 d-flex align-items-center justify-content-center">
                    <img src="{{ asset('images/auth/client-bg.png') }}"  width="400" alt="auth image">
                </div>
            </div> --}}
            <div class="col-md-8 mx-auto d-grid align-items-center justify-content-center">
                <div class="card">

                    <div class="card-body text-center">
                        <a href="{{ route('index') }}">
                            <img src="{{ asset('images/logo/icon.png') }}" alt="logo">
                        </a>
                        <h3>Client Company Profile</h3>
                        <p>Please enter your detail to complete profile</p>
                        <form method="POST" action="{{ route('client.update_profile') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row justify-content-center">
                                <div class="col-md-4 image-selection">
                                    <div class="mx-auto position-relative mb-5" style="max-width: 150px;">
                                        <img id="avatar_img" src="{{ asset('images/default-user.jpg') }}" alt="avatar"
                                            class="rounded-circle border">
                                        <input type="file" name="avatar" id="avatar" class="d-none" accept="image/*"
                                            required>
                                        <a href="javascript:void(0)" onclick="document.getElementById('avatar').click()"
                                            class="btn btn-outline-primary border rounded-circle">
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

                            <h6>Owner Information</h6>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <input id="first_name" type="text"
                                        class="form-control @error('first_name') is-invalid @enderror" name="first_name"
                                        value="{{ old('first_name') }}" placeholder="First Name" required
                                        autocomplete="first_name" autofocus>

                                    @error('first_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <input id="middle_name" type="text"
                                        class="form-control @error('middle_name') is-invalid @enderror" name="middle_name"
                                        value="{{ old('middle_name') }}" placeholder="Middle Name"
                                        autocomplete="middle_name">

                                    @error('middle_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <input id="last_name" type="text"
                                        class="form-control @error('last_name') is-invalid @enderror" name="last_name"
                                        value="{{ old('last_name') }}" placeholder="Last Name" required
                                        autocomplete="last_name">

                                    @error('last_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <select name="gender" id="gender"
                                        class="form-control @error('gender') is-invalid @enderror" required>
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
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


                            <h6>Company Information</h6>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <input id="company_alias" type="text" name="company_alias"
                                        class="form-control @error('company_alias') is-invalid @enderror"
                                        placeholder="Company Alias" required>
                                    @error('company_alias')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <input id="legal_name" type="text" name="legal_name"
                                        class="form-control @error('legal_name') is-invalid @enderror"
                                        placeholder="Legal Name" required>
                                    @error('legal_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <input id="tax_id" type="text" name="tax_id"
                                        class="form-control @error('tax_id') is-invalid @enderror" placeholder="Tax ID"
                                        required>
                                    @error('tax_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <input id="phone_no" type="tel" name="phone_no"
                                        class="form-control @error('phone_no') is-invalid @enderror"
                                        placeholder="Phone Number" required>
                                    @error('phone_no')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>



                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <input id="suite" type="text"
                                        class="form-control @error('suite') is-invalid @enderror" name="suite"
                                        value="{{ old('suite') }}" placeholder="Suite" required autocomplete="suite">

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
                                    <input id="city" type="text"
                                        class="form-control @error('city') is-invalid @enderror" name="city"
                                        value="{{ old('city') }}" placeholder="City" required autocomplete="city">

                                    @error('city')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
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
                                        value="{{ old('country') }}" placeholder="Country" required
                                        autocomplete="country">

                                    @error('country')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <input id="zip_code" type="text"
                                        class="form-control @error('zip_code') is-invalid @enderror" name="zip_code"
                                        value="{{ old('zip_code') }}" placeholder="Zip Code" required
                                        autocomplete="zip_code">

                                    @error('zip_code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        Complete Profile
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="mb-3">

                            <p>
                                Skip for now
                                <a class="" href="{{ route('client.index') }}">
                                    Dashboard
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
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
