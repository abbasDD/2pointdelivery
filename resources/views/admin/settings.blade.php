@extends('admin.layouts.app')

@section('title', 'Settings')

@section('content')

    <div class="container">
        <div class="d-flex">
            <div class="tab-content flex-grow-1" id="v-pills-tabContent">
                {{-- Personal Tab  --}}
                <div class="tab-pane fade m-3 show active" id="v-pills-personal" role="tabpanel"
                    aria-labelledby="v-pills-personal-tab">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Personal</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 image-selection">
                                    <div class="mx-auto" style="max-width: 150px;">
                                        <img id="avatar_img" src="{{ asset('images/default-user.jpg') }}" alt="avatar"
                                            class="p-3 border w-100" onclick="document.getElementById('avatar').click()">
                                        <input type="file" name="avatar" id="avatar" class="d-none" accept="image/*"
                                            required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label">First Name</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name"
                                            value="{{ Auth::user()->first_name }}" placeholder="First Name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="last_name" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name"
                                            value="{{ Auth::user()->last_name }}" placeholder="Last Name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username"
                                            value="{{ Auth::user()->username }}" placeholder="Username" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ Auth::user()->email }}" placeholder="Email" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Company Tab  --}}
                <div class="tab-pane fade m-3" id="v-pills-company" role="tabpanel" aria-labelledby="v-pills-company-tab">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Company</h5>
                        </div>
                        <div class="card-body">
                            <p>Company Name</p>
                        </div>
                    </div>
                </div>
                {{-- Address Tab --}}
                <div class="tab-pane fade m-3" id="v-pills-address" role="tabpanel" aria-labelledby="v-pills-address-tab">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Address</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="suite" class="form-label">Suite</label>
                                        <input type="text" class="form-control" id="suite" name="suite"
                                            value="{{ Auth::user()->suite }}" placeholder="Suite" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="street" class="form-label">Street</label>
                                        <input type="text" class="form-control" id="street" name="street"
                                            value="{{ Auth::user()->street }}" placeholder="Street" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="city" class="form-label">City</label>
                                        <input type="text" class="form-control" id="city" name="city"
                                            value="{{ Auth::user()->city }}" placeholder="City" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="state" class="form-label">State</label>
                                        <input type="text" class="form-control" id="state" name="state"
                                            value="{{ Auth::user()->state }}" placeholder="State" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="country" class="form-label">Country</label>
                                        <input type="text" class="form-control" id="country" name="country"
                                            value="{{ Auth::user()->country }}" placeholder="Country" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Change Password Tab --}}
                <div class="tab-pane fade m-3" id="v-pills-password" role="tabpanel"
                    aria-labelledby="v-pills-password-tab">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Password</h5>
                        </div>
                        <div class="card-body">
                            <p>Password Name</p>
                        </div>
                    </div>
                </div>
                {{-- Social Links Tab --}}
                <div class="tab-pane fade m-3" id="v-pills-social" role="tabpanel" aria-labelledby="v-pills-social-tab">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Social</h5>
                        </div>
                        <div class="card-body">
                            <p>Social Name</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-body">
                    <div class="d-flex flex-column nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                        aria-orientation="vertical">
                        <button class="nav-link active" id="v-pills-personal-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-personal" type="button" role="tab"
                            aria-controls="v-pills-personal" aria-selected="true">
                            <i class="fas fa-user"></i> <span class="d-none d-md-inline">Personal</span>
                        </button>
                        <button class="nav-link" id="v-pills-company-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-company" type="button" role="tab"
                            aria-controls="v-pills-company" aria-selected="false">
                            <i class="fas fa-building me-1"></i> <span class="d-none d-md-inline">Company</span>
                        </button>
                        <button class="nav-link" id="v-pills-address-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-address" type="button" role="tab"
                            aria-controls="v-pills-address" aria-selected="false">
                            <i class="fas fa-map-marker-alt"></i> <span class="d-none d-md-inline">Address</span>
                        </button>
                        <button class="nav-link" id="v-pills-password-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-password" type="button" role="tab"
                            aria-controls="v-pills-password" aria-selected="false">
                            <i class="fas fa-key"></i> <span class="d-none d-md-inline">Password</span>
                        </button>
                        <button class="nav-link" id="v-pills-social-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-social" type="button" role="tab"
                            aria-controls="v-pills-social" aria-selected="false">
                            <i class="fas fa-link"></i> <span class="d-none d-md-inline">Social</span>
                        </button>
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
