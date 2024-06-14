<div class="container">
    <div class="row">
        {{-- Show at top on Mobile Devices --}}
        <div class="col-md-12">
            <div class="card mt-3">
                <div class="card-body p-3">
                    <div class="d-flex nav nav-pills gap-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        {{-- Privacy Policy --}}
                        <button class="nav-link active" id="v-pills-privacyPolicy-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-privacyPolicy" type="button" role="tab"
                            aria-controls="v-pills-privacyPolicy" aria-selected="true">
                            Privacy Policy
                        </button>
                        {{-- Terms and Conditions --}}
                        <button class="nav-link" id="v-pills-termsAndConditions-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-termsAndConditions" type="button" role="tab"
                            aria-controls="v-pills-termsAndConditions" aria-selected="false">
                            Terms and Conditions
                        </button>
                        {{-- Cancellation Policy --}}
                        <button class="nav-link" id="v-pills-cancellationPolicy-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-cancellationPolicy" type="button" role="tab"
                            aria-controls="v-pills-cancellationPolicy" aria-selected="false">
                            Cancellation Policy
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="tab-content flex-grow-1" id="v-pills-tabContent">
                {{-- Privacy Policy Tab  --}}
                <div class="tab-pane fade show active" id="v-pills-privacyPolicy" role="tabpanel"
                    aria-labelledby="v-pills-privacyPolicy-tab">
                    @include('admin.frontendSettings.partials.tabs.privacyPolicy')
                </div>
                {{-- Terms and Conditions Tab --}}
                <div class="tab-pane fade" id="v-pills-termsAndConditions" role="tabpanel"
                    aria-labelledby="v-pills-termsAndConditions-tab">
                    @include('admin.frontendSettings.partials.tabs.termsAndConditions')
                </div>
                {{-- Cancellation Policy Tab --}}
                <div class="tab-pane fade" id="v-pills-cancellationPolicy" role="tabpanel"
                    aria-labelledby="v-pills-cancellationPolicy-tab">
                    @include('admin.frontendSettings.partials.tabs.cancellationPolicy')
                </div>
            </div>
        </div>

    </div>
</div>
