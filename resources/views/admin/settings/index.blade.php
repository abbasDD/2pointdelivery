@extends('admin.layouts.app')

@section('title', 'System Settings')

@section('content')

    <div class="container">
        <div class="row">
            {{-- Show at top on Mobile Devices --}}
            <div class="col-md-2 d-block d-md-none">
                <div class="card mt-3">
                    <div class="card-body p-3">
                        <div class="d-flex nav nav-pills justify-content-between" id="v-pills-tab" role="tablist"
                            aria-orientation="vertical">
                            {{-- System Settings --}}
                            <button class="nav-link active" id="v-pills-system-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-system" type="button" role="tab"
                                aria-controls="v-pills-system" aria-selected="true">
                                <i class="fas fa-user"></i> <span class="d-none d-lg-inline">System</span>
                            </button>
                            {{-- Tax Settings --}}
                            <button class="nav-link" id="v-pills-taxSetting-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-taxSetting" type="button" role="tab"
                                aria-controls="v-pills-taxSetting" aria-selected="true">
                                <i class="fas fa-map-marker-alt"></i> <span class="d-none d-lg-inline">taxSetting</span>
                            </button>
                            {{-- Payment Settings --}}
                            <button class="nav-link" id="v-pills-paymentSettings-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-paymentSettings" type="button" role="tab"
                                aria-controls="v-pills-paymentSettings" aria-selected="false">
                                <i class="fas fa-building"></i> <span class="d-none d-lg-inline">Payment</span>
                            </button>
                            {{-- Social Login Settings --}}
                            <button class="nav-link" id="v-pills-socialLogin-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-socialLogin" type="button" role="tab"
                                aria-controls="v-pills-socialLogin" aria-selected="false">
                                <i class="fa-solid fa-hashtag"></i> <span class="d-none d-lg-inline">Social Login</span>
                            </button>
                            {{-- SMTP Settings --}}
                            <button class="nav-link" id="v-pills-smtpSettings-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-smtpSettings" type="button" role="tab"
                                aria-controls="v-pills-smtpSettings" aria-selected="false">
                                <i class="fas fa-envelope"></i> <span class="d-none d-lg-inline">SMTP</span>
                            </button>
                            {{-- Map Key --}}
                            <button class="nav-link" id="v-pills-mapKey-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-mapKey" type="button" role="tab"
                                aria-controls="v-pills-mapKey" aria-selected="false">
                                <i class="fas fa-map-marked-alt"></i> <span class="d-none d-lg-inline">Map Key</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-10 col-sm-10">
                <div class="tab-content flex-grow-1" id="v-pills-tabContent">
                    {{-- System Tab  --}}
                    <div class="tab-pane fade show active" id="v-pills-system" role="tabpanel"
                        aria-labelledby="v-pills-system-tab">
                        {{-- Load System Tab  --}}
                        @include('admin.settings.system.index')
                    </div>
                    {{-- taxSetting Tab  --}}
                    <div class="tab-pane fade" id="v-pills-taxSetting" role="tabpanel"
                        aria-labelledby="v-pills-taxSetting-tab">
                        {{-- Load taxSetting Tab  --}}
                        @include('admin.settings.tax.index')
                    </div>
                    {{-- Company Tab --}}
                    <div class="tab-pane fade" id="v-pills-paymentSettings" role="tabpanel"
                        aria-labelledby="v-pills-paymentSettings-tab">
                        {{-- Load taxSetting Tab --}}
                        @include('admin.settings.payment.index')
                    </div>
                    {{-- Social Login Tab --}}
                    <div class="tab-pane fade" id="v-pills-socialLogin" role="tabpanel"
                        aria-labelledby="v-pills-socialLogin-tab">
                        {{-- Load Social Login Tab --}}
                        @include('admin.settings.socialLogin.index')
                    </div>
                    {{-- SMTP Tab --}}
                    <div class="tab-pane fade" id="v-pills-smtpSettings" role="tabpanel"
                        aria-labelledby="v-pills-smtpSettings-tab">
                        {{-- Load SMTP Tab --}}
                        @include('admin.settings.smtp.index')
                    </div>
                    {{-- Map Key --}}
                    <div class="tab-pane fade" id="v-pills-mapKey" role="tabpanel" aria-labelledby="v-pills-mapKey-tab">
                        {{-- Load Map Key --}}
                        @include('admin.settings.mapKey.index')
                    </div>
                </div>
            </div>
            {{-- Show on right side on Desktop --}}
            <div class="col-md-2 d-none d-md-block">
                <div class="card mt-3">
                    <div class="card-body p-3">
                        <div class="d-flex flex-column nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                            aria-orientation="vertical">
                            {{-- System Settings --}}
                            <button class="nav-link active" id="v-pills-system-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-system" type="button" role="tab"
                                aria-controls="v-pills-system" aria-selected="true">
                                <i class="fas fa-user"></i> <span class="d-none d-lg-inline">System</span>
                            </button>
                            {{-- Tax Settings --}}
                            <button class="nav-link" id="v-pills-taxSetting-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-taxSetting" type="button" role="tab"
                                aria-controls="v-pills-taxSetting" aria-selected="true">
                                <i class="fas fa-map-marker-alt"></i> <span class="d-none d-lg-inline">Tax</span>
                            </button>
                            {{-- Payment Settings --}}
                            <button class="nav-link" id="v-pills-paymentSettings-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-paymentSettings" type="button" role="tab"
                                aria-controls="v-pills-paymentSettings" aria-selected="false">
                                <i class="fas fa-building"></i> <span class="d-none d-lg-inline">Payment</span>
                            </button>
                            {{-- Social Login Settings --}}
                            <button class="nav-link" id="v-pills-socialLogin-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-socialLogin" type="button" role="tab"
                                aria-controls="v-pills-socialLogin" aria-selected="false">
                                <i class="fa-solid fa-hashtag"></i> <span class="d-none d-lg-inline">Social Login</span>
                            </button>
                            {{-- SMTP Settings --}}
                            <button class="nav-link" id="v-pills-smtpSettings-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-smtpSettings" type="button" role="tab"
                                aria-controls="v-pills-smtpSettings" aria-selected="false">
                                <i class="fas fa-envelope"></i> <span class="d-none d-lg-inline">SMTP</span>
                            </button>
                            {{-- Map Key --}}
                            <button class="nav-link" id="v-pills-mapKey-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-mapKey" type="button" role="tab"
                                aria-controls="v-pills-mapKey" aria-selected="false">
                                <i class="fas fa-map"></i> <span class="d-none d-lg-inline">Map Key</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


@endsection
