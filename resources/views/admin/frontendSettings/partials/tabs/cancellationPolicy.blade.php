{{-- A form to create new welcome email --}}
<form action="{{ route('admin.frontendSettings.cancellationPolicy.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <h5>Cancellation Policy</h5>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="cancellationPolicyBody">Body</label>
                        <input id="cancellationPolicyBody" type="hidden" name="value"
                            value="{{ old('value', $cancellationPolicy->value ?? '') }}">
                        <trix-editor input="cancellationPolicyBody" class="trix-content"></trix-editor>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


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
