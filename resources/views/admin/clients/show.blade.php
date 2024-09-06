@extends('admin.layouts.app')

@section('title', 'Client')

@section('content')

    @empty($client)
        <p> No client found </p>
    @endempty

    @isset($client)
        <section class="section p-0">
            <div class="container-fluid">
                <div class="section-header mb-2">
                    <div class="d-flex justify-content-between">
                        <h4 class="mb-0">Client Details</h4>
                        <div class="">
                            {{-- Edit Route --}}
                            <a class="btn btn-sm btn-primary" href="{{ route('admin.client.profile', $client->id) }}">
                                Edit Client
                            </a>
                        </div>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <img class="mb-3"
                                        src="{{ $client->thumbnail ? asset('/images/users/thumbnail/' . $client->thumbnail) : asset('images/users/default.png') }}"
                                        alt="Profile Image" width="50">
                                    <h4 class="mb-0">{{ $client->first_name . ' ' . $client->last_name }}</h4>
                                    <p>{{ $client->user->email }}</p>

                                    {{-- Sepration Line --}}
                                    <hr>

                                    {{-- Email Verfied  --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0">Email Verified</h6>
                                        <p class="mb-0">{{ $client->user->email_verified_at ? 'Yes' : 'No' }}</p>
                                    </div>
                                    {{-- Show Phone Number --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0">Phone</h6>
                                        <p class="mb-0">{{ $client->phone_no ?: '-' }}</p>
                                    </div>
                                    {{-- Show Gender --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0">Gender</h6>
                                        <p class="mb-0">{{ $client->gender ?: '-' }}</p>
                                    </div>
                                    {{-- Date of Birth --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0">Date of Birth</h6>
                                        <p class="mb-0">
                                            {{ date(config('date_format') ?: 'Y-m-d', strtotime($client->date_of_birth)) ?: '-' }}
                                        </p>
                                    </div>
                                    {{-- Account Type --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0">Account</h6>
                                        <p class="mb-0">{{ $client->company_enabled ? 'Company' : 'Individual' }}</p>
                                    </div>
                                    {{-- Tax ID --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0">Tax ID</h6>
                                        <p class="mb-0">{{ $client->tax_id ?: '-' }}</p>
                                    </div>
                                    {{-- Suite --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0">Suite</h6>
                                        <p class="mb-0">{{ $client->suite ?: '-' }}</p>
                                    </div>
                                    {{-- Street --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0">Street</h6>
                                        <p class="mb-0">{{ $client->street ?: '-' }}</p>
                                    </div>
                                    {{-- City --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0">City</h6>
                                        <p class="mb-0">{{ app('addressHelper')->getCityName($client->city) }}</p>
                                    </div>
                                    {{-- State --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0">State</h6>
                                        <p class="mb-0">{{ app('addressHelper')->getStateName($client->state) }}</p>
                                    </div>
                                    {{-- Country --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0">Country</h6>
                                        <p class="mb-0">{{ app('addressHelper')->getCountryName($client->country) }}</p>
                                    </div>
                                    {{-- Zip Code --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0">Zip Code</h6>
                                        <p class="mb-0">{{ $client->zip_code ?: '-' }}</p>
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
