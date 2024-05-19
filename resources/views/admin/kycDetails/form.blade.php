{{-- User Details --}}
@if ($userDetails)
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="form-group">
                <label for="name">Type:</label>
                <input class="form-control" type="text" id="name" name="name" placeholder="Enter Name"
                    value="{{ old('name', $kycDetails->type ?? '') }}" disabled>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="form-group">
                <label for="name">Full Name:</label>
                <input class="form-control" type="text" id="name" name="name" placeholder="Enter Name"
                    value="{{ old('name', $userDetails->first_name . ' ' . $userDetails->last_name ?? '') }}" disabled>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="form-group">
                <label for="name">Email:</label>
                <input class="form-control" type="text" id="name" name="name" placeholder="Enter Name"
                    value="{{ old('name', $kycDetails->user_email ?? '') }}" disabled>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="form-group">
                <label for="name">Phone Number:</label>
                <input class="form-control" type="text" id="name" name="name" placeholder="Enter Name"
                    value="{{ old('name', $userDetails->phone_no ?? '') }}" disabled>
            </div>
        </div>
    </div>
@endif
{{-- KYC Details --}}
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="front_image">ID Card Front:</label>
        <div class="form-group card clickable-card text-center" data-toggle="tooltip" data-placement="bottom"
            title="Click to upload ID card front image">
            @if (!isset($kycDetails['front_image']))
                <i class="fa fa-camera fa-3x m-3 camera-icon"></i>
            @endif
            <img src="{{ isset($kycDetails['front_image']) ? asset('/images/kyc/' . $kycDetails['front_image']) : '' }}"
                class="img-fluid mx-auto d-block p-2 selected-image h-100" alt="ID Card front">
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <label for="back_image">ID Card Back:</label>
        <div class="form-group card clickable-card text-center" data-toggle="tooltip" data-placement="bottom"
            title="Click to upload ID card back image">
            @if (!isset($kycDetails['back_image']))
                <i class="fa fa-camera fa-3x m-3 camera-icon"></i>
            @endif
            <img src="{{ isset($kycDetails['back_image']) ? asset('/images/kyc/' . $kycDetails['back_image']) : '' }}"
                class="img-fluid mx-auto d-block p-2 selected-image h-100" alt="ID Card back">
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label for="id_type">ID Card Type:</label>
            <input class="form-control" type="text" id="id_type" name="id_type" placeholder="Enter ID Card Type"
                value="{{ old('id_type', $kycDetails->id_type ?? '') }}" disabled>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label for="id_number">ID Number:</label>
            <input class="form-control" type="text" id="id_number" name="id_number" placeholder="Enter ID Number"
                value="{{ old('id_number', $kycDetails->id_number ?? '') }}" disabled>
        </div>
    </div>

    {{-- Select Country --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="country">Country</label>
            <input type="text" class="form-control @error('country') is-invalid @enderror" id="country"
                name="country" value="{{ app('addressHelper')->getCountryName($kycDetails['country']) }}"
                placeholder="Enter Country" disabled>
        </div>
    </div>

    {{-- Select State --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="state">State</label>
            <input type="text" class="form-control @error('state') is-invalid @enderror" id="state"
                name="state" value="{{ app('addressHelper')->getStateName($kycDetails['state']) }}"
                placeholder="Enter State" disabled>
        </div>
    </div>

    {{-- Select City --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="city">City</label>
            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city"
                name="city" value="{{ app('addressHelper')->getCityName($kycDetails['city']) }}"
                placeholder="Enter City" disabled>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label for="issue_date">Issue Date:</label>
            <input class="form-control" type="date" id="issue_date" name="issue_date"
                value="{{ old('issue_date', $kycDetails->issue_date ?? '') }}" disabled>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <div class="form-group">
            <label for="expiry_date">Expiry Date:</label>
            <input class="form-control" type="date" id="expiry_date" name="expiry_date"
                value="{{ old('expiry_date', $kycDetails->expiry_date ?? '') }}" disabled>
        </div>
    </div>
</div>
<div class="col-md-12 mb-3">
    @if ($kycDetails->is_verified == 0)
        <div class="form-group" style="float: right">
            <a href="{{ route('admin.kycDetail.approve', $kycDetails->id) }}" class="btn btn-primary"><i
                    class="fa-solid fa-check"></i> Approve</a>
            <a href="{{ route('admin.kycDetail.reject', $kycDetails->id) }}" class="btn btn-danger"><i
                    class="fa-solid fa-xmark"></i> Reject</a>
        </div>
    @else
        <div class="form-group" style="float: right">

            @if ($kycDetails->is_verified == 1)
                <button class="btn btn-primary">Approved</button>
            @elseif($kycDetails->is_verified == 2)
                <button class="btn btn-danger">Rejected</button>
            @endif
        </div>
    @endif
</div>
