@extends('admin.layouts.app')

@section('title', 'Moving Config')

@section('content')

    <div class="container">
        <div class="row">
            {{-- Show at top on Mobile Devices --}}
            <div class="col-md-2 d-block d-md-none">
                <div class="card mt-3">
                    <div class="card-body p-3">
                        <div class="d-flex nav nav-pills justify-content-between" id="v-pills-tab" role="tablist"
                            aria-orientation="vertical">
                            <button class="nav-link active" id="v-pills-rooms-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-rooms" type="button" role="tab" aria-controls="v-pills-rooms"
                                aria-selected="true">
                                <i class="fas fa-user"></i> <span class="d-none d-lg-inline">Rooms</span>
                            </button>
                            <button class="nav-link" id="v-pills-floorPlan-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-floorPlan" type="button" role="tab"
                                aria-controls="v-pills-floorPlan" aria-selected="true">
                                <i class="fas fa-map-marker-alt"></i> <span class="d-none d-lg-inline">Floor Plan</span>
                            </button>
                            <button class="nav-link" id="v-pills-floorAssess-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-floorAssess" type="button" role="tab"
                                aria-controls="v-pills-floorAssess" aria-selected="false">
                                <i class="fas fa-building"></i> <span class="d-none d-lg-inline">Floor Access</span>
                            </button>
                            <button class="nav-link" id="v-pills-jobDetails-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-jobDetails" type="button" role="tab"
                                aria-controls="v-pills-jobDetails" aria-selected="false">
                                <i class="fas fa-key"></i> <span class="d-none d-lg-inline">Job Details</span>
                            </button>
                            <button class="nav-link" id="v-pills-movingDetail-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-movingDetail" type="button" role="tab"
                                aria-controls="v-pills-movingDetail" aria-selected="false">
                                <i class="fas fa-link"></i> <span class="d-none d-lg-inline">Moving Details</span>
                            </button>
                            <button class="nav-link" id="v-pills-priority-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-priority" type="button" role="tab"
                                aria-controls="v-pills-priority" aria-selected="true">
                                <i class="fas fa-map-marker-alt"></i> <span class="d-none d-lg-inline">Priority</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-10 col-sm-10">
                <div class="tab-content flex-grow-1" id="v-pills-tabContent">
                    {{-- Rooms Tab  --}}
                    <div class="tab-pane fade show active" id="v-pills-rooms" role="tabpanel"
                        aria-labelledby="v-pills-rooms-tab">
                        {{-- Load Rooms Tab  --}}
                        @include('admin.movingConfig.noOfRooms.index')
                    </div>
                    {{-- floorPlan Tab  --}}
                    <div class="tab-pane fade" id="v-pills-floorPlan" role="tabpanel"
                        aria-labelledby="v-pills-floorPlan-tab">
                        {{-- Load floorPlan Tab  --}}
                        @include('admin.movingConfig.floorPlan.index')
                    </div>
                    {{-- Company Tab --}}
                    <div class="tab-pane fade" id="v-pills-floorAssess" role="tabpanel"
                        aria-labelledby="v-pills-floorAssess-tab">
                        {{-- Load floorPlan Tab --}}
                        @include('admin.movingConfig.floorAssess.index')
                    </div>
                    {{--  Priority Tab --}}
                    <div class="tab-pane fade" id="v-pills-jobDetails" role="tabpanel"
                        aria-labelledby="v-pills-jobDetails-tab">
                        {{-- Load  Priority Tab --}}
                        @include('admin.movingConfig.jobDetails.index')
                    </div>
                    {{-- Authentication Links Tab --}}
                    <div class="tab-pane fade" id="v-pills-movingDetail" role="tabpanel"
                        aria-labelledby="v-pills-movingDetail-tab">
                        {{-- Load  Authentication Links Tab --}}
                        @include('admin.movingDetail.index')
                    </div>
                    {{-- Priority Tab  --}}
                    <div class="tab-pane fade" id="v-pills-priority" role="tabpanel" aria-labelledby="v-pills-priority-tab">
                        {{-- Load priority Tab  --}}
                        @include('admin.movingConfig.priority.index')
                    </div>
                </div>
            </div>
            {{-- Show on right side on Desktop --}}
            <div class="col-md-2 d-none d-md-block">
                <div class="card mt-3">
                    <div class="card-body p-3">
                        <div class="d-flex flex-column nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                            aria-orientation="vertical">
                            <button class="nav-link active" id="v-pills-rooms-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-rooms" type="button" role="tab"
                                aria-controls="v-pills-rooms" aria-selected="true">
                                <i class="fas fa-user"></i> <span class="d-none d-lg-inline">Rooms</span>
                            </button>
                            <button class="nav-link" id="v-pills-floorPlan-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-floorPlan" type="button" role="tab"
                                aria-controls="v-pills-floorPlan" aria-selected="true">
                                <i class="fas fa-map-marker-alt"></i> <span class="d-none d-lg-inline">Floor Plan</span>
                            </button>
                            <button class="nav-link" id="v-pills-floorAssess-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-floorAssess" type="button" role="tab"
                                aria-controls="v-pills-floorAssess" aria-selected="false">
                                <i class="fas fa-building"></i> <span class="d-none d-lg-inline">Floor Access</span>
                            </button>
                            <button class="nav-link" id="v-pills-jobDetails-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-jobDetails" type="button" role="tab"
                                aria-controls="v-pills-jobDetails" aria-selected="false">
                                <i class="fas fa-key"></i> <span class="d-none d-lg-inline">Job Details</span>
                            </button>
                            <button class="nav-link" id="v-pills-movingDetail-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-movingDetail" type="button" role="tab"
                                aria-controls="v-pills-movingDetail" aria-selected="false">
                                <i class="fas fa-link"></i> <span class="d-none d-lg-inline">Moving Details</span>
                            </button>
                            <button class="nav-link" id="v-pills-priority-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-priority" type="button" role="tab"
                                aria-controls="v-pills-priority" aria-selected="true">
                                <i class="fas fa-map-marker-alt"></i> <span class="d-none d-lg-inline">Priority</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


@endsection
