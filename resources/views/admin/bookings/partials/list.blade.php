<table class="table table-striped">
    <thead class="thead-primary">
        <tr>
            <th>Order#</th>
            <th>Date Time</th>
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
                <td>{{ $booking->uuid }}</td>
                <td>
                    <p>{{ app('dateHelper')->formatTimestamp($booking->created_at, 'Y-m-d') }} </p>
                    <p>{{ $booking->booking_time }}</p>
                </td>
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
                <td>
                    <p>${{ $booking->total_price }}</p>
                    <p>
                        <span class="fw-bold">Tax:</span>
                        @if ($booking->booking_type == 'delivery' && $booking->delivery != null)
                            ${{ $booking->delivery->tax_price }}
                        @else
                            $0
                        @endif
                    </p>
                </td>

                <td>
                    <p class="badge {{ $booking->status == 'completed' ? 'bg-primary' : 'bg-warning' }}">
                        {{ $booking->status }}
                    </p>
                </td>
                <td>
                    <a href="{{ route('admin.booking.show', $booking->id) }}" class="btn btn-sm btn-primary"
                        data-toggle="tooltip" data-placement="top" title="View Booking">
                        <i class="fas fa-eye"></i>
                    </a>
                    @if ($booking->status == 'pending')
                        <a href="{{ route('admin.booking.cancel', $booking->id) }}" class="btn btn-sm btn-danger"
                            data-toggle="tooltip" data-placement="top" title="Cancel Booking">
                            <i class="fas fa-close"></i>
                        </a>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center">No data found</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $bookings->links() }}



{{-- Cancel Booking Modal --}}
