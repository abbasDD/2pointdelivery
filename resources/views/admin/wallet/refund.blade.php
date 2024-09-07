@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

    {{-- Refund Wallet List --}}
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Refund Requests</h5>
        </div>
        {{-- List --}}

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Booking Reference</th>
                            <th scope="col">Type</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Payment Method</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($wallets as $wallet)
                            <tr>
                                <td>{{ $wallet->created_at->format(config('date_format') ?: 'Y-m-d') }}</td>
                                <td>
                                    <p>{{ $wallet->booking_id ? \App\Models\Booking::find($wallet->booking_id)->uuid : '-' }}
                                    </p>
                                    <p>{{ $wallet->transaction_id ? $wallet->transaction_id : 'wallet' }}</p>
                                </td>
                                <td>{{ $wallet->type }}</td>
                                <td>
                                    @if ($wallet->type == 'spend' || $wallet->type == 'earned')
                                        <p class="text-success"> + C${{ $wallet->amount }}</p>
                                    @else
                                        <p class="text-danger"> - C${{ $wallet->amount }}</p>
                                    @endif
                                </td>
                                <td>{{ $wallet->payment_method ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $wallet->status == 'pending' ? 'warning' : 'success' }}">
                                        {{ $wallet->status }}
                                    </span>
                                </td>


                                <td>
                                    @if ($wallet->status == 'pending')
                                        <button onclick="approveRefundModal({{ $wallet->id }})"
                                            class="btn btn-primary btn-sm">Approve</button>
                                        <button onclick="rejectRefundModal({{ $wallet->id }})"
                                            class="btn btn-danger btn-sm">Reject</button>
                                    @else
                                        <p class="badge {{ $wallet->status == 'success' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $wallet->status == 'success' ? 'Approved' : 'Rejected' }}</p>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No data found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>

    </div>

    {{-- Approve Refund Modal --}}
    <div class="modal fade" id="approveRefundModal" tabindex="-1" role="dialog" aria-labelledby="approveRefundModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveRefundModalLabel">Approve Refund</h5>
                </div>
                <form action="{{ route('admin.wallet.refund.approve') }}" method="POST">
                    <div class="modal-body">

                        @csrf
                        <input type="hidden" id="approve_wallet_id" name="wallet_id" value="">

                        {{-- Transaction ID --}}
                        <div class="form-group">
                            <label for="transaction_id">Transaction ID</label>
                            <input type="text" class="form-control" id="transaction_id" name="transaction_id"
                                placeholder="Enter Transaction ID" required>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Approve</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Reject Refund Modal --}}
    <div class="modal fade" id="rejectRefundModal" tabindex="-1" role="dialog" aria-labelledby="rejectRefundModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectRefundModalLabel">Reject Refund</h5>
                </div>
                <form action="{{ route('admin.wallet.refund.reject') }}" method="POST">
                    <div class="modal-body">

                        @csrf
                        <input type="hidden" id="reject_wallet_id" name="wallet_id" value="">

                        {{-- Reason --}}
                        <div class="form-group">
                            <label for="reason">Reason</label>
                            <textarea class="form-control" id="reason" name="reason" placeholder="Enter Reason" required></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function approveRefundModal(id) {
            $('#approve_wallet_id').val(id);
            $('#approveRefundModal').modal('show');
        }

        function rejectRefundModal(id) {
            $('#reject_wallet_id').val(id);
            $('#rejectRefundModal').modal('show');
        }
    </script>

@endsection
