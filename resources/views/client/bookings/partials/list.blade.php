<table class="table table-striped">
    <thead class="thead-primary">
        <tr>
            <th>Reference</th>
            <th>Date Time</th>
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
                    @if ($booking->status == 'draft' || $booking->status == 'pending')
                        <p class="badge bg-warning">{{ $booking->status }}</p>
                    @elseif ($booking->status == 'accepted' || $booking->status == 'in_transit' || $booking->status == 'completed')
                        <p class="badge bg-success">{{ $booking->status }}</p>
                    @else
                        <p class="badge bg-danger">{{ $booking->status }}</p>
                    @endif
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

                    {{-- Cancel Booking --}}
                    @if ($booking->status == 'pending')
                        <a href="{{ route('client.booking.cancel', $booking->id) }}" class="btn btn-sm btn-danger"
                            data-toggle="tooltip" data-placement="top" title="Cancel Booking">
                            <i class="fas fa-close"></i>
                        </a>
                    @endif

                    {{-- Refund Request if cancelled and payment method is not cod --}}
                    @if ($booking->status == 'cancelled' && $booking->payment_method != 'cod' && $booking->refunded == 0)
                        <a href="{{ route('client.wallet.refund.request', $booking->id) }}"
                            class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top"
                            title="Refund Request">
                            <i class="fas fa-undo"></i>
                        </a>
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
