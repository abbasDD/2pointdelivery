{{-- Sub Admin Form --}}


<div class="row">

    {{-- Profile Image --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <div class="image-selection">
                <div class="mx-auto" style="max-width: 150px;">
                    <img id="profile_image_preview"
                        src="{{ isset($admin['profile_image']) && $admin['profile_image'] !== null ? asset('images/users/' . $admin['profile_image']) : asset('images/users/default.png') }}"
                        alt="profile_image" class="p-3 border w-100 p-3"
                        onclick="document.getElementById('profile_image').click()">
                    <input type="file" name="profile_image" id="profile_image" class="d-none" accept="image/*">
                </div>
            </div>
            @if ($errors->has('profile_image'))
                <span class="invalid-feedback" role="alert">
                    <strong>Profile Image is required</strong>
                </span>
            @endif
        </div>
    </div>

    {{-- First Name --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="first_name">First Name</label>
            {{-- Add hidden field of id in form if not empty --}}
            @isset($admin)
                <input type="hidden" name="id" value="{{ $admin['id'] }}">
            @endisset
            <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name"
                name="first_name" value="{{ old('first_name', $admin['first_name'] ?? '') }}"
                placeholder="Enter First Name" required>
            @error('first_name')
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
                name="last_name" value="{{ old('last_name', $admin['last_name'] ?? '') }}" placeholder="Enter Last Name"
                required>
            @error('last_name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Admin Type --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="admin_type">Admin Type</label>
            <select class="form-control @error('admin_type') is-invalid @enderror" id="admin_type" name="admin_type"
                required>
                <option value="" selected disabled>Choose Admin Type</option>
                <option value="super" {{ old('admin_type', $admin['admin_type'] ?? '') == 'super' ? 'selected' : '' }}>
                    Super Admin</option>
                <option value="sub" {{ old('admin_type', $admin['admin_type'] ?? '') == 'sub' ? 'selected' : '' }}>
                    Sub Admin</option>
            </select>
            @error('admin_type')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    @empty($admin)
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
                    name="password" placeholder="Enter Password" {{ isset($admin) ? '' : 'required' }}>
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
                    {{ isset($admin) ? '' : 'required' }}>
                @error('password_confirmation')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    @endempty
    {{-- Submit Button --}}
    <div class="col-md-12 text-right">
        <button type="submit" class="btn btn-primary btn-block">
            {{ isset($admin) ? 'Update' : 'Add' }}
        </button>
    </div>
</div>


<script>
    // Profile Image JS
    document.querySelector('#profile_image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file.');
            event.target.value = null;
            return;
        }

        const reader = new FileReader();
        reader.onload = (event) => {
            document.querySelector('#profile_image_preview').src = event.target.result;
        }

        reader.readAsDataURL(file);
    });
</script>
