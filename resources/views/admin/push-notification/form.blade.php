<div class="row">
    {{-- Select Users --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="user_id">Select User</label>
            <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                <option value="" disabled>Select User</option>
                <option value="all"
                    {{ old('user_id', $pushNotification['user_id'] ?? '') == 'all' ? 'selected' : '' }}>
                    All
                </option>
                <option value="helpers"
                    {{ old('user_id', $pushNotification['user_id'] ?? '') == 'helpers' ? 'selected' : '' }}>
                    Helpers
                </option>
                <option value="clients"
                    {{ old('user_id', $pushNotification['user_id'] ?? '') == 'clients' ? 'selected' : '' }}>
                    Clients
                </option>
            </select>
            @error('user_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Title --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="title">Title</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                name="title" value="{{ old('title', $pushNotification['title'] ?? '') }}" placeholder="Enter Title"
                required>
            @error('title')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    {{-- Body --}}
    <div class="col-md-12">
        {{-- Style to hide file upload --}}
        @trixassets
        <style>
            .trix-button--icon-attach {
                display: none !important;
            }
        </style>
        <div class="form-group mb-3">
            <label for="body">Body</label>
            <input id="body" type="hidden" name="body"
                value="{{ old('body', $pushNotification['body'] ?? '') }}" required>
            <trix-editor input="body" class="trix-content"></trix-editor>
            @error('body')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>


    {{-- Checkbox for Email --}}
    <div class="col-md-4">
        <label for="send_email">Would you like to send email?</label>
        <div class="form-group mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="send_email" id="send_email"
                    {{ old('send_email', $pushNotification['send_email'] ?? '') ? 'checked' : '' }}>
                <label class="form-check-label" for="send_email">
                    Send Email
                </label>
            </div>
        </div>
    </div>


    {{-- Submit Button --}}
    <div class="col-md-12 text-right">
        <button type="submit" class="btn btn-primary btn-block">
            Send
        </button>
    </div>

</div>
