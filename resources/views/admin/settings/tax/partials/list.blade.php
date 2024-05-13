<table class="table table-striped">
    <thead class="thead-primary">
        <tr>
            <th>ID</th>
            <th>Country</th>
            <th>State</th>
            {{-- <th>City</th> --}}
            <th>GST %</th>
            <th>PST %</th>
            <th>HST %</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($taxCountries as $taxCountry)
            <tr>
                <td>{{ $taxCountry->id }}</td>
                <td>{{ $taxCountry->country_name }}</td>
                <td>{{ $taxCountry->state_name }}</td>
                {{-- <td>{{ $taxCountry->city_name }}</td> --}}
                <td>{{ $taxCountry->gst_rate }}</td>
                <td>{{ $taxCountry->pst_rate }}</td>
                <td>{{ $taxCountry->hst_rate }}</td>
                <td>
                    <button type="button" id="statusTaxButton_{{ $taxCountry->id }}"
                        class="btn  {{ $taxCountry->is_active ? 'btn-primary' : 'btn-danger' }} btn-sm"
                        onclick="showStatusDialog({{ $taxCountry->id }}, '{{ $taxCountry->is_active }}')">
                        {{ $taxCountry->is_active == 1 ? 'Active' : 'Inactive' }}
                    </button>
                </td>

                <td><a class="btn btn-sm btn-primary" href="{{ route('admin.taxSetting.edit', $taxCountry->id) }}"><i
                            class="fa fa-edit"></i></a></td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">No data found</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- Modal --}}

<div class="modal fade" id="statusTaxModal" tabindex="-1" role="dialog" aria-labelledby="statusTaxModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusTaxModalLabel">Update Status</h5>
            </div>
            <div class="modal-body">
                Are you sure you want to update status?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('statusTaxModal')"
                    data-dismiss="modal">Close</button>
                <a id="updateTaxStatusLink" href="#" class="btn btn-primary">Update</a>
            </div>
        </div>
    </div>
</div>

{{ $taxCountries->links() }}


<script>
    function showStatusDialog(id, status) {
        $('#statusTaxModal').modal('show');
        var baseUrl = "{{ url('admin/settings/tax/update-status') }}"; // Use `url()` to get the base part
        // $('#updateTaxStatusLink').attr('href', baseUrl + '/' + id);

        // Remove previous click event handler from #updateTaxStatusLink
        $('#updateTaxStatusLink').off('click');
        // add onclick to updateTaxStatusLink here
        $('#updateTaxStatusLink').click(function() {
            updateStatus(id);
        });
        // console.log($('#statusTaxButton_' + id).text());
        if ($('#statusTaxButton_' + id).text().trim() == 'Active') {
            $('#updateTaxStatusLink').text("Decactivate");
            $('#updateTaxStatusLink').removeClass('btn-primary');
            $('#updateTaxStatusLink').addClass('btn-danger');
        } else {
            $('#updateTaxStatusLink').text("Activate");
            $('#updateTaxStatusLink').removeClass('btn-danger');
            $('#updateTaxStatusLink').addClass('btn-primary');
        }

    }

    function updateStatus(id) {
        console.log(id);
        var baseUrl = "{{ url('admin/settings/tax/update-status') }}";
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
                    $('#statusTaxModal').modal('hide');
                    // Change text on bbutton as per response
                    if (jsonResponse.is_active == 1) {
                        $('#statusTaxButton_' + id).text('Inactive');
                        $('#statusTaxButton_' + id).removeClass('btn-primary');
                        $('#statusTaxButton_' + id).addClass('btn-danger');
                    }
                    if (jsonResponse.is_active == 0) {
                        $('#statusTaxButton_' + id).text('Active');
                        $('#statusTaxButton_' + id).removeClass('btn-danger');
                        $('#statusTaxButton_' + id).addClass('btn-primary');
                    }

                    // Trigger Notification
                    triggerToast('Success', 'Tax Status updated succcessfully');
                    // Remove function from button
                    $('updateTaxStatusLink').off('click');
                    console.log(jsonResponse.message); // Print the message from the response
                } else {
                    // Hide modal
                    $('#statusTaxModal').modal('hide');
                    // Remove function from button
                    $('updateTaxStatusLink').off('click');
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
