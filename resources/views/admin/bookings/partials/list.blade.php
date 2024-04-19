<table class="table table-striped">
    <thead class="thead-primary">
        <tr>
            <th>ID</th>
            <th>Priority</th>
            <th>Receiver Name</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($bookings as $booking)
            <tr>
                <td>{{ $booking->id }}</td>
                <td>{{ $booking->priority }}</td>
                <td>{{ $booking->receiver_name }}</td>
                <td>{{ $booking->status }}</td>
                <td><a href="{{ route('admin.booking.show', $booking->id) }}" class="btn btn-sm btn-primary"><i
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
