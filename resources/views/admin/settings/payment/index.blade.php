<div class="container">
    <h5>Payment Settings</h5>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.paymentSetting.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.settings.payment.form')
            </form>
        </div>
    </div>
</div>
