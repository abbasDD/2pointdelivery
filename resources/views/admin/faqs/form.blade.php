{{-- FAQ  Form --}}
<div class="row">
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="question">Question</label>
            <input type="text" class="form-control @error('question') is-invalid @enderror" id="question"
                name="question" value="{{ old('question', $faq['question'] ?? '') }}" placeholder="Enter Question"
                required>
            @error('question')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    {{-- Answer --}}
    <div class="col-md-12">
        {{-- <div class="form-group mb-3">
            <label for="answer">Answer</label>
            <textarea class="form-control @error('answer') is-invalid @enderror" id="answer" name="answer" rows="6"
                placeholder="Enter Answer" required>{{ old('answer', $faq['answer'] ?? '') }}</textarea>
            @error('answer')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div> --}}
        <div class="form-group mb-3">
            <label for="answer">Answer</label>
            <input id="answer" type="hidden" name="answer" value="{{ old('answer', $faq['answer'] ?? '') }}"
                required>
            <trix-editor input="answer" class="trix-content"></trix-editor>
            @error('answer')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    {{-- Submit Button --}}
    <div class="col-md-12 text-right">
        <button type="submit" class="btn btn-primary btn-block">
            {{ isset($faq) ? 'Update' : 'Add' }}
        </button>
    </div>
</div>
