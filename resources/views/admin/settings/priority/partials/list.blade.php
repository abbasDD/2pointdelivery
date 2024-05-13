<table class="table table-striped">
    <thead class="thead-primary">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Description</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($prioritySettings as $prioritySetting)
            <tr>
                <td>{{ $prioritySetting->id }}</td>
                <td>{{ $prioritySetting->name }}</td>
                <td>${{ $prioritySetting->price }}</td>
                <td>{{ $prioritySetting->description ?? 'N/A' }}</td>
                <td>
                    <button type="button" id="statusPriorityButton_{{ $prioritySetting->id }}"
                        class="btn  {{ $prioritySetting->is_active ? 'btn-primary' : 'btn-danger' }} btn-sm"
                        onclick="showPriorityStatusDialog({{ $prioritySetting->id }}, '{{ $prioritySetting->is_active }}')">
                        {{ $prioritySetting->is_active == 1 ? 'Active' : 'Inactive' }}
                    </button>
                </td>

                <td><a class="btn btn-sm btn-primary"
                        href="{{ route('admin.prioritySetting.edit', $prioritySetting->id) }}"><i
                            class="fa fa-edit"></i></a></td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">No data found</td>
            </tr>
        @endforelse
    </tbody>
</table>


<div class="modal fade" id="priorityStatusModal" tabindex="-1" role="dialog"
    aria-labelledby="priorityStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="priorityStatusModalLabel">Update Status</h5>
            </div>
            <div class="modal-body">
                Are you sure you want to update status?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('priorityStatusModal')"
                    data-dismiss="modal">Close</button>
                <a id="updatePriorityStatusLink" href="#" class="btn btn-primary">Update</a>
            </div>
        </div>
    </div>
</div>


{{ $prioritySettings->links() }}



<script>
    function showPriorityStatusDialog(id, status) {
        $('#priorityStatusModal').modal('show');
        var baseUrl = "{{ url('admin/settings/priority/update-status') }}"; // Use `url()` to get the base part
        // $('#updatePriorityStatusLink').attr('href', baseUrl + '/' + id);

        // Remove previous click event handler from #updatePriorityStatusLink
        $('#updatePriorityStatusLink').off('click');
        // add onclick to updatePriorityStatusLink here
        $('#updatePriorityStatusLink').click(function() {
            updatePriorityStatus(id);
        });
        // console.log($('#statusPriorityButton_' + id).text());
        if ($('#statusPriorityButton_' + id).text().trim() == 'Active') {
            $('#updatePriorityStatusLink').text("Decactivate");
            $('#updatePriorityStatusLink').removeClass('btn-primary');
            $('#updatePriorityStatusLink').addClass('btn-danger');
        } else {
            $('#updatePriorityStatusLink').text("Activate");
            $('#updatePriorityStatusLink').removeClass('btn-danger');
            $('#updatePriorityStatusLink').addClass('btn-primary');
        }

    }

    function updatePriorityStatus(id) {
        console.log(id);
        var baseUrl = "{{ url('admin/settings/priority/update-status') }}";
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
                    $('#priorityStatusModal').modal('hide');
                    // Change text on bbutton as per response
                    if (jsonResponse.is_active == 1) {
                        $('#statusPriorityButton_' + id).text('Inactive');
                        $('#statusPriorityButton_' + id).removeClass('btn-primary');
                        $('#statusPriorityButton_' + id).addClass('btn-danger');
                    }
                    if (jsonResponse.is_active == 0) {
                        $('#statusPriorityButton_' + id).text('Active');
                        $('#statusPriorityButton_' + id).removeClass('btn-danger');
                        $('#statusPriorityButton_' + id).addClass('btn-primary');
                    }

                    // Trigger Notification
                    triggerToast('Success', 'Priority Status updated succcessfully');
                    // Remove function from button
                    $('updatePriorityStatusLink').off('click');
                    console.log(jsonResponse.message); // Print the message from the response
                } else {
                    // Hide modal
                    $('#priorityStatusModal').modal('hide');
                    // Remove function from button
                    $('updatePriorityStatusLink').off('click');
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
