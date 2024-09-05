<table class="table table-striped">
    <thead class="thead-primary">
        <tr>
            <th>Reference</th>
            <th>Date Time</th>
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
                    <p>{{ date(config('date_format') ?: 'Y-m-d', strtotime($booking->booking_date)) }}</p>
                    <p>{{ date(config('time_format') ?: 'H:i A', strtotime($booking->booking_time)) }}</p>
                </td>
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
                <td>${{ $booking->payment->helper_fee ?? 0 }}</td>
                <td>
                    @if ($booking->status == 'draft' || $booking->status == 'pending')
                        <p class="badge bg-warning">{{ $booking->status }}</p>
                    @elseif ($booking->status == 'accepted' || $booking->status == 'in_transit' || $booking->status == 'completed')
                        <p class="badge bg-success">{{ $booking->status }}</p>
                    @else
                        <p class="badge bg-danger">{{ $booking->status }}</p>
                    @endif
                </td>
                @if ($booking->status == 'pending')
                    {{-- CHeck if user already accepted this booking --}}
                    @if ($booking->helper_user_id == auth()->user()->id)
                        <td><a href="{{ route('helper.booking.show', $booking->id) }}" class="btn btn-sm btn-primary">
                                View
                        </td>
                    @else
                        <td>
                            <a class="btn btn-sm btn-primary"
                                href="{{ route('helper.booking.accept', $booking->id) }}">Accept</a>
                        </td>
                    @endif
                @else
                    <td><a href="{{ route('helper.booking.show', $booking->id) }}" class="btn btn-sm btn-primary">
                            View
                    </td>
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
