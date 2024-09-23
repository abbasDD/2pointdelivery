{{-- Moving Details --}}
<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Weight</th>
                    <th>Volume</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($booking_moving_details as $movingDetail)
                    <tr>
                        <td>{{ $movingDetail->name }}</td>
                        <td>{{ $movingDetail->description }}</td>
                        <td>{{ $movingDetail->weight }}</td>
                        <td>{{ $movingDetail->volume }}</td>
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
