<table class="table table-striped">
    <thead class="thead-primary">
        <tr>
            <th>ID</th>
            <th>Service Name</th>
            <th>Category Name</th>
            <th>Base Price</th>
            <th>Price Per KM</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($service_categories as $service_category)
            <tr>
                <td>{{ $service_category->id }}</td>
                <td>{{ $service_category->serviceType->name }}</td>
                <td>{{ $service_category->name }}</td>
                <td>{{ $service_category->base_price }}</td>
                <td>{{ $service_category->price_per_km }}</td>
                <td>
                    <button type="button"
                        class="btn  {{ $service_category->is_active ? 'btn-primary' : 'btn-danger' }} btn-sm"
                        onclick="updateStatus({{ $service_category->id }})">
                        {{ $service_category->is_active == 1 ? 'Active' : 'Inactive' }}
                    </button>


                </td>
                <td><a href="{{ route('admin.serviceCategory.edit', $service_category->id) }}"><i
                            class="fa fa-edit"></i></a></td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">No data found</td>
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

{{ $service_categories->links() }}


<script>
    function updateStatus(id) {
        $('#statusModal').modal('show');
        var baseUrl = "{{ url('admin/service-category/update-status') }}"; // Use `url()` to get the base part
        $('#updateStatusLink').attr('href', baseUrl + '/' + id);
        $('#updateStatusLink').innerHTML = "Active";
    }
</script>
