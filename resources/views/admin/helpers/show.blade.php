@extends('admin.layouts.app')

@section('title', 'Helper')

@section('content')

    @empty($helper)
        <p> No helper found </p>
    @endempty

    @isset($helper)
        <section class="section">
            <div class="container-fluid">
                <div class="section-header mb-2">
                    <div class="d-flex justify-content-between">
                        <h4 class="mb-0">Helper Details</h4>

                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <img class="mb-3"
                                        src="{{ $helper->profile_image ? asset($helper->profile_image) : asset('images/users/default.png') }}"
                                        alt="Profile Image" width="50">
                                    <h4 class="mb-0">{{ $helper->first_name . ' ' . $helper->last_name }}</h4>
                                    <p>{{ $helper->email }}</p>

                                    {{-- Sepration Line --}}
                                    <hr>

                                    {{-- Email Verfied  --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0">Email Verified</h6>
                                        <p class="mb-0">{{ $helper->email_verified_at ? 'Yes' : 'No' }}</p>
                                    </div>
                                    {{-- Show Phone Number --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0">Phone</h6>
                                        <p class="mb-0">{{ $helper->phone_no ?: 'N/A' }}</p>
                                    </div>
                                    {{-- Show Gender --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0">Gender</h6>
                                        <p class="mb-0">{{ $helper->gender ?: 'N/A' }}</p>
                                    </div>
                                    {{-- Account Type --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0">Account</h6>
                                        <p class="mb-0">{{ $helper->company_enabled ? 'Company' : 'Individual' }}</p>
                                    </div>
                                    {{-- Tax ID --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0">Tax ID</h6>
                                        <p class="mb-0">{{ $helper->tax_id ?: 'N/A' }}</p>
                                    </div>
                                    {{-- Suite --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0">Suite</h6>
                                        <p class="mb-0">{{ $helper->suite ?: 'N/A' }}</p>
                                    </div>
                                    {{-- Street --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0">Street</h6>
                                        <p class="mb-0">{{ $helper->street ?: 'N/A' }}</p>
                                    </div>
                                    {{-- City --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0">City</h6>
                                        <p class="mb-0">{{ $helper->city ?: 'N/A' }}</p>
                                    </div>
                                    {{-- State --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0">State</h6>
                                        <p class="mb-0">{{ $helper->state ?: 'N/A' }}</p>
                                    </div>
                                    {{-- Country --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0">Country</h6>
                                        <p class="mb-0">{{ $helper->country ?: 'N/A' }}</p>
                                    </div>
                                    {{-- Zip Code --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0">Zip Code</h6>
                                        <p class="mb-0">{{ $helper->zip_code ?: 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        @include('admin.bookings.partials.list')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endisset


@endsection
