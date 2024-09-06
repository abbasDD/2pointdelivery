@extends('admin.layouts.app')

@section('title', 'Sub Admins')

@section('content')

    <section class="section p-0">
        <div class="container-fluid">
            <div class="section-header mb-2">
                <div class="d-flex justify-content-between my-3">
                    <h4 class="mb-0">Sub Admins</h4>
                    <a href="{{ route('admin.admin.create') }}" class="btn btn-primary btn-sm">Add</a>
                </div>
            </div>
            <div class="section-body">
                <table class="table table-striped">
                    <thead class="thead-primary">
                        <tr>
                            <th scope="col">#</th>
                            <th>Profile Image</th>
                            <th scope="col">First Name</th>
                            <th scope="col">Last Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Type</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($admins as $admin)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td><img src="{{ isset($admin->thumbnail) && $admin->thumbnail !== null ? asset('images/users/thumbnail/' . $admin['thumbnail']) : asset('images/users/default.png') }}"
                                        width="50px" height="50px"></td>
                                <td>{{ $admin->first_name }}</td>
                                <td>{{ $admin->last_name }}</td>
                                <td>{{ $admin->user->email }}</td>
                                <td>{{ $admin->admin_type }}</td>
                                <td>
                                    <button type="button" id="statusButton_{{ $admin->id }}"
                                        class="btn  {{ $admin->user->is_active ? 'btn-primary' : 'btn-danger' }} btn-sm"
                                        onclick="showStatusDialog({{ $admin->id }}, '{{ $admin->user->is_active }}')">
                                        {{ $admin->user->is_active == 1 ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td>
                                    <a href="{{ route('admin.admin.edit', $admin->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No data found</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>

            </div>
        </div>
    </section>

    {{-- Status Modal --}}
    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Update Status</h5>
                </div>
                <div class="modal-body">
                    Are you sure you want to update status?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('statusModal')">Close</button>
                    {{-- <a id="updateStatusLink" href="#" class="btn btn-primary">Update</a> --}}
                    <button type="button" id="updateStatusLink" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showStatusDialog(id, status) {
            $('#statusModal').modal('show');
            var baseUrl = "{{ url('admin/admins/update-status') }}"; // Use `url()` to get the base part
            // $('#updateStatusLink').attr('href', baseUrl + '/' + id);

            // Remove previous click event handler from #updateStatusLink
            $('#updateStatusLink').off('click');
            // add onclick to updateStatusLink here
            $('#updateStatusLink').click(function() {
                updateStatus(id);
            });
            // console.log($('#statusButton_' + id).text());
            if ($('#statusButton_' + id).text().trim() == 'Active') {
                $('#updateStatusLink').text("Decactivate");
                $('#updateStatusLink').removeClass('btn-primary');
                $('#updateStatusLink').addClass('btn-danger');
            } else {
                $('#updateStatusLink').text("Activate");
                $('#updateStatusLink').removeClass('btn-danger');
                $('#updateStatusLink').addClass('btn-primary');
            }

        }

        function updateStatus(id) {
            console.log(id);
            var baseUrl = "{{ url('admin/admins/update-status') }}";
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
                        $('#statusModal').modal('hide');
                        // Change text on bbutton as per response
                        if (jsonResponse.is_active == 1) {
                            $('#statusButton_' + id).text('Inactive');
                            $('#statusButton_' + id).removeClass('btn-primary');
                            $('#statusButton_' + id).addClass('btn-danger');
                        }
                        if (jsonResponse.is_active == 0) {
                            $('#statusButton_' + id).text('Active');
                            $('#statusButton_' + id).removeClass('btn-danger');
                            $('#statusButton_' + id).addClass('btn-primary');
                        }
                        // Trigger Notification
                        triggerToast('Success', 'Status updated succcessfully');
                        // Remove function from button
                        $('updateStatusLink').off('click');
                        console.log(jsonResponse.message); // Print the message from the response
                    } else {
                        // Hide modal
                        $('#statusModal').modal('hide');
                        // Remove function from button
                        $('updateStatusLink').off('click');
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

@endsection
