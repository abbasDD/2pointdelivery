{{-- Sub Admin Form --}}
<div class="row">
    {{-- First Name --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="first_name">First Name</label>
            {{-- Add hidden field of id in form if not empty --}}
            @isset($client)
                <input type="hidden" name="id" value="{{ $client['id'] }}">
            @endisset
            <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name"
                name="first_name" value="{{ old('first_name', $client['first_name'] ?? '') }}"
                placeholder="Enter First Name" required>
            @error('first_name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    {{-- Middle Name --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="middle_name">Middle Name</label>
            <input type="text" class="form-control @error('middle_name') is-invalid @enderror" id="middle_name"
                name="middle_name" value="{{ old('middle_name', $client['middle_name'] ?? '') }}"
                placeholder="Enter Middle Name (optional)">
            @error('middle_name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    {{-- Last Name --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="last_name">Last Name</label>
            <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name"
                name="last_name" value="{{ old('last_name', $client['last_name'] ?? '') }}"
                placeholder="Enter Last Name" required>
            @error('last_name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    @if (!isset($client))
        {{-- Email --}}
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                    name="email" value="{{ old('email') }}" placeholder="Enter Email" required>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        {{-- Password --}}
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="password">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                    name="password" placeholder="Enter Password" {{ isset($client) ? '' : 'required' }}>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        {{-- Confirm Password --}}
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                    id="password_confirmation" name="password_confirmation" placeholder="Confirm Password"
                    {{ isset($client) ? '' : 'required' }}>
                @error('password_confirmation')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    @endif
    {{-- Gender --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="gender">Gender</label>
            <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                <option value="" selected disabled>Choose Gender</option>
                <option value="Male" {{ old('gender', $client['gender'] ?? '') == 'male' ? 'selected' : '' }}>
                    Male</option>
                <option value="Female" {{ old('gender', $client['gender'] ?? '') == 'female' ? 'selected' : '' }}>
                    Female</option>
            </select>
            @error('gender')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    {{-- Date of Birth --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="date_of_birth">Date of Birth</label>
            <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth"
                name="date_of_birth" value="{{ old('date_of_birth', $client['date_of_birth'] ?? '') }}">
            @error('date_of_birth')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    {{-- Account Type --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="company_enabled">Account Type</label>
            <select class="form-control @error('company_enabled') is-invalid @enderror" id="company_enabled"
                name="company_enabled" required>
                <option value="" selected disabled>Choose Account Type</option>
                <option value="1"
                    {{ old('company_enabled', $client['company_enabled'] ?? '') == 1 ? 'selected' : '' }}>
                    Company
                </option>
                <option value="0"
                    {{ old('company_enabled', $client['company_enabled'] ?? '') == 0 ? 'selected' : '' }}>
                    Individual
                </option>
            </select>
            @error('company_enabled')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    {{-- Tax ID --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="tax_id">Tax ID</label>
            <input type="text" class="form-control @error('tax_id') is-invalid @enderror" id="tax_id"
                name="tax_id" value="{{ old('tax_id', $client['tax_id'] ?? '') }}"
                placeholder="Enter Tax ID (optional)">
            @error('tax_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    {{-- Submit Button --}}
    <div class="col-md-12 text-right">
        <button type="submit" class="btn btn-primary btn-block">
            {{ isset($client) ? 'Update' : 'Submit' }}
        </button>
    </div>
</div>
