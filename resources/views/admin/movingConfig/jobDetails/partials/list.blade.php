<table class="table table-striped">
    <thead class="thead-primary">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Helper Fee</th>
            <th>Description</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($jobDetails as $jobDetail)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $jobDetail->name }}</td>
                <td>${{ $jobDetail->price }}</td>
                <td>${{ $jobDetail->helper_fee }}</td>
                <td>{{ $jobDetail->description ?? '-' }}</td>
                <td>
                    <button type="button" id="statusjobDetailButton_{{ $jobDetail->id }}"
                        class="btn  {{ $jobDetail->is_active ? 'btn-primary' : 'btn-danger' }} btn-sm"
                        onclick="showjobDetailStatusDialog({{ $jobDetail->id }}, '{{ $jobDetail->is_active }}')">
                        {{ $jobDetail->is_active == 1 ? 'Active' : 'Inactive' }}
                    </button>
                </td>

                <td><a class="btn btn-sm btn-primary"
                        href="{{ route('admin.movingConfig.jobDetails.edit', $jobDetail->id) }}"><i
                            class="fa fa-edit"></i></a></td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">No data found</td>
            </tr>
        @endforelse
    </tbody>
</table>


<div class="modal fade" id="jobDetailStatusModal" tabindex="-1" role="dialog"
    aria-labelledby="jobDetailStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="jobDetailStatusModalLabel">Update Status</h5>
            </div>
            <div class="modal-body">
                Are you sure you want to update status?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('jobDetailStatusModal')"
                    data-dismiss="modal">Close</button>
                <a id="updatejobDetailStatusLink" href="#" class="btn btn-primary">Update</a>
            </div>
        </div>
    </div>
</div>


{{ $jobDetails->links() }}



<script>
    function showjobDetailStatusDialog(id, status) {
        $('#jobDetailStatusModal').modal('show');
        var baseUrl = "{{ url('admin/moving-config/no-of-rooms/update-status') }}"; // Use `url()` to get the base part
        // $('#updatejobDetailStatusLink').attr('href', baseUrl + '/' + id);

        // Remove previous click event handler from #updatejobDetailStatusLink
        $('#updatejobDetailStatusLink').off('click');
        // add onclick to updatejobDetailStatusLink here
        $('#updatejobDetailStatusLink').click(function() {
            updatejobDetailStatus(id);
        });
        // console.log($('#statusjobDetailButton_' + id).text());
        if ($('#statusjobDetailButton_' + id).text().trim() == 'Active') {
            $('#updatejobDetailStatusLink').text("Decactivate");
            $('#updatejobDetailStatusLink').removeClass('btn-primary');
            $('#updatejobDetailStatusLink').addClass('btn-danger');
        } else {
            $('#updatejobDetailStatusLink').text("Activate");
            $('#updatejobDetailStatusLink').removeClass('btn-danger');
            $('#updatejobDetailStatusLink').addClass('btn-primary');
        }

    }

    function updatejobDetailStatus(id) {
        console.log(id);
        var baseUrl = "{{ url('admin/moving-config/no-of-rooms/update-status') }}";
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
                    $('#jobDetailStatusModal').modal('hide');
                    // Change text on bbutton as per response
                    if (jsonResponse.is_active == 1) {
                        $('#statusjobDetailButton_' + id).text('Inactive');
                        $('#statusjobDetailButton_' + id).removeClass('btn-primary');
                        $('#statusjobDetailButton_' + id).addClass('btn-danger');
                    }
                    if (jsonResponse.is_active == 0) {
                        $('#statusjobDetailButton_' + id).text('Active');
                        $('#statusjobDetailButton_' + id).removeClass('btn-danger');
                        $('#statusjobDetailButton_' + id).addClass('btn-primary');
                    }

                    // Trigger Notification
                    triggerToast('Success', 'jobDetail Status updated succcessfully');
                    // Remove function from button
                    $('updatejobDetailStatusLink').off('click');
                    console.log(jsonResponse.message); // Print the message from the response
                } else {
                    // Hide modal
                    $('#jobDetailStatusModal').modal('hide');
                    // Remove function from button
                    $('updatejobDetailStatusLink').off('click');
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
