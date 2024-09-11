@extends('admin.layouts.app')

@section('title', 'Helper')

@section('content')

    @empty($helper)
        <p> No helper found </p>
    @endempty

    @isset($helper)
        <section class="section p-0">
            <div class="container-fluid">
                <div class="section-header mb-2">
                    <div class="d-flex justify-content-between">
                        <h4 class="mb-0">Helper Details</h4>
                        {{-- Show Approval Buttons if not approved --}}
                        @if ($helper->is_approved == 0)
                            <div id="action-buttons" class=" d-flex align-items-center">
                                <button type="button" id="approveButton_{{ $helper->id }}" class="btn btn-primary btn-sm mr-2"
                                    onclick="showApproveDialog({{ $helper->id }})">
                                    Approve
                                </button>
                                <button type="button" id="rejectButton_{{ $helper->id }}" class="btn btn-danger btn-sm"
                                    onclick="showRejectDialog({{ $helper->id }})">
                                    Reject
                                </button>
                            </div>
                        @else
                            {{-- Show Status --}}
                            <p class="{{ $helper->is_approved == 1 ? 'bg-success' : 'bg-danger' }} badge text-white">
                                Helper {{ $helper->is_approved == 1 ? 'Approved' : 'Rejected' }}</p>
                        @endif



                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <img class="mb-3"
                                        src="{{ $helper->profile_image ? asset('images/users/' . $helper->profile_image) : asset('images/users/default.png') }}"
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
                                        <p class="mb-0">{{ $helper->phone_no ?: '-' }}</p>
                                    </div>
                                    {{-- Show Gender --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0">Gender</h6>
                                        <p class="mb-0">{{ $helper->gender ?: '-' }}</p>
                                    </div>
                                    {{-- Date of Birth --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0">Date of Birth</h6>
                                        <p class="mb-0">
                                            {{ date(config('date_format') ?: 'Y-m-d', strtotime($helper->date_of_birth)) ?: '-' }}
                                        </p>
                                    </div>
                                    {{-- Account Type --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0">Account</h6>
                                        <p class="mb-0">{{ $helper->company_enabled ? 'Company' : 'Individual' }}</p>
                                    </div>
                                    {{-- Service Badge ID --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0">Service Badge ID</h6>
                                        <p class="mb-0">{{ $helper->service_badge_id ?: '-' }}</p>
                                    </div>
                                    {{-- Suite --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0">Suite</h6>
                                        <p class="mb-0">{{ $helper->suite ?: '-' }}</p>
                                    </div>
                                    {{-- Street --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0">Street</h6>
                                        <p class="mb-0">{{ $helper->street ?: '-' }}</p>
                                    </div>
                                    {{-- City --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0">City</h6>
                                        <p class="mb-0">{{ app('addressHelper')->getCityName($helper->city) }}</p>
                                    </div>
                                    {{-- State --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0">State</h6>
                                        <p class="mb-0">{{ app('addressHelper')->getStateName($helper->state) }}</p>
                                        {{-- <p class="mb-0">{{ $helper->state ?: '-' }}</p> --}}
                                    </div>
                                    {{-- Country --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0">Country</h6>
                                        <p class="mb-0">{{ app('addressHelper')->getCountryName($helper->country) }}</p>
                                    </div>
                                    {{-- Zip Code --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="mb-0">Zip Code</h6>
                                        <p class="mb-0">{{ $helper->zip_code ?: '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            {{-- Helper Vehicle --}}
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h4>Helper Vehicle</h4>
                                    </div>
                                    @if ($helper_vehicle)
                                        <div class="row">
                                            {{-- Vehicle Type --}}
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <p class="mb-0">
                                                        <span class="fw-bold">Vehicle Type : </span>
                                                        {{ $helper_vehicle->vehicleType ? $helper_vehicle->vehicleType->name : '-' }}
                                                    </p>
                                                </div>
                                            </div>
                                            {{-- Vehicle Number --}}
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <p class="mb-0">
                                                        <span class="fw-bold">Vehicle Number : </span>
                                                        {{ $helper_vehicle->vehicle_number ?: '-' }}
                                                    </p>
                                                </div>
                                            </div>
                                            {{-- Vehicle Make --}}
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-center  mb-3">
                                                    <p class="mb-0">
                                                        <span class="fw-bold">Vehicle Make : </span>
                                                        {{ $helper_vehicle->vehicle_make ?: '-' }}
                                                    </p>
                                                </div>
                                            </div>
                                            {{-- Vehicle Model --}}
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-center  mb-3">
                                                    <p class="mb-0">
                                                        <span class="fw-bold">Vehicle Model : </span>
                                                        {{ $helper_vehicle->vehicle_model ?: '-' }}
                                                    </p>
                                                </div>
                                            </div>
                                            {{-- Vehicle Color --}}
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-center  mb-3">
                                                    <p class="mb-0">
                                                        <span class="fw-bold">Vehicle Color : </span>
                                                        {{ $helper_vehicle->vehicle_color ?: '-' }}
                                                    </p>
                                                </div>
                                            </div>
                                            {{-- Approve/ Reject Button --}}
                                            <div class="col-md-12">
                                                @if ($helper_vehicle->is_approved == 0)
                                                    <div class="d-flex align-items-center gap-3 justify-content-end">
                                                        <a href="{{ route('admin.helpers.vehicles.approve', $helper_vehicle->id) }}"
                                                            class="btn btn-primary btn-sm">Approve Vehicle</a>
                                                        <a href="{{ route('admin.helpers.vehicles.reject', $helper_vehicle->id) }}"
                                                            class="btn btn-danger btn-sm">Reject Vehicle</a>
                                                    </div>
                                                @else
                                                    {{-- Show Status --}}

                                                    <div class="d-flex align-items-center gap-3 justify-content-end">
                                                        <p
                                                            class="{{ $helper_vehicle->is_approved == 1 ? 'bg-success' : 'bg-danger' }} badge text-white">
                                                            Vehicle
                                                            {{ $helper_vehicle->is_approved == 1 ? 'Approved' : 'Rejected' }}
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <p>No vehicle found</p>
                                    @endif
                                </div>
                            </div>
                            {{-- Booking List --}}
                            <div class="card">
                                <div class="card-body">
                                    <h4>Bookings</h4>
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


    {{-- Approve Helper Modal --}}
    <div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveModalLabel">Approve Helper</h5>
                </div>
                <div class="modal-body">
                    Are you sure you want to approve this helper?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('approveModal')"
                        data-dismiss="modal">Close</button>
                    {{-- <a id="approveHelperLink" href="#" class="btn btn-primary">Update</a> --}}
                    <button type="button" id="approveHelperLink" class="btn btn-primary">Approve</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Reject Helper Modal --}}
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Reject Helper</h5>
                </div>
                <div class="modal-body">
                    Are you sure you want to reject this helper?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('rejectModal')"
                        data-dismiss="modal">Close</button>
                    {{-- <a id="rejectHelperLink" href="#" class="btn btn-primary">Update</a> --}}
                    <button type="button" id="rejectHelperLink" class="btn btn-danger">Reject</button>
                </div>
            </div>
        </div>
    </div>

@endsection


<script>
    function showApproveDialog(id, status) {
        $('#approveModal').modal('show');

        // Remove previous click event handler from #approveHelperLink
        $('#approveHelperLink').off('click');
        // add onclick to approveHelperLink here
        $('#approveHelperLink').click(function() {
            approveHelper(id);
        });

    }

    function approveHelper(id) {
        console.log(id);
        var baseUrl = "{{ url('admin/helpers/approve') }}";
        var csrfToken = $('meta[name="csrf-token"]').attr('content'); // Get CSRF token from the meta tag

        $.ajax({
            url: baseUrl,
            data: {
                id: id,
                _token: csrfToken
            },
            type: 'POST', // or 'GET' depending on your route definition
            success: function(response) {
                // Handle the response
                console.log(response); // Log the response for debugging
                var jsonResponse = JSON.parse(response); //Parse the JSON string into an object
                if (jsonResponse.status == 'success') {
                    // Hide modal
                    $('#approveModal').modal('hide');

                    // Trigger Notification
                    triggerToast('Success', 'Helper approved succcessfully');
                    // Remove function from button
                    $('approveHelperLink').off('click');

                    // Remove row from list
                    $('#action-buttons').remove();

                    console.log(jsonResponse.message); // Print the message from the response
                } else {
                    // Hide modal
                    $('#approveModal').modal('hide');
                    // Remove function from button
                    $('approveHelperLink').off('click');
                    console.log('Failed'); // Or any other message you want to print for failed status
                }
            },
            error: function(xhr, status, error) {
                // Handle errors
                console.error(error); // Log the error for debugging
                // Show an error message to the user
            }
        });

    }

    // Reject Helper Modal Open

    function showRejectDialog(id, status) {
        $('#rejectModal').modal('show');

        // Remove previous click event handler from #rejectHelperLink
        $('#rejectHelperLink').off('click');
        // add onclick to rejectHelperLink here
        $('#rejectHelperLink').click(function() {
            rejectHelper(id);
        });
    }

    function rejectHelper(id) {
        console.log(id);
        var baseUrl = "{{ url('admin/helpers/reject') }}";
        var csrfToken = $('meta[name="csrf-token"]').attr('content'); // Get CSRF token from the meta tag

        $.ajax({
            url: baseUrl,
            data: {
                id: id,
                _token: csrfToken
            },
            type: 'POST', // or 'GET' depending on your route definition
            success: function(response) {
                // Handle the response
                console.log(response); // Log the response for debugging
                var jsonResponse = JSON.parse(response); //Parse the JSON string into an object
                if (jsonResponse.status == 'success') {
                    // Hide modal
                    $('#rejectModal').modal('hide');

                    // Trigger Notification
                    triggerToast('Success', 'Helper rejectd succcessfully');
                    // Remove function from button
                    $('rejectHelperLink').off('click');

                    // Remove row from list
                    $('#action-buttons').remove();

                    console.log(jsonResponse.message); // Print the message from the response
                } else {
                    // Hide modal
                    $('#rejectModal').modal('hide');
                    // Remove function from button
                    $('rejectHelperLink').off('click');

                    console.log('Failed'); // Or any other message you want to print for failed status
                }
            },
            error: function(xhr, status, error) {
                // Handle errors
                console.error(error); // Log the error for debugging
                // Show an error message to the user
            }
        });

    }
</script>
