{{-- secureship API --}}
<div class="container">
    <h5>Secureship API</h5>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.deliveryConfig.secureship.update') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @include('admin.deliveryConfig.secureshipApi.form')
            </form>
        </div>
    </div>
</div>
