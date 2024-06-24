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
        @forelse ($invitations as $invitation)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $invitation->invitee_email }}</td>
                <td>Permissions</td>
                <td>
                    <p>{{ app('dateHelper')->formatTimestamp($invitation->created_at, 'Y-m-d') }} </p>
                </td>
                <td>
                    @if ($invitation->status == 'pending')
                        <span class="badge bg-warning">Pending</span>
                    @elseif($invitation->status == 'accepted')
                        <span class="badge bg-success">Accepted</span>
                    @else
                        <span class="badge bg-danger">Declined</span>
                    @endif
                </td>
                <td>
                    @if ($invitation->status == 'pending')
                        <a class="btn btn-sm btn-success" href="{{ route('client.invitation.accept', $invitation->id) }}">
                            Accept
                        </a>
                        <a class="btn btn-sm btn-danger" href="{{ route('client.invitation.decline', $invitation->id) }}">
                            Decline
                        </a>
                    @else
                        <a class="btn btn-sm btn-primary"
                            href="{{ route('client.team.switchUser', $invitation->inviter_id) }}">
                            Switch User
                        </a>
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

{{-- {{ $invitations->links() }} --}}
