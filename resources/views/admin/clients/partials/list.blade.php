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
        @forelse ($clients as $client)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td><img src="{{ $client->thumbnail ? asset('images/users/thumbnail/' . $client->thumbnail) : asset('images/users/default.png') }}"
                        alt="Profile Image" width="50">
                </td>
                <td>{{ $client->user->email }}</td>
                <td>{{ $client->first_name . ' ' . $client->last_name }}</td>
                <td>{{ $client->company_enabled ? 'Company' : 'Individual' }}</td>
                <td>
                    <button type="button" id="statusButton_{{ $client->id }}"
                        class="btn  {{ $client->user->is_active ? 'btn-primary' : 'btn-danger' }} btn-sm"
                        onclick="showStatusDialog({{ $client->id }}, '{{ $client->user->is_active }}')">
                        {{ $client->user->is_active == 1 ? 'Active' : 'Inactive' }}
                    </button>
                </td>
                <td class="gap-1">
                    {{-- View Route --}}
                    <a class="btn btn-sm btn-primary" href="{{ route('admin.client.show', $client->id) }}"><i
                            class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="View Profile"></i></a>
                    {{-- Edit Route --}}
                    <a class="btn btn-sm btn-primary" href="{{ route('admin.client.profile', $client->id) }}"><i
                            class="fa fa-edit" data-toggle="tooltip" data-placement="top" title="Edit Profile"></i></a>
                    {{-- Reset Password --}}
                    <a class="btn btn-sm btn-primary" onclick="resetPasswordDialog({{ $client->id }})"><i
                            class="fa-solid fa-lock" data-toggle="tooltip" data-placement="top"
                            title="Reset Password"></i></a>
                    {{-- Impersonate --}}
                    @canImpersonate($guard = null)
                    <a class="btn btn-sm btn-primary"
                        href="{{ route('impersonate', $client->user->id) }}">Impersonate</a>
                    @endCanImpersonate
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">No data found</td>
            </tr>
        @endforelse
    </tbody>
</table>

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

<div class="modal fade" id="resetPasswordModal" tabindex="-1" role="dialog" aria-labelledby="resetPasswordModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetPasswordModalLabel">Reset Password</h5>
            </div>
            <div class="modal-body">

                <p>Please copy newly created password and share it with user. Click update to update password</p>

                <p>New Password</p>
                <div class="d-flex gap-3">
                    <h5 class="mb-0" id="newPassword"></h5>
                    <button class="btn btn-primary btn-sm" onclick="copyToClipboard('#newPassword')"><i
                            class="fa fa-copy"></i></button>
                </div>
                <p id="success-copied" class="d-none">Copied Successfully</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    onclick="closeModal('resetPasswordModal')">Close</button>
                {{-- <a id="updateStatusLink" href="#" class="btn btn-primary">Update</a> --}}
                <button type="button" id="resetPasswordLink" class="btn btn-primary">Update</button>
            </div>
        </div>
    </div>
</div>

{{ $clients->links() }}



<script>
    // Define a variable for password
    var newPassword;

    function showStatusDialog(id, status) {
        $('#statusModal').modal('show');
        var baseUrl = "{{ url('admin/clients/update-status') }}"; // Use `url()` to get the base part
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
        var baseUrl = "{{ url('admin/clients/update-status') }}";
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

    function resetPasswordDialog(id) {

        $('#resetPasswordModal').modal('show');
        // Generate a aphanumeric password
        newPassword = Math.random().toString(36).slice(-8);
        console.log(newPassword);

        $('#newPassword').text(newPassword);

        // Hide success message
        $("#success-copied").addClass("d-none");

        // Remove previous click event handler from #resetPasswordLink
        $('#resetPasswordLink').off('click');
        // add onclick to resetPasswordLink here
        $('#resetPasswordLink').click(function() {
            resetPassword(id);
        });

    }

    function resetPassword(id) {
        var baseUrl = "{{ url('admin/clients/reset-password') }}";
        var csrfToken = $('meta[name="csrf-token"]').attr('content'); // Get CSRF token from the meta tag

        $.ajax({
            url: baseUrl,
            data: {
                id: id,
                password: newPassword,
                _token: csrfToken
            },
            type: 'POST', // or 'GET' depending on your route definition
            success: function(response) {
                // Handle the response
                console.log(response); // Log the response for debugging
                var jsonResponse = JSON.parse(response); //Parse the JSON string into an object
                if (jsonResponse.status == 'success') {
                    // Hide modal
                    $('#resetPasswordModal').modal('hide');
                    // Trigger Notification
                    triggerToast('Success', 'Password reset succcessfully');
                    // Remove function from button
                    $('resetPasswordLink').off('click');
                    console.log(jsonResponse.message); // Print the message from the response
                } else {
                    // Hide modal
                    $('#resetPasswordModal').modal('hide');
                    // Remove function from button
                    $('resetPasswordLink').off('click');
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


    // Copy to clip board
    function copyToClipboard(element) {
        var $temp = newPassword;
        // Caopy this newPassword value to clipboard
        navigator.clipboard.writeText($temp);
        // Trigger Notification
        triggerToast('Success', 'Password copied to clipboard');

        // Show success message
        $("#success-copied").removeClass("d-none");

    }
</script>
