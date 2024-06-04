<table class="table table-striped">
    <thead class="thead-primary">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($kycTypes as $kycType)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $kycType->name }}</td>
                <td>
                    <button type="button" id="statusButton_{{ $kycType->id }}"
                        class="btn  {{ $kycType->is_active ? 'btn-primary' : 'btn-danger' }} btn-sm"
                        onclick="showStatusDialog({{ $kycType->id }}, '{{ $kycType->is_active }}')">
                        {{ $kycType->is_active == 1 ? 'Active' : 'Inactive' }}
                    </button>
                </td>
                <td>
                    <a href="{{ route('admin.kycType.edit', $kycType->id) }}" class="btn btn-sm btn-primary"><i
                            class="fa-solid fa-pen"></i></a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="11" class="text-center">No data found</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- {{ $kycTypes->links() }} --}}



<script>
    function showStatusDialog(id, status) {
        $('#statusModal').modal('show');
        var baseUrl = "{{ url('admin/kyc-type/update-status') }}"; // Use `url()` to get the base part
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
        var baseUrl = "{{ url('admin/kyc-type/update-status') }}";
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
