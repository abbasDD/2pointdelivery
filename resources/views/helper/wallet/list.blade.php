{{-- List --}}
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Wallet History</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($wallets as $wallet)
                        <tr>
                            <td>{{ $wallet->created_at->format('Y-m-d') }}</td>
                            <td>{{ $wallet->amount }}</td>
                            <td>{{ $wallet->status }}</td>
                            <td>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No data found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>
