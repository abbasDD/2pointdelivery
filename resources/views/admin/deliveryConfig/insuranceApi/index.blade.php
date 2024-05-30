{{-- insurance API --}}

<div class="container">
    <h5>Insurance API</h5>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.deliveryConfig.insurance.update') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @include('admin.deliveryConfig.insuranceApi.form')
            </form>
        </div>
    </div>
</div>
