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
        @forelse ($noOfRooms as $noOfRoom)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $noOfRoom->name }}</td>
                <td>${{ $noOfRoom->price }}</td>
                <td>${{ $noOfRoom->helper_fee }}</td>
                <td>{{ $noOfRoom->description ?? '-' }}</td>
                <td>
                    <button type="button" id="statusnoOfRoomButton_{{ $noOfRoom->id }}"
                        class="btn  {{ $noOfRoom->is_active ? 'btn-primary' : 'btn-danger' }} btn-sm"
                        onclick="shownoOfRoomStatusDialog({{ $noOfRoom->id }}, '{{ $noOfRoom->is_active }}')">
                        {{ $noOfRoom->is_active == 1 ? 'Active' : 'Inactive' }}
                    </button>
                </td>

                <td><a class="btn btn-sm btn-primary"
                        href="{{ route('admin.movingConfig.noOfRooms.edit', $noOfRoom->id) }}"><i
                            class="fa fa-edit"></i></a></td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">No data found</td>
            </tr>
        @endforelse
    </tbody>
</table>


<div class="modal fade" id="noOfRoomStatusModal" tabindex="-1" role="dialog"
    aria-labelledby="noOfRoomStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="noOfRoomStatusModalLabel">Update Status</h5>
            </div>
            <div class="modal-body">
                Are you sure you want to update status?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('noOfRoomStatusModal')"
                    data-dismiss="modal">Close</button>
                <a id="updatenoOfRoomStatusLink" href="#" class="btn btn-primary">Update</a>
            </div>
        </div>
    </div>
</div>


{{ $noOfRooms->links() }}



<script>
    function shownoOfRoomStatusDialog(id, status) {
        $('#noOfRoomStatusModal').modal('show');
        var baseUrl = "{{ url('admin/moving-config/no-of-rooms/update-status') }}"; // Use `url()` to get the base part
        // $('#updatenoOfRoomStatusLink').attr('href', baseUrl + '/' + id);

        // Remove previous click event handler from #updatenoOfRoomStatusLink
        $('#updatenoOfRoomStatusLink').off('click');
        // add onclick to updatenoOfRoomStatusLink here
        $('#updatenoOfRoomStatusLink').click(function() {
            updatenoOfRoomStatus(id);
        });
        // console.log($('#statusnoOfRoomButton_' + id).text());
        if ($('#statusnoOfRoomButton_' + id).text().trim() == 'Active') {
            $('#updatenoOfRoomStatusLink').text("Decactivate");
            $('#updatenoOfRoomStatusLink').removeClass('btn-primary');
            $('#updatenoOfRoomStatusLink').addClass('btn-danger');
        } else {
            $('#updatenoOfRoomStatusLink').text("Activate");
            $('#updatenoOfRoomStatusLink').removeClass('btn-danger');
            $('#updatenoOfRoomStatusLink').addClass('btn-primary');
        }

    }

    function updatenoOfRoomStatus(id) {
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
                    $('#noOfRoomStatusModal').modal('hide');
                    // Change text on bbutton as per response
                    if (jsonResponse.is_active == 1) {
                        $('#statusnoOfRoomButton_' + id).text('Inactive');
                        $('#statusnoOfRoomButton_' + id).removeClass('btn-primary');
                        $('#statusnoOfRoomButton_' + id).addClass('btn-danger');
                    }
                    if (jsonResponse.is_active == 0) {
                        $('#statusnoOfRoomButton_' + id).text('Active');
                        $('#statusnoOfRoomButton_' + id).removeClass('btn-danger');
                        $('#statusnoOfRoomButton_' + id).addClass('btn-primary');
                    }

                    // Trigger Notification
                    triggerToast('Success', 'noOfRoom Status updated succcessfully');
                    // Remove function from button
                    $('updatenoOfRoomStatusLink').off('click');
                    console.log(jsonResponse.message); // Print the message from the response
                } else {
                    // Hide modal
                    $('#noOfRoomStatusModal').modal('hide');
                    // Remove function from button
                    $('updatenoOfRoomStatusLink').off('click');
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
