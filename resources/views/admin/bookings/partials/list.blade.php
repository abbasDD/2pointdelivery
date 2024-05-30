<table class="table table-striped">
    <thead class="thead-primary">
        <tr>
            <th>Order#</th>
            <th>Date</th>
            <th>Time</th>
            <th>Priority</th>
            <th>Service Type</th>
            <th>Address</th>
            <th>Price</th>
            <th>Tax</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($bookings as $booking)
            <tr>
                <td>{{ $booking->uuid }}</td>
                <td>{{ app('dateHelper')->formatTimestamp($booking->created_at, 'Y-m-d') }}
                </td>
                <td>{{ $booking->booking_time }}</td>
                <td>{{ $booking->prioritySetting->name }}</td>
                <td>
                    {{-- Service Type --}}
                    <p>{{ $booking->serviceType->name }}</p>
                    {{-- Service Category --}}
                    <p>{{ $booking->serviceCategory->name }}</p>
                </td>
                <td>
                    {{-- Pickup Address --}}
                    <p><span class="fw-bold">Pickup:</span> {{ $booking->pickup_address }}</p>
                    {{-- Dropoff Address --}}
                    <p><span class="fw-bold">Dropoff:</span> {{ $booking->dropoff_address }}</p>
                </td>
                <td>{{ $booking->total_price }}</td>

                @if ($booking->booking_type == 'delivery')
                    <td>{{ $booking->delivery->tax_price }}</td>
                @else
                    <td>0</td>
                @endif

                <td>
                    <p class="badge {{ $booking->status == 'completed' ? 'bg-primary' : 'bg-danger' }}">
                        {{ $booking->status }}
                    </p>
                </td>
                <td><a href="{{ route('admin.booking.show', $booking->id) }}" class="btn btn-sm btn-primary"><i
                            class="fas fa-eye"></i></a></td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center">No data found</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $bookings->links() }}
