@extends('client.layouts.app')

@section('title', 'Edit Profile')

@section('content')

    <div class="container">
        <div class="row">
            {{-- Show at top on Mobile Devices --}}
            <div class="col-md-2 d-block d-md-none">
                <div class="card mt-3">
                    <div class="card-body p-3">
                        <div class="d-flex nav nav-pills justify-content-between" id="v-pills-tab" role="tablist"
                            aria-orientation="vertical">
                            <button class="nav-link active {{ $clientData->first_name == null ? 'text-danger' : '' }}"
                                id="v-pills-personal-tab" data-bs-toggle="pill" data-bs-target="#v-pills-personal"
                                type="button" role="tab" aria-controls="v-pills-personal" aria-selected="true">
                                <i class="fas fa-user"></i> <span class="d-none d-lg-inline">Personal</span>
                            </button>
                            <button class="nav-link {{ $clientData->suite == null ? 'text-danger' : '' }}"
                                id="v-pills-address-tab" data-bs-toggle="pill" data-bs-target="#v-pills-address"
                                type="button" role="tab" aria-controls="v-pills-address" aria-selected="true">
                                <i class="fas fa-map-marker-alt"></i> <span class="d-none d-lg-inline">Address</span>
                            </button>
                            @if ($clientData->company_enabled)
                                <button
                                    class="nav-link {{ $clientCompanyData->company_alias == null ? 'text-danger' : '' }}"
                                    id="v-pills-company-tab" data-bs-toggle="pill" data-bs-target="#v-pills-company"
                                    type="button" role="tab" aria-controls="v-pills-company" aria-selected="false">
                                    <i class="fas fa-building"></i> <span class="d-none d-lg-inline">Company</span>
                                </button>
                            @endif
                            <button class="nav-link" id="v-pills-password-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-password" type="button" role="tab"
                                aria-controls="v-pills-password" aria-selected="false">
                                <i class="fas fa-key"></i> <span class="d-none d-lg-inline">Password</span>
                            </button>
                            <button class="nav-link" id="v-pills-social-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-social" type="button" role="tab"
                                aria-controls="v-pills-social" aria-selected="false">
                                <i class="fas fa-link"></i> <span class="d-none d-lg-inline">Social</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-10 col-sm-10">
                <div class="tab-content flex-grow-1" id="v-pills-tabContent">
                    {{-- Personal Tab  --}}
                    <div class="tab-pane fade show active" id="v-pills-personal" role="tabpanel"
                        aria-labelledby="v-pills-personal-tab">
                        {{-- Load Personal Tab  --}}
                        @include('client.profile.partials.personal')
                    </div>
                    {{-- Address Tab  --}}
                    <div class="tab-pane fade" id="v-pills-address" role="tabpanel" aria-labelledby="v-pills-address-tab">
                        {{-- Load Personal Tab  --}}
                        @include('client.profile.partials.address')
                    </div>
                    @if ($clientData->company_enabled)
                        {{-- Company Tab --}}
                        <div class="tab-pane fade" id="v-pills-company" role="tabpanel"
                            aria-labelledby="v-pills-company-tab">
                            {{-- Load Address Tab --}}
                            @include('client.profile.partials.company')
                        </div>
                    @endif
                    {{--  Password Tab --}}
                    <div class="tab-pane fade" id="v-pills-password" role="tabpanel" aria-labelledby="v-pills-password-tab">
                        {{-- Load  Password Tab --}}
                        @include('client.profile.partials.password')
                    </div>
                    {{-- Social Links Tab --}}
                    <div class="tab-pane fade" id="v-pills-social" role="tabpanel" aria-labelledby="v-pills-social-tab">
                        {{-- Load  Social Links Tab --}}
                        @include('client.profile.partials.social')
                    </div>
                </div>
            </div>
            {{-- Show on right side on Desktop --}}
            <div class="col-md-2 d-none d-md-block">
                <div class="card mt-3">
                    <div class="card-body p-3">
                        <div class="d-flex flex-column nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                            aria-orientation="vertical">
                            <button class="nav-link active {{ $clientData->first_name == null ? 'text-danger' : '' }}"
                                id="v-pills-personal-tab" data-bs-toggle="pill" data-bs-target="#v-pills-personal"
                                type="button" role="tab" aria-controls="v-pills-personal" aria-selected="true">
                                <i class="fas fa-user"></i> <span class="d-none d-lg-inline">Personal</span>
                            </button>
                            <button class="nav-link {{ $clientData->suite == null ? 'text-danger' : '' }}"
                                id="v-pills-address-tab" data-bs-toggle="pill" data-bs-target="#v-pills-address"
                                type="button" role="tab" aria-controls="v-pills-address" aria-selected="true">
                                <i class="fas fa-map-marker-alt"></i> <span class="d-none d-lg-inline">Address</span>
                            </button>
                            @if ($clientData->company_enabled)
                                <button
                                    class="nav-link {{ $clientCompanyData->company_alias == null ? 'text-danger' : '' }}"
                                    id="v-pills-company-tab" data-bs-toggle="pill" data-bs-target="#v-pills-company"
                                    type="button" role="tab" aria-controls="v-pills-company" aria-selected="false">
                                    <i class="fas fa-building"></i> <span class="d-none d-lg-inline">Company</span>
                                </button>
                            @endif
                            <button class="nav-link" id="v-pills-password-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-password" type="button" role="tab"
                                aria-controls="v-pills-password" aria-selected="false">
                                <i class="fas fa-key"></i> <span class="d-none d-lg-inline">Password</span>
                            </button>
                            <button class="nav-link" id="v-pills-social-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-social" type="button" role="tab"
                                aria-controls="v-pills-social" aria-selected="false">
                                <i class="fas fa-link"></i> <span class="d-none d-lg-inline">Social</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


@endsection
