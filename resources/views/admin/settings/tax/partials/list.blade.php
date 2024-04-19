<table class="table table-striped">
    <thead class="thead-primary">
        <tr>
            <th>ID</th>
            <th>Country</th>
            <th>State</th>
            <th>Tax Type</th>
            <th>Tax Rate</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($taxCountries as $taxCountry)
            <tr>
                <td>{{ $taxCountry->id }}</td>
                <td>{{ $taxCountry->country }}</td>
                <td>{{ $taxCountry->state }}</td>
                <td>{{ $taxCountry->tax_type }}</td>
                <td>{{ $taxCountry->tax_rate }}</td>
                <td>
                    <button type="button" class="btn {{ $taxCountry->is_active ? 'btn-primary' : 'btn-danger' }} btn-sm"
                        onclick="updateStatus({{ $taxCountry->id }})">
                        {{ $taxCountry->is_active == 1 ? 'Active' : 'Inactive' }}
                    </button>
                </td>

                <td><a class="btn btn-sm btn-primary" href="{{ route('admin.taxSetting.edit', $taxCountry->id) }}"><i
                            class="fa fa-edit"></i></a></td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center">No data found</td>
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
                Are you sure you want to update status?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a id="updateStatusLink" href="#" class="btn btn-primary">Update</a>
            </div>
        </div>
    </div>
</div>

{{ $taxCountries->links() }}


<script>
    function updateStatus(id) {
        $('#statusModal').modal('show');
        var baseUrl = "{{ url('admin/settings/tax/update-status') }}"; // Use `url()` to get the base part
        $('#updateStatusLink').attr('href', baseUrl + '/' + id);
        $('#updateStatusLink').innerHTML = "Active";
    }
</script>
