{{--   Form --}}
<div class="row">
    {{-- List of $helpTopics --}}
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="help_topic_id">Category</label>
            <select class="form-control @error('help_topic_id') is-invalid @enderror" id="help_topic_id"
                name="help_topic_id" required>
                <option value="" selected disabled>Select Topic</option>
                @foreach ($helpTopics as $topic)
                    <option value="{{ $topic['id'] }}"
                        {{ old('help_topic_id', $helpQuestion['help_topic_id'] ?? '') == $topic['id'] ? 'selected' : '' }}>
                        {{ $topic['name'] }}
                    </option>
                @endforeach
            </select>
            @error('help_topic_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="question">Question</label>
            <input type="text" class="form-control @error('question') is-invalid @enderror" id="question"
                name="question" value="{{ old('question', $helpQuestion['question'] ?? '') }}"
                placeholder="Enter Question" required>
            @error('question')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    {{-- Answer --}}
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="answer">Answer</label>
            <textarea class="form-control @error('answer') is-invalid @enderror" id="answer" name="answer" rows="3"
                placeholder="Enter Answer" required>{{ old('answer', $helpQuestion['answer'] ?? '') }}</textarea>
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
            {{ isset($helpQuestion) ? 'Update' : 'Add' }}
        </button>
    </div>
</div>