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
        @forelse ($floorAssess as $item)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $item->name }}</td>
                <td>${{ $item->price }}</td>
                <td>${{ $item->helper_fee }}</td>
                <td>{{ $item->description ?? '-' }}</td>
                <td>
                    <button type="button" id="statusfloorAssessButton_{{ $item->id }}"
                        class="btn  {{ $item->is_active ? 'btn-primary' : 'btn-danger' }} btn-sm"
                        onclick="showfloorAssessStatusDialog({{ $item->id }}, '{{ $item->is_active }}')">
                        {{ $item->is_active == 1 ? 'Active' : 'Inactive' }}
                    </button>
                </td>

                <td><a class="btn btn-sm btn-primary"
                        href="{{ route('admin.movingConfig.floorAssess.edit', $item->id) }}"><i
                            class="fa fa-edit"></i></a></td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">No data found</td>
            </tr>
        @endforelse
    </tbody>
</table>


<div class="modal fade" id="floorAssessStatusModal" tabindex="-1" role="dialog"
    aria-labelledby="floorAssessStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="floorAssessStatusModalLabel">Update Status</h5>
            </div>
            <div class="modal-body">
                Are you sure you want to update status?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('floorAssessStatusModal')"
                    data-dismiss="modal">Close</button>
                <a id="updatefloorAssessStatusLink" href="#" class="btn btn-primary">Update</a>
            </div>
        </div>
    </div>
</div>


{{ $floorAssess->links() }}



<script>
    function showfloorAssessStatusDialog(id, status) {
        $('#floorAssessStatusModal').modal('show');
        var baseUrl = "{{ url('admin/moving-config/no-of-rooms/update-status') }}"; // Use `url()` to get the base part
        // $('#updatefloorAssessStatusLink').attr('href', baseUrl + '/' + id);

        // Remove previous click event handler from #updatefloorAssessStatusLink
        $('#updatefloorAssessStatusLink').off('click');
        // add onclick to updatefloorAssessStatusLink here
        $('#updatefloorAssessStatusLink').click(function() {
            updatefloorAssessStatus(id);
        });
        // console.log($('#statusfloorAssessButton_' + id).text());
        if ($('#statusfloorAssessButton_' + id).text().trim() == 'Active') {
            $('#updatefloorAssessStatusLink').text("Decactivate");
            $('#updatefloorAssessStatusLink').removeClass('btn-primary');
            $('#updatefloorAssessStatusLink').addClass('btn-danger');
        } else {
            $('#updatefloorAssessStatusLink').text("Activate");
            $('#updatefloorAssessStatusLink').removeClass('btn-danger');
            $('#updatefloorAssessStatusLink').addClass('btn-primary');
        }

    }

    function updatefloorAssessStatus(id) {
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
                    $('#floorAssessStatusModal').modal('hide');
                    // Change text on bbutton as per response
                    if (jsonResponse.is_active == 1) {
                        $('#statusfloorAssessButton_' + id).text('Inactive');
                        $('#statusfloorAssessButton_' + id).removeClass('btn-primary');
                        $('#statusfloorAssessButton_' + id).addClass('btn-danger');
                    }
                    if (jsonResponse.is_active == 0) {
                        $('#statusfloorAssessButton_' + id).text('Active');
                        $('#statusfloorAssessButton_' + id).removeClass('btn-danger');
                        $('#statusfloorAssessButton_' + id).addClass('btn-primary');
                    }

                    // Trigger Notification
                    triggerToast('Success', 'floorAssess Status updated succcessfully');
                    // Remove function from button
                    $('updatefloorAssessStatusLink').off('click');
                    console.log(jsonResponse.message); // Print the message from the response
                } else {
                    // Hide modal
                    $('#floorAssessStatusModal').modal('hide');
                    // Remove function from button
                    $('updatefloorAssessStatusLink').off('click');
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
