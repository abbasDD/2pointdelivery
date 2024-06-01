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
                    <p class="badge {{ $booking->status == 'completed' ? 'bg-primary' : 'bg-danger' }}">
                        {{ $booking->status }}
                    </p>
                </td>
                <td>
                    {{-- If booking status is draft then ask client to payment --}}
                    @if ($booking->status == 'draft')
                        <a href="{{ route('client.booking.payment', $booking->id) }}" class="btn btn-sm btn-primary"
                            data-toggle="tooltip" data-placement="top" title="Pay Now"><i
                                class="fas fa-credit-card"></i></a>
                    @else
                        {{-- Else show view button --}}
                        <a href="{{ route('client.booking.show', $booking->id) }}" class="btn btn-sm btn-primary"
                            data-toggle="tooltip" data-placement="top" title="View Booking"><i
                                class="fas fa-eye"></i></a>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">No data found</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- {{ $bookings->links() }} --}}
