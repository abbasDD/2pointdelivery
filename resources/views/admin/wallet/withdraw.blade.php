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
                                        <p class="text-success"> +{{ $wallet->amount }}</p>
                                    @else
                                        <p class="text-danger"> -{{ $wallet->amount }}</p>
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
                                        <button onclick="openWithdrawModal({{ $wallet->id }})"
                                            class="btn btn-primary btn-sm">Withdraw</button>
                                    @endif

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

@endsection
