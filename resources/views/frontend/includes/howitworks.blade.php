<section id="howitworks" class="howitworks bg-light-gray">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 col-lg-6">
                <div class="heading">
                    <h6>{{ __('frontend.howitworks.heading') }}</h2>
                        <h2>{{ __('frontend.howitworks.title') }}</h2>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <p>{{ __('frontend.howitworks.content') }}</p>
                {{-- Redirect to About Us --}}
                <div class="arrow-button">
                    <a href="{{ route('about-us') }}">
                        <i class="fas fa-long-arrow-alt-right mr-2"></i> {{ __('frontend.howitworks.button_text') }}
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            {{-- Step 1 --}}
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="icon">
                            <i class="fa fa-dolly"></i>
                        </div>
                        <div class="content">
                            {{-- Icon --}}
                            <i class="fa fa-dolly"></i>
                            {{-- Heading --}}
                            <h4 class="mb-2">{{ __('frontend.howitworks.step01.title') }}</h4>
                            {{-- Description --}}
                            <p>{{ __('frontend.howitworks.step01.subtitle') }}</p>

                            {{-- Redirect to New Booking --}}
                            <div class="arrow-button">
                                <a href="{{ route('newBooking') }}">
                                    <i class="fas fa-long-arrow-alt-right mr-2"></i>
                                    {{ __('frontend.howitworks.step01.button_text') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Step 2 --}}
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="icon">
                            <i class="fa-solid fa-truck"></i>
                        </div>
                        <div class="content">
                            {{-- Icon --}}
                            <i class="fa-solid fa-truck"></i>
                            {{-- Heading --}}
                            <h4 class="mb-2">{{ __('frontend.howitworks.step02.title') }}</h4>
                            {{-- Description --}}
                            <p>{{ __('frontend.howitworks.step02.subtitle') }}</p>

                            {{-- Redirect to New Booking --}}
                            <div class="arrow-button">
                                <a href="{{ route('newBooking') }}">
                                    <i class="fas fa-long-arrow-alt-right mr-2"></i>
                                    {{ __('frontend.howitworks.step02.button_text') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Step 3 --}}
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="icon">
                            <i class="fa-solid fa-map-pin"></i>
                        </div>
                        <div class="content">
                            {{-- Icon --}}
                            <i class="fa-solid fa-map-pin"></i>
                            {{-- Heading --}}
                            <h4 class="mb-2">{{ __('frontend.howitworks.step03.title') }}</h4>
                            {{-- Description --}}
                            <p>{{ __('frontend.howitworks.step03.subtitle') }}</p>

                            {{-- Redirect to Track Booking --}}
                            <div class="arrow-button">
                                <a href="#">
                                    <i class="fas fa-long-arrow-alt-right mr-2"></i>
                                    {{ __('frontend.howitworks.step03.button_text') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
