<table class="table table-striped">
    <thead class="thead-primary">
        <tr>
            <th>Booking Reference</th>
            <th>Review</th>
            <th>Rating</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($reviews as $review)
            <tr>
                <td>{{ $review->booking->uuid }}</td>
                <td>{{ $review->review }}</td>
                <td>
                    <i class="fas fa-star {{ $review->rating >= 1 ? 'text-warning' : '' }}"></i>
                    <i class="fas fa-star {{ $review->rating >= 2 ? 'text-warning' : '' }}"></i>
                    <i class="fas fa-star {{ $review->rating >= 3 ? 'text-warning' : '' }}"></i>
                    <i class="fas fa-star {{ $review->rating >= 4 ? 'text-warning' : '' }}"></i>
                    <i class="fas fa-star {{ $review->rating >= 5 ? 'text-warning' : '' }}"></i>
                </td>
                <td>
                    <button type="button" id="statusButton_{{ $review->id }}"
                        class="btn  {{ $review->is_approved ? 'btn-primary' : 'btn-danger' }} btn-sm"
                        onclick="showStatusDialog({{ $review->id }}, '{{ $review->is_approved }}')">
                        {{ $review->is_approved == 1 ? 'Active' : 'Inactive' }}
                    </button>
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="10" class="text-center">No data found</td>
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
                <p id="statusMessage">
                    Are you sure you want this review to be on Home Page?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('statusModal')"
                    data-dismiss="modal">Close</button>
                <a id="updateStatusLink" href="#" class="btn btn-primary">Update</a>
            </div>
        </div>
    </div>
</div>

{{ $reviews->links() }}



<script>
    function showStatusDialog(id, status) {
        $('#statusModal').modal('show');

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

        // Update the message
        $('#statusMessage').text(
            'Are you sure you want this review to be ' + $('#updateStatusLink').text().trim() + ' on Home Page?'
        );

    }

    function updateStatus(id) {
        console.log(id);
        var baseUrl = "{{ url('admin/review/update-status') }}";
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
                    if (jsonResponse.is_approved == 1) {
                        $('#statusButton_' + id).text('Inactive');
                        $('#statusButton_' + id).removeClass('btn-primary');
                        $('#statusButton_' + id).addClass('btn-danger');
                    }
                    if (jsonResponse.is_approved == 0) {
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
