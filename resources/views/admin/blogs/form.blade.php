{{-- FAQ  Form --}}
<div class="row">
    {{-- Title --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="title">Title</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                value="{{ old('title', $blog['title'] ?? '') }}" placeholder="Enter Title" required>
            @error('title')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    {{-- Author --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="author">Author</label>
            <input type="text" class="form-control @error('author') is-invalid @enderror" id="author"
                name="author" value="{{ old('author', $blog['author'] ?? '') }}" placeholder="Enter Author Name"
                required>
            @error('author')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    {{-- Body --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="body">Body</label>
            <input id="body" type="hidden" name="body" value="{{ old('body', $blog['body'] ?? '') }}" required>
            <trix-editor input="body" class="trix-content"></trix-editor>
            @error('body')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    {{-- Submit Button --}}
    <div class="col-md-12 text-right">
        <button type="submit" class="btn btn-primary btn-block">
            {{ isset($blog) ? 'Update' : 'Add' }}
        </button>
    </div>
</div>
