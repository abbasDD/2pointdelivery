@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

    {{-- Withdraw Wallet List --}}
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Withdraw Requests</h5>
        </div>
        {{-- withdraw list --}}
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
                                    @if ($wallet->type == 'earned')
                                        <p class="text-success"> + C${{ $wallet->amount }}</p>
                                    @else
                                        <p class="text-danger"> - C${{ $wallet->amount }}</p>
                                    @endif
                                </td>
                                <td>{{ $wallet->payment_method }}</td>
                                <td>
                                    <span class="badge bg-{{ $wallet->status == 'pending' ? 'warning' : 'success' }}">
                                        {{ $wallet->status }}
                                    </span>
                                </td>
                                <td>
                                    @if ($wallet->status == 'pending')
                                        <button onclick="approveWithdrawModal({{ $wallet->id }})"
                                            class="btn btn-primary btn-sm">Approve</button>
                                        <button onclick="rejectWithdrawModal({{ $wallet->id }})"
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

    {{-- Approve Withdraw Modal --}}
    <div class="modal fade" id="approveWithdrawModal" tabindex="-1" role="dialog"
        aria-labelledby="approveWithdrawModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveWithdrawModalLabel">Approve Withdraw</h5>
                </div>
                <form action="{{ route('admin.wallet.withdraw.approve') }}" method="POST">
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

    {{-- Reject Withdraw Modal --}}
    <div class="modal fade" id="rejectWithdrawModal" tabindex="-1" role="dialog"
        aria-labelledby="rejectWithdrawModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectWithdrawModalLabel">Reject Withdraw</h5>
                </div>
                <form action="{{ route('admin.wallet.withdraw.reject') }}" method="POST">
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
        // approveWithdrawModal
        function approveWithdrawModal(id) {
            // Show Modal
            $('#approveWithdrawModal').modal('show');

            // wallet_id
            document.getElementById('approve_wallet_id').value = id;
        }

        // rejectWithdrawModal
        function rejectWithdrawModal(id) {
            // Show Modal
            $('#rejectWithdrawModal').modal('show');

            // wallet_id
            document.getElementById('reject_wallet_id').value = id;
        }
    </script>

@endsection
