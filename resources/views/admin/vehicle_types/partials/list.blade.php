<table class="table table-striped">
    <thead class="thead-primary">
        <tr>
            <th>ID</th>
            <th>Vehice Name</th>
            <th>Description</th>
            <th>Service Availble</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($vehicle_types as $vehicle_type)
            <tr>
                <td>{{ $vehicle_type->id }}</td>
                <td>{{ $vehicle_type->name }}</td>
                <td>{{ $vehicle_type->description }}</td>
                <td>
                    @if ($vehicle_type->service_types->isNotEmpty())
                        {{ implode(', ', $vehicle_type->service_types->pluck('name')->toArray()) }}
                    @else
                        <span class="badge badge-danger">No service available</span>
                    @endif
                </td>
                <td><a href="{{ route('admin.vehicleType.edit', $vehicle_type->id) }}" class="btn btn-sm btn-primary"><i
                            class="fas fa-pen"></i></a></td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">No data found</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $vehicle_types->links() }}
