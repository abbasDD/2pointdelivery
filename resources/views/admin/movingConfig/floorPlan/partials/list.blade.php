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
        @forelse ($floorPlans as $floorPlan)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $floorPlan->name }}</td>
                <td>${{ $floorPlan->price }}</td>
                <td>${{ $floorPlan->helper_fee }}</td>
                <td>{{ $floorPlan->description ?? '-' }}</td>
                <td>
                    <button type="button" id="statusfloorPlanButton_{{ $floorPlan->id }}"
                        class="btn  {{ $floorPlan->is_active ? 'btn-primary' : 'btn-danger' }} btn-sm"
                        onclick="showfloorPlanStatusDialog({{ $floorPlan->id }}, '{{ $floorPlan->is_active }}')">
                        {{ $floorPlan->is_active == 1 ? 'Active' : 'Inactive' }}
                    </button>
                </td>

                <td><a class="btn btn-sm btn-primary"
                        href="{{ route('admin.movingConfig.floorPlan.edit', $floorPlan->id) }}"><i
                            class="fa fa-edit"></i></a></td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">No data found</td>
            </tr>
        @endforelse
    </tbody>
</table>


<div class="modal fade" id="floorPlanStatusModal" tabindex="-1" role="dialog"
    aria-labelledby="floorPlanStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="floorPlanStatusModalLabel">Update Status</h5>
            </div>
            <div class="modal-body">
                Are you sure you want to update status?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('floorPlanStatusModal')"
                    data-dismiss="modal">Close</button>
                <a id="updatefloorPlanStatusLink" href="#" class="btn btn-primary">Update</a>
            </div>
        </div>
    </div>
</div>


{{ $floorPlans->links() }}



<script>
    function showfloorPlanStatusDialog(id, status) {
        $('#floorPlanStatusModal').modal('show');
        var baseUrl = "{{ url('admin/moving-config/no-of-rooms/update-status') }}"; // Use `url()` to get the base part
        // $('#updatefloorPlanStatusLink').attr('href', baseUrl + '/' + id);

        // Remove previous click event handler from #updatefloorPlanStatusLink
        $('#updatefloorPlanStatusLink').off('click');
        // add onclick to updatefloorPlanStatusLink here
        $('#updatefloorPlanStatusLink').click(function() {
            updatefloorPlanStatus(id);
        });
        // console.log($('#statusfloorPlanButton_' + id).text());
        if ($('#statusfloorPlanButton_' + id).text().trim() == 'Active') {
            $('#updatefloorPlanStatusLink').text("Decactivate");
            $('#updatefloorPlanStatusLink').removeClass('btn-primary');
            $('#updatefloorPlanStatusLink').addClass('btn-danger');
        } else {
            $('#updatefloorPlanStatusLink').text("Activate");
            $('#updatefloorPlanStatusLink').removeClass('btn-danger');
            $('#updatefloorPlanStatusLink').addClass('btn-primary');
        }

    }

    function updatefloorPlanStatus(id) {
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
                    $('#floorPlanStatusModal').modal('hide');
                    // Change text on bbutton as per response
                    if (jsonResponse.is_active == 1) {
                        $('#statusfloorPlanButton_' + id).text('Inactive');
                        $('#statusfloorPlanButton_' + id).removeClass('btn-primary');
                        $('#statusfloorPlanButton_' + id).addClass('btn-danger');
                    }
                    if (jsonResponse.is_active == 0) {
                        $('#statusfloorPlanButton_' + id).text('Active');
                        $('#statusfloorPlanButton_' + id).removeClass('btn-danger');
                        $('#statusfloorPlanButton_' + id).addClass('btn-primary');
                    }

                    // Trigger Notification
                    triggerToast('Success', 'floorPlan Status updated succcessfully');
                    // Remove function from button
                    $('updatefloorPlanStatusLink').off('click');
                    console.log(jsonResponse.message); // Print the message from the response
                } else {
                    // Hide modal
                    $('#floorPlanStatusModal').modal('hide');
                    // Remove function from button
                    $('updatefloorPlanStatusLink').off('click');
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
