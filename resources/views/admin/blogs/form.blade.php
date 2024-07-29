{{-- Blog  Form --}}
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


<script>
    document.addEventListener("trix-attachment-add", function(event) {
        if (event.attachment.file) {
            uploadFileAttachment(event.attachment);
        }
    });

    function uploadFileAttachment(attachment) {
        // url
        var url = "{{ route('attachments.store') }}";
        // base_url
        var base_url = "{{ url('/') }}";

        var file = attachment.file;
        var form = new FormData();
        form.append("file", file);

        var xhr = new XMLHttpRequest();
        xhr.open("POST", url, true);
        xhr.setRequestHeader("X-CSRF-TOKEN", '{{ csrf_token() }}');

        xhr.upload.onprogress = function(event) {
            var progress = event.loaded / event.total * 100;
            attachment.setUploadProgress(progress);
        };

        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                attachment.setAttributes({
                    url: base_url + '/' + response.url,
                    href: base_url + '/' + response.url
                });
            } else {
                console.error('Error uploading file:', xhr.status, xhr.statusText);
            }
        };

        xhr.onerror = function() {
            console.error('Error uploading file:', xhr.status, xhr.statusText);
        };

        xhr.send(form);
    }
</script>
