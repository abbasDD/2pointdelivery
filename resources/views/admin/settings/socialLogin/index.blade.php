<div class="container">
    <h5>Social Login Settings</h5>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.socialLoginSetting.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.settings.socialLogin.form')
            </form>
        </div>
    </div>
</div>
