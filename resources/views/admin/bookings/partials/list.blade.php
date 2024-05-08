<table class="table table-striped">
    <thead class="thead-primary">
        <tr>
            <th>ID</th>
            <th>Client</th>
            <th>Priority</th>
            <th>Service Type</th>
            <th>Address</th>
            <th>Price</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($bookings as $booking)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $booking->client->first_name }}</td>
                <td>{{ $booking->prioritySetting->name }}</td>
                <td>
                    {{-- Service Type --}}
                    <p>{{ $booking->serviceType->name }}</p>
                    {{-- Service Category --}}
                    <p>{{ $booking->serviceCategory->name }}</p>
                </td>
                <td>
                    {{-- Pickup Address --}}
                    <p>Pickup: {{ $booking->pickup_address }}</p>
                    {{-- Dropoff Address --}}
                    <p>Dropoff: {{ $booking->dropoff_address }}</p>
                </td>
                <td>{{ $booking->total_price }}</td>
                <td>
                    <p class="badge {{ $booking->status == 'completed' ? 'bg-primary' : 'bg-danger' }}">
                        {{ $booking->status }}
                    </p>
                </td>
                <td><a href="{{ route('admin.booking.show', 1) }}" class="btn btn-sm btn-primary"><i
                            class="fas fa-eye"></i></a></td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center">No data found</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $bookings->links() }}
