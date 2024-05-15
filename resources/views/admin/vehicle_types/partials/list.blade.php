<table class="table table-striped">
    <thead class="thead-primary">
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Vehice Name</th>
            <th>Description</th>
            <th>Service Availble</th>
            <th>Vehicle Price</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($vehicle_types as $vehicle_type)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td><img src="{{ $vehicle_type->image ? asset('images/vehicle_types/' . $vehicle_type->image) : asset('images/vehicle_types/default.png') }}"
                        alt="" width="50px" height="50px"></td>
                <td>{{ $vehicle_type->name }}</td>
                <td>{{ $vehicle_type->description }}</td>
                <td>
                    @if ($vehicle_type->service_types->isNotEmpty())
                        {{ implode(', ', $vehicle_type->service_types->pluck('name')->toArray()) }}
                    @else
                        <span class="badge badge-danger">No service available</span>
                    @endif
                </td>
                <td>{{ $vehicle_type->price . '$ per ' . $vehicle_type->price_type }}</td>
                <td>
                    <button type="button" id="statusButton_{{ $vehicle_type->id }}"
                        class="btn  {{ $vehicle_type->is_active ? 'btn-primary' : 'btn-danger' }} btn-sm"
                        onclick="showStatusDialog({{ $vehicle_type->id }}, '{{ $vehicle_type->is_active }}')">
                        {{ $vehicle_type->is_active == 1 ? 'Active' : 'Inactive' }}
                    </button>
                </td>
                <td><a href="{{ route('admin.vehicleType.edit', $vehicle_type->id) }}"
                        class="btn btn-sm btn-primary"><i class="fas fa-pen"></i></a></td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">No data found</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $vehicle_types->links() }}



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
                <button type="button" class="btn btn-secondary" onclick="closeModal('statusModal')"
                    data-dismiss="modal">Close</button>
                <a id="updateStatusLink" href="#" class="btn btn-primary">Update</a>
            </div>
        </div>
    </div>
</div>



<script>
    function showStatusDialog(id, status) {
        $('#statusModal').modal('show');
        var baseUrl = "{{ url('admin/vehicle-type/update-status') }}"; // Use `url()` to get the base part
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
        var baseUrl = "{{ url('admin/vehicle-type/update-status') }}";
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
                triggerToast('Error', 'Something went wrong');
                console.error(error); // Log the error for debugging
                // Show an error message to the user
            }
        });

    }
</script>
