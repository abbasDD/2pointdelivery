@extends('helper.layouts.app')

@section('title', 'Edit Profile')

@section('content')

    <div class="container">
        <div class="d-flex">
            <div class="tab-content flex-grow-1" id="v-pills-tabContent">
                {{-- Personal Tab  --}}
                <div class="tab-pane fade m-3 show active" id="v-pills-personal" role="tabpanel"
                    aria-labelledby="v-pills-personal-tab">
                    {{-- Load Personal Tab  --}}
                    @include('helper.profile.partials.personal')
                </div>
                {{-- Show only if company enabled --}}
                {{-- Address Tab --}}
                <div class="tab-pane fade m-3" id="v-pills-address" role="tabpanel" aria-labelledby="v-pills-address-tab">
                    {{-- Load Address Tab --}}
                    @include('helper.profile.partials.address')
                </div>
                {{--  Password Tab --}}
                <div class="tab-pane fade m-3" id="v-pills-password" role="tabpanel" aria-labelledby="v-pills-password-tab">
                    {{-- Load  Password Tab --}}
                    @include('helper.profile.partials.password')
                </div>
                {{-- Social Links Tab --}}
                <div class="tab-pane fade m-3" id="v-pills-social" role="tabpanel" aria-labelledby="v-pills-social-tab">
                    {{-- Load  Social Links Tab --}}
                    @include('helper.profile.partials.social')
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
                        {{-- Show only if company enabled --}}
                        <button class="nav-link" id="v-pills-address-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-address" type="button" role="tab" aria-controls="v-pills-address"
                            aria-selected="false">
                            <i class="fas fa-map-marker-alt"></i> <span class="d-none d-md-inline">Address</span>
                        </button>
                        <button class="nav-link" id="v-pills-password-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-password" type="button" role="tab"
                            aria-controls="v-pills-password" aria-selected="false">
                            <i class="fas fa-key"></i> <span class="d-none d-md-inline">Password</span>
                        </button>
                        <button class="nav-link" id="v-pills-social-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-social" type="button" role="tab" aria-controls="v-pills-social"
                            aria-selected="false">
                            <i class="fas fa-link"></i> <span class="d-none d-md-inline">Social</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>


@endsection
