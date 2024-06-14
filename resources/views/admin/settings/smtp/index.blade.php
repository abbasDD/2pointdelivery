<div class="container">
    <h5>Social Login Settings</h5>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.smtpSetting.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.settings.smtp.form')
            </form>
        </div>
    </div>
</div>
