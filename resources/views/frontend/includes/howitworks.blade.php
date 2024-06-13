<section id="howitworks" class="howitworks bg-light-gray">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 col-lg-6">
                <div class="heading">
                    <h6>How it works</h2>
                        <h2>Anything can move or deliver with in 3 easy steps</h2>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <p>Our global logistics expertise, advanced supply chain technology & customized logistics solutions
                    will help you analyze, develop and implement successful supply chain management strategies from
                    end-to-end.</p>
                {{-- Redirect to About Us --}}
                <div class="arrow-button">
                    <a href="{{ route('about-us') }}">
                        <i class="fas fa-long-arrow-alt-right mr-2"></i> Read More
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
                            <h4>Select service</h4>
                            {{-- Description --}}
                            <p>First select the service which you want to avail, either you want our delivery or moving
                                service.</p>

                            {{-- Redirect to New Booking --}}
                            <div class="arrow-button">
                                <a href="{{ route('newBooking') }}">
                                    <i class="fas fa-long-arrow-alt-right mr-2"></i> View Services
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
                            <h4>Book Service</h4>
                            {{-- Description --}}
                            <p>Set your pickup & drop-off location, select time and select the vehicle that is right for
                                you.</p>

                            {{-- Redirect to New Booking --}}
                            <div class="arrow-button">
                                <a href="{{ route('newBooking') }}">
                                    <i class="fas fa-long-arrow-alt-right mr-2"></i> Book Now
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
                            <h4>Track Booking</h4>
                            {{-- Description --}}
                            <p>Track your booking, and get your goods delivered to your door.</p>

                            {{-- Redirect to Track Booking --}}
                            <div class="arrow-button">
                                <a href="#">
                                    <i class="fas fa-long-arrow-alt-right mr-2"></i> Track Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
