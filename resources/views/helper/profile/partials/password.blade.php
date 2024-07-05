<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Password</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('helper.update.password') }}" method="POST">
            @csrf
            <div class="row">
                {{-- Current Password --}}
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <div class="input-group">
                            <input type="password" id="current_password" class="form-control"
                                placeholder="Current Password" name="current_password"
                                aria-describedby="current_password" required>
                            <span class="input-group-text text-uppercase toggle-password" id="toggle_current_password">
                                <i class="fa-solid fa-eye-slash"></i> <!-- initially hide -->
                            </span>
                        </div>
                        @error('current_password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                {{-- New Password --}}
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <div class="input-group">
                            <input type="password" id="new_password" class="form-control" placeholder="New Password"
                                name="new_password" aria-describedby="new_password" required>
                            <span class="input-group-text text-uppercase toggle-password" id="toggle_new_password">
                                <i class="fa-solid fa-eye-slash"></i> <!-- initially hide -->
                            </span>
                        </div>
                        @error('new_password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                {{-- Confirm Password --}}
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <div class="input-group">
                            <input type="password" id="confirm_password" class="form-control"
                                placeholder="Confirm Password" name="confirm_password"
                                aria-describedby="confirm_password" required>
                            <span class="input-group-text text-uppercase toggle-password" id="toggle_confirm_password">
                                <i class="fa-solid fa-eye-slash"></i> <!-- initially hide -->
                            </span>
                        </div>
                        @error('confirm_password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Show only if original_user_id is null which means user is the team owner --}}
            @if (session('original_user_id') == null)
                {{-- Button to Submit --}}
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            @endif

        </form>
    </div>
</div>


<script>
    // Current Password
    document.addEventListener("DOMContentLoaded", function() {
        const togglePassword = document.querySelector('#toggle_current_password');
        const currentPasswordInput = document.getElementById('current_password');

        togglePassword.addEventListener('click', function() {
            const icon = togglePassword.querySelector('i');
            if (currentPasswordInput.type === "password") {
                currentPasswordInput.type = "text";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                currentPasswordInput.type = "password";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });
    });

    // New Password
    document.addEventListener("DOMContentLoaded", function() {
        const togglePassword = document.querySelector('#toggle_new_password');
        const newPasswordInput = document.getElementById('new_password');

        togglePassword.addEventListener('click', function() {
            const icon = togglePassword.querySelector('i');
            if (newPasswordInput.type === "password") {
                newPasswordInput.type = "text";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                newPasswordInput.type = "password";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });
    });

    // Confirm Password
    document.addEventListener("DOMContentLoaded", function() {
        const togglePassword = document.querySelector('.toggle-password');
        const confirmPasswordInput = document.getElementById('confirm_password');

        togglePassword.addEventListener('click', function() {
            const icon = togglePassword.querySelector('i');
            if (confirmPasswordInput.type === "password") {
                confirmPasswordInput.type = "text";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                confirmPasswordInput.type = "password";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });
    });
</script>
