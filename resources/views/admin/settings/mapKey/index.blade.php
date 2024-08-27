<div class="container">
    <h5>Map API Key</h5>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.mapKeySetting.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.settings.mapKey.form')
            </form>
        </div>
    </div>
</div>
