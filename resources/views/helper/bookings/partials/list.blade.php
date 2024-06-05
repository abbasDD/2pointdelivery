<table class="table table-striped">
    <thead class="thead-primary">
        <tr>
            <th>Order#</th>
            <th>Date Time</th>
            <th>Priority</th>
            <th>Service Type</th>
            <th>Address</th>
            <th>Fee</th>
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
                <td>${{ $booking->payment->helper_fee }}</td>
                <td>
                    <p class="badge {{ $booking->status == 'completed' ? 'bg-primary' : 'bg-danger' }}">
                        {{ $booking->status }}
                    </p>
                </td>
                @if ($booking->status == 'pending')
                    <td>
                        <a class="btn btn-sm btn-primary"
                            href="{{ route('helper.booking.accept', $booking->id) }}">Accept</a>
                    </td>
                @else
                    <td><a href="{{ route('helper.booking.show', $booking->id) }}" class="btn btn-sm btn-primary"><i
                                class="fas fa-eye"></i></a></td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">No data found</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- {{ $bookings->links() }} --}}
