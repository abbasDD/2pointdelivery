<div class="container">
    <div class="row">
        {{-- Show at top on Mobile Devices --}}
        <div class="col-md-12">
            <div class="card mt-3">
                <div class="card-body p-3">
                    <div class="d-flex nav nav-pills gap-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        {{-- Welcome Email --}}
                        <button class="nav-link active" id="v-pills-welcomeEmail-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-welcomeEmail" type="button" role="tab"
                            aria-controls="v-pills-welcomeEmail" aria-selected="true">
                            Welcome Email
                        </button>
                        {{-- Password Reset --}}
                        <button class="nav-link" id="v-pills-passwordReset-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-passwordReset" type="button" role="tab"
                            aria-controls="v-pills-passwordReset" aria-selected="false">
                            Password Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="tab-content flex-grow-1" id="v-pills-tabContent">
                {{-- Welcome Email Tab  --}}
                <div class="tab-pane fade show active" id="v-pills-welcomeEmail" role="tabpanel"
                    aria-labelledby="v-pills-welcomeEmail-tab">
                    @include('admin.emailTemplates.partials.tabs.welcome')
                </div>
                {{-- Password Reset Tab --}}
                <div class="tab-pane fade" id="v-pills-passwordReset" role="tabpanel"
                    aria-labelledby="v-pills-passwordReset-tab">
                    @include('admin.emailTemplates.partials.tabs.passwordReset')
                </div>
            </div>
        </div>

    </div>
</div>
