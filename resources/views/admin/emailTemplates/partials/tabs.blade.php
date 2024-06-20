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
                        {{-- bookingStatusEmail --}}
                        <button class="nav-link" id="v-pills-bookingStatusEmail-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-bookingStatusEmail" type="button" role="tab"
                            aria-controls="v-pills-bookingStatusEmail" aria-selected="false">
                            Booking Status
                        </button>
                        {{-- deliveryNotificationEmail --}}
                        <button class="nav-link" id="v-pills-deliveryNotificationEmail-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-deliveryNotificationEmail" type="button" role="tab"
                            aria-controls="v-pills-deliveryNotificationEmail" aria-selected="false">
                            Delivery Notification
                        </button>
                        {{-- feedbackEmail --}}
                        <button class="nav-link" id="v-pills-feedbackEmail-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-feedbackEmail" type="button" role="tab"
                            aria-controls="v-pills-feedbackEmail" aria-selected="false">
                            Feedback
                        </button>
                        {{-- requestFeedbackEmail --}}
                        <button class="nav-link" id="v-pills-requestFeedbackEmail-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-requestFeedbackEmail" type="button" role="tab"
                            aria-controls="v-pills-requestFeedbackEmail" aria-selected="false">
                            Request Feedback
                        </button>
                        {{-- refundNotificationEmail --}}
                        <button class="nav-link" id="v-pills-refundNotificationEmail-tab" data-bs-toggle="pill"
                            data-bs-target="#v-pills-refundNotificationEmail" type="button" role="tab"
                            aria-controls="v-pills-refundNotificationEmail" aria-selected="false">
                            Refund Notification
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
                {{-- bookingStatusEmail Tab --}}
                <div class="tab-pane fade" id="v-pills-bookingStatusEmail" role="tabpanel"
                    aria-labelledby="v-pills-bookingStatusEmail-tab">
                    @include('admin.emailTemplates.partials.tabs.bookingStatus')
                </div>
                {{-- deliveryNotificationEmail Tab --}}
                <div class="tab-pane fade" id="v-pills-deliveryNotificationEmail" role="tabpanel"
                    aria-labelledby="v-pills-deliveryNotificationEmail-tab">
                    @include('admin.emailTemplates.partials.tabs.deliveryNotification')
                </div>
                {{-- feedbackEmail Tab --}}
                <div class="tab-pane fade" id="v-pills-feedbackEmail" role="tabpanel"
                    aria-labelledby="v-pills-feedbackEmail-tab">
                    @include('admin.emailTemplates.partials.tabs.feedback')
                </div>
                {{-- requestFeedbackEmail Tab --}}
                <div class="tab-pane fade" id="v-pills-requestFeedbackEmail" role="tabpanel"
                    aria-labelledby="v-pills-requestFeedbackEmail-tab">
                    @include('admin.emailTemplates.partials.tabs.requestFeedback')
                </div>
                {{-- refundNotificationEmail Tab --}}
                <div class="tab-pane fade" id="v-pills-refundNotificationEmail" role="tabpanel"
                    aria-labelledby="v-pills-refundNotificationEmail-tab">
                    @include('admin.emailTemplates.partials.tabs.refundNotification')
                </div>
            </div>
        </div>

    </div>
</div>
