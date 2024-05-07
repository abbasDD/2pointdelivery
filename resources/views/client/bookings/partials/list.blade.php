<table class="table table-striped">
    <thead class="thead-primary">
        <tr>
            <th>ID</th>
            <th>Priority</th>
            <th>Pickup Address</th>
            <th>Dropoff Address</th>
            <th>Type</th>
            <th>Price</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($bookings as $booking)
            <tr>
                <td>{{ $booking->id }}</td>
                <td>{{ $booking->prioritySetting->name }}</td>
                <td>{{ $booking->pickup_address }}</td>
                <td>{{ $booking->dropoff_address }}</td>
                <td>{{ $booking->serviceType->name }}</td>
                <td>{{ $booking->total_price }}</td>
                <td>{{ $booking->status }}</td>
                <td><a href="{{ route('client.booking.show', $booking->id) }}" class="btn btn-sm btn-primary"><i
                            class="fas fa-eye"></i></a></td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center">No data found</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- {{ $bookings->links() }} --}}
