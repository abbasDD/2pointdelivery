<div class="container">
    <h5>Authentication Settings</h5>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.authenticationSetting.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.settings.authentication.form')
            </form>
        </div>
    </div>
</div>
