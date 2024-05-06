<table class="table table-striped">
    <thead class="thead-primary">
        <tr>
            <th>ID</th>
            <th>Profile Image</th>
            <th>Full Name</th>
            <th>Account Type</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($clients as $client)
            <tr>
                <td>{{ $client->id }}</td>
                <td><img src="{{ $client->profile_image ? asset($client->profile_image) : asset('images/users/default.png') }}"
                        alt="Profile Image" width="50">
                </td>
                <td>{{ $client->first_name . ' ' . $client->last_name }}</td>
                <td>{{ $client->company_enabled ? 'Company' : 'Individual' }}</td>
                <td>
                    <button type="button" id="statusButton_{{ $client->id }}"
                        class="btn  {{ $client->is_active ? 'btn-primary' : 'btn-danger' }} btn-sm"
                        onclick="showStatusDialog({{ $client->id }}, '{{ $client->is_active }}')">
                        {{ $client->is_active == 1 ? 'Active' : 'Inactive' }}
                    </button>
                </td>
                {{-- <td>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="statusButton_{{ $client->id }}"
                            onclick="showStatusDialog({{ $client->id }}, {{ $client->is_active }})"
                            {{ $client->is_active ? 'checked' : '' }}>
                        <label class="custom-control-label" for="statusButton_{{ $client->id }}"></label>
                    </div>
                </td> --}}
                <td>
                    {{-- View Route --}}
                    <a class="btn btn-sm btn-primary" href="{{ route('admin.client.show', $client->id) }}"><i
                            class="fa fa-eye"></i></a>
                    {{-- Edit Route --}}
                    <a class="btn btn-sm btn-primary" href="{{ route('admin.client.edit', $client->id) }}"><i
                            class="fa fa-edit"></i></a>
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                {{-- <a id="updateStatusLink" href="#" class="btn btn-primary">Update</a> --}}
                <button type="button" id="updateStatusLink" class="btn btn-primary">Update</button>
            </div>
        </div>
    </div>
</div>

{{ $clients->links() }}



<script>
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
</script>
