@extends('admin.layouts.app')

@section('title', 'Delivery Config')

@section('content')

    <div class="container">
        <div class="row">
            {{-- Show at top on Mobile Devices --}}
            <div class="col-md-2 d-block d-md-none">
                <div class="card mt-3">
                    <div class="card-body p-3">
                        <div class="d-flex nav nav-pills justify-content-between" id="v-pills-tab" role="tablist"
                            aria-orientation="vertical">
                            <button class="nav-link active" id="v-pills-insuranceApi-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-insuranceApi" type="button" role="tab"
                                aria-controls="v-pills-insuranceApi" aria-selected="true">
                                <i class="fas fa-user"></i> <span class="d-none d-lg-inline">Insurance</span>
                            </button>
                            <button class="nav-link" id="v-pills-secureshipApi-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-secureshipApi" type="button" role="tab"
                                aria-controls="v-pills-secureshipApi" aria-selected="true">
                                <i class="fas fa-map-marker-alt"></i> <span class="d-none d-lg-inline">Secureship</span>
                            </button>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-10 col-sm-10">
                <div class="tab-content flex-grow-1" id="v-pills-tabContent">
                    {{-- Rooms Tab  --}}
                    <div class="tab-pane fade show active" id="v-pills-insuranceApi" role="tabpanel"
                        aria-labelledby="v-pills-insuranceApi-tab">
                        {{-- Load Rooms Tab  --}}
                        @include('admin.deliveryConfig.insuranceApi.index')
                    </div>
                    {{-- secureshipApi Tab  --}}
                    <div class="tab-pane fade" id="v-pills-secureshipApi" role="tabpanel"
                        aria-labelledby="v-pills-secureshipApi-tab">
                        {{-- Load secureshipApi Tab  --}}
                        @include('admin.deliveryConfig.secureshipApi.index')
                    </div>
                </div>
            </div>
            {{-- Show on right side on Desktop --}}
            <div class="col-md-2 d-none d-md-block">
                <div class="card mt-3">
                    <div class="card-body p-3">
                        <div class="d-flex flex-column nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                            aria-orientation="vertical">
                            <button class="nav-link active" id="v-pills-insuranceApi-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-insuranceApi" type="button" role="tab"
                                aria-controls="v-pills-insuranceApi" aria-selected="true">
                                <i class="fas fa-user"></i> <span class="d-none d-lg-inline">Insurance</span>
                            </button>
                            <button class="nav-link" id="v-pills-secureshipApi-tab" data-bs-toggle="pill"
                                data-bs-target="#v-pills-secureshipApi" type="button" role="tab"
                                aria-controls="v-pills-secureshipApi" aria-selected="true">
                                <i class="fas fa-map-marker-alt"></i> <span class="d-none d-lg-inline">Secureship</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


@endsection
