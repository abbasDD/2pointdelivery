<table class="table table-striped">
    <thead class="thead-primary">
        <tr>
            <th>ID</th>
            <th>Address</th>
            <th>Receiver Name</th>
            <th>Receiver Phone</th>
            <th>Receiver Email</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($addressBooks as $addressBook)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>
                    <p><span class="fw-bold">Pickup: </span> {{ $addressBook->pickup_address }} </p>
                    <p><span class="fw-bold">Dropoff: </span> {{ $addressBook->dropoff_address }} </p>
                </td>
                <td>{{ $addressBook->receiver_name }}</td>
                <td>{{ $addressBook->receiver_phone }}</td>
                <td>{{ $addressBook->receiver_email }}</td>
                <td>
                    {{-- <a href="#" class="btn btn-sm btn-primary"><i class="fas fa-pencil"></i></a> --}}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">No data found</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- {{ $addressBooks->links() }} --}}
