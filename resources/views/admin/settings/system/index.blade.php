<div class="container">
    <h5>System Settings</h5>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.systemSetting.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.settings.system.form')
            </form>
        </div>
    </div>
</div>
