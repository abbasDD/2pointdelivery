<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Social Links</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('client.update.social') }}" method="POST">
            @csrf
            <div class="row">
                {{-- Facebook --}}
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <div class="input-group">
                            <input type="text" id="facebook" class="form-control" placeholder="Facebook"
                                name="facebook" value="{{ old('facebook', $social_links['facebook'] ?? '') }}"
                                aria-describedby="facebook">
                            <span class="input-group-text text-uppercase" id="facebook"><i
                                    class="fa-brands fa-facebook"></i></span>
                        </div>
                        @error('facebook')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                {{-- LinkedIn --}}
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <div class="input-group">
                            <input type="text" id="linkedin" class="form-control" placeholder="LinkedIn"
                                name="linkedin" value="{{ old('linkedin', $social_links['linkedin'] ?? '') }}"
                                aria-describedby="linkedin">
                            <span class="input-group-text text-uppercase" id="linkedin"><i
                                    class="fa-brands fa-linkedin"></i></span>
                        </div>
                        @error('linkedin')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                {{-- Instagram --}}
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <div class="input-group">
                            <input type="text" id="instagram" class="form-control" placeholder="Instagram"
                                name="instagram" value="{{ old('instagram', $social_links['instagram'] ?? '') }}"
                                aria-describedby="instagram">
                            <span class="input-group-text text-uppercase" id="instagram"><i
                                    class="fa-brands fa-instagram"></i></span>
                        </div>
                        @error('instagram')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                {{-- Tiktok --}}
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <div class="input-group">
                            <input type="text" id="tiktok" class="form-control" placeholder="Tiktok"
                                name="tiktok" value="{{ old('tiktok', $social_links['tiktok'] ?? '') }}"
                                aria-describedby="tiktok">
                            <span class="input-group-text text-uppercase" id="tiktok"><i
                                    class="fa-brands fa-tiktok"></i></span>
                        </div>
                        @error('tiktok')
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
