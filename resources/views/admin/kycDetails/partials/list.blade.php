<table class="table table-striped">
    <thead class="thead-primary">
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>User Type</th>
            <th>ID Type</th>
            <th>ID Number</th>
            <th>City</th>
            <th>State</th>
            <th>Country</th>
            <th>Issue Date</th>
            <th>Expiry Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($kycDetails as $kycDetail)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $kycDetail->user_email }}</td>
                <td>{{ $kycDetail->type }}</td>
                <td>{{ $kycDetail->id_type }}</td>
                <td>{{ $kycDetail->id_number }}</td>
                <td>{{ app('addressHelper')->getCityName($kycDetail->city) }}</td>
                <td>{{ app('addressHelper')->getStateName($kycDetail->state) }}</td>
                <td>{{ app('addressHelper')->getCountryName($kycDetail->country) }}</td>
                <td>{{ $kycDetail->issue_date }}</td>
                <td>{{ $kycDetail->expiry_date }}</td>
                <td>
                    <a href="{{ route('admin.kycDetail.approve', $kycDetail->id) }}" class="btn btn-sm btn-primary"><i
                            class="fa-solid fa-check"></i></a>
                    <a href="{{ route('admin.kycDetail.reject', $kycDetail->id) }}" class="btn btn-sm btn-danger"><i
                            class="fa-solid fa-xmark"></i></a>
                    <a href="{{ route('admin.kycDetail.show', $kycDetail->id) }}" class="btn btn-sm btn-primary"><i
                            class="fa-solid fa-eye"></i></a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="11" class="text-center">No data found</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- {{ $kycDetails->links() }} --}}
