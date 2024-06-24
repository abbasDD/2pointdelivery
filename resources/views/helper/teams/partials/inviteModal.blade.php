{{-- Modal to Invite --}}
<div class="modal fade" id="inviteTeamModal" tabindex="-1" role="dialog" aria-labelledby="inviteTeamModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inviteTeamModalLabel">Invite User</h5>
            </div>
            <form action="{{ route('helper.team.invite') }}" method="POST">
                <div class="modal-body">
                    @csrf

                    <input id="searchInput" name="invitee_email" type="email" class="form-control"
                        placeholder="Write email address" required>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Invite</button>
                </div>
            </form>
        </div>
    </div>
</div>
