<table class="table table-striped">
    <thead class="thead-primary">
        <tr>
            <th>ID</th>
            <th>Profile Image</th>
            <th>Email</th>
            <th>Full Name</th>
            <th>Account Type</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($helpers as $helper)
            <tr id="helper_{{ $helper->id }}">
                <td>{{ $helper->id }}</td>
                <td><img src="{{ $helper->profile_image ? asset($helper->profile_image) : asset('images/users/default.png') }}"
                        alt="Profile Image" width="50">
                </td>
                <td>{{ $helper->email }}</td>
                <td>{{ $helper->first_name . ' ' . $helper->last_name }}</td>
                <td>{{ $helper->company_enabled ? 'Company' : 'Individual' }}</td>
                <td>
                    <button type="button" id="approveButton_{{ $helper->id }}" class="btn btn-primary btn-sm"
                        onclick="showApproveDialog({{ $helper->id }})">
                        Approve
                    </button>
                    <button type="button" id="rejectButton_{{ $helper->id }}" class="btn btn-danger btn-sm"
                        onclick="showRejectDialog({{ $helper->id }})">
                        Reject
                    </button>
                </td>
                <td>
                    {{-- View Route --}}
                    <a class="btn btn-sm btn-primary" href="{{ route('admin.helper.show', $helper->id) }}"><i
                            class="fa fa-eye"></i></a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">No data found</td>
            </tr>
        @endforelse
    </tbody>
</table>

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
                <button type="button" class="btn btn-secondary" onclick="closeModal('statusModal')"
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
                <button type="button" class="btn btn-secondary" onclick="closeModal('statusModal')"
                    data-dismiss="modal">Close</button>
                {{-- <a id="rejectHelperLink" href="#" class="btn btn-primary">Update</a> --}}
                <button type="button" id="rejectHelperLink" class="btn btn-danger">Reject</button>
            </div>
        </div>
    </div>
</div>

{{ $helpers->links() }}



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
                    $('#helper_' + id).remove();

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
                    $('#helper_' + id).remove();

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
