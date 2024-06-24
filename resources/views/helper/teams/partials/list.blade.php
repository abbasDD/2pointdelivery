<table class="table table-striped">
    <thead class="thead-primary">
        <tr>
            <th>Sr No</th>
            <th>User Email</th>
            <th>Permissions</th>
            <th>Invite Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($acceptedInvites as $acceptedInvite)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $acceptedInvite->invitee_email }}</td>
                <td>Permissions</td>
                <td>
                    <p>{{ app('dateHelper')->formatTimestamp($acceptedInvite->created_at, 'Y-m-d') }} </p>
                </td>
                <td>
                    @if ($acceptedInvite->status == 'pending')
                        <span class="badge bg-warning">Pending</span>
                    @elseif($acceptedInvite->status == 'accepted')
                        <span class="badge bg-success">Accepted</span>
                    @else
                        <span class="badge bg-danger">Rejected</span>
                    @endif
                </td>
                <td>
                    <a class="btn btn-sm btn-danger" href="{{ route('helper.team.remove', $acceptedInvite->id) }}"><i
                            class="fas fa-trash"></i></a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">No data found</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- {{ $acceptedInvites->links() }} --}}
