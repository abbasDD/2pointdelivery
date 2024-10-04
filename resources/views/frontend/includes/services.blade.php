<section id="services">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <div class="heading">
                    <h6>What We Offer</h6>
                    <h2>Our Valued Services</h2>
                    <p>In the realm of logistics delivery, efficiency and reliability are paramount. Our logistics
                        delivery services are meticulously designed to streamline the transportation of goods from point
                        A to point B seamlessly. With a focus on precision timing and route optimization, we ensure that
                        your packages arrive at their destination promptly and intact. </p>
                </div>
            </div>
        </div>
        <div class="service-slider">
            <div class="row mx-auto my-auto justify-content-center">
                <div id="seviceCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner" role="listbox">
                        @forelse ($serviceTypes as $serviceType)
                            {{-- Service {{ $loop->iteration }} --}}
                            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                <div class="col-md-4">
                                    <div class="card mx-2">
                                        <div class="card-body">
                                            <img src="{{ $serviceType->image ? asset('images/service_types/' . $serviceType->image) : asset('images/service_types/default.png') }}"
                                                alt="Image" height="200" class="w-100 mb-3">
                                            <h5 class="card-title">{{ $serviceType->name }}</h5>
                                            <p class="card-text">
                                                {{ $serviceType->description }}
                                            </p>
                                            {{-- Redirect to Booking --}}
                                            <div class="arrow-button">
                                                <a href="{{ route('newBooking') }}">
                                                    <i class="fas fa-long-arrow-alt-right mr-2"></i> Book This Service
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            {{-- Service 1 --}}
                            <div class="carousel-item active">
                                <div class="col-md-4">
                                    <div class="card mx-2">
                                        <div class="card-body">
                                            <img src="{{ asset('frontend/images/services/service-1.png') }}"
                                                alt="Image" class="img-fluid mx-auto">
                                            <h5 class="card-title">Small</h5>
                                            <p class="card-text">Introducing our Small Item Delivery Service - because
                                                we
                                                understand the importance of life's little essentials. Whether it's your
                                                phone, keys, or glasses that you've left behind, we're here to swiftly
                                                deliver them to your doorstep. With our reliable and efficient service,
                                                you
                                                can rest assured that your essentials will be safely returned to you,
                                                allowing you to carry on with your day without missing a beat. Simply
                                                reach
                                                out to us, and consider it delivered!</p>
                                            {{-- Redirect to Booking --}}
                                            <div class="arrow-button">
                                                <a href="{{ route('newBooking') }}">
                                                    <i class="fas fa-long-arrow-alt-right mr-2"></i> Book This Service
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforelse

                    </div>
                    <a class="carousel-control-prev w-aut" href="#seviceCarousel" role="button" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </a>
                    <a class="carousel-control-next w-aut" href="#seviceCarousel" role="button" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    let items = document.querySelectorAll('.carousel .carousel-item')

    items.forEach((el) => {
        const minPerSlide = 3;
        let next = el.nextElementSibling
        for (var i = 1; i < minPerSlide; i++) {
            if (!next) {
                // wrap carousel by using first child
                next = items[0]
            }
            let cloneChild = next.cloneNode(true)
            el.appendChild(cloneChild.children[0])
            next = next.nextElementSibling
        }
    })
</script>
