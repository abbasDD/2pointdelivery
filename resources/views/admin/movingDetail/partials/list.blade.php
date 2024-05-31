<table class="table table-striped">
    <thead class="thead-primary">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Weight</th>
            <th>Volume</th>
            <th>Description</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($movingDetails as $movingDetail)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $movingDetail->name }}</td>
                <td>{{ $movingDetail->weight }} Kgs</td>
                <td>{{ $movingDetail->volume }} Cu Ft</td>
                <td>{{ $movingDetail->description ?? '-' }}</td>
                <td>
                    <button type="button" id="statusmovingDetailButton_{{ $movingDetail->id }}"
                        class="btn  {{ $movingDetail->is_active ? 'btn-primary' : 'btn-danger' }} btn-sm"
                        onclick="showmovingDetailStatusDialog({{ $movingDetail->id }}, '{{ $movingDetail->is_active }}')">
                        {{ $movingDetail->is_active == 1 ? 'Active' : 'Inactive' }}
                    </button>
                </td>

                <td><a class="btn btn-sm btn-primary" href="{{ route('admin.movingDetail.edit', $movingDetail->id) }}"><i
                            class="fa fa-edit"></i></a></td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">No data found</td>
            </tr>
        @endforelse
    </tbody>
</table>


<div class="modal fade" id="movingDetailStatusModal" tabindex="-1" role="dialog"
    aria-labelledby="movingDetailStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="movingDetailStatusModalLabel">Update Status</h5>
            </div>
            <div class="modal-body">
                Are you sure you want to update status?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('movingDetailStatusModal')"
                    data-dismiss="modal">Close</button>
                <a id="updatemovingDetailStatusLink" href="#" class="btn btn-primary">Update</a>
            </div>
        </div>
    </div>
</div>


{{ $movingDetails->links() }}



<script>
    function showmovingDetailStatusDialog(id, status) {
        $('#movingDetailStatusModal').modal('show');
        var baseUrl = "{{ url('admin/moving-config/no-of-rooms/update-status') }}"; // Use `url()` to get the base part
        // $('#updatemovingDetailStatusLink').attr('href', baseUrl + '/' + id);

        // Remove previous click event handler from #updatemovingDetailStatusLink
        $('#updatemovingDetailStatusLink').off('click');
        // add onclick to updatemovingDetailStatusLink here
        $('#updatemovingDetailStatusLink').click(function() {
            updatemovingDetailStatus(id);
        });
        // console.log($('#statusmovingDetailButton_' + id).text());
        if ($('#statusmovingDetailButton_' + id).text().trim() == 'Active') {
            $('#updatemovingDetailStatusLink').text("Decactivate");
            $('#updatemovingDetailStatusLink').removeClass('btn-primary');
            $('#updatemovingDetailStatusLink').addClass('btn-danger');
        } else {
            $('#updatemovingDetailStatusLink').text("Activate");
            $('#updatemovingDetailStatusLink').removeClass('btn-danger');
            $('#updatemovingDetailStatusLink').addClass('btn-primary');
        }

    }

    function updatemovingDetailStatus(id) {
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
                    $('#movingDetailStatusModal').modal('hide');
                    // Change text on bbutton as per response
                    if (jsonResponse.is_active == 1) {
                        $('#statusmovingDetailButton_' + id).text('Inactive');
                        $('#statusmovingDetailButton_' + id).removeClass('btn-primary');
                        $('#statusmovingDetailButton_' + id).addClass('btn-danger');
                    }
                    if (jsonResponse.is_active == 0) {
                        $('#statusmovingDetailButton_' + id).text('Active');
                        $('#statusmovingDetailButton_' + id).removeClass('btn-danger');
                        $('#statusmovingDetailButton_' + id).addClass('btn-primary');
                    }

                    // Trigger Notification
                    triggerToast('Success', 'movingDetail Status updated succcessfully');
                    // Remove function from button
                    $('updatemovingDetailStatusLink').off('click');
                    console.log(jsonResponse.message); // Print the message from the response
                } else {
                    // Hide modal
                    $('#movingDetailStatusModal').modal('hide');
                    // Remove function from button
                    $('updatemovingDetailStatusLink').off('click');
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
