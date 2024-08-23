<section id="testimonials">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="heading">
                    <h6>Testimonials</h6>
                    <h2>Individually Assess
                        Each Plan And Offer
                        Optimal Solutions!</h2>
                    <p>Serving an impressive list of long-term clients with experience and expertise in multiple
                        industries.</p>

                    {{-- See All --}}
                    <div class="arrow-button">
                        <a href="{{ route('testimonials') }}">
                            <i class="fas fa-long-arrow-alt-right mr-2"></i>
                            See All
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="slider">
                    <div id="carouselAboutIndicators" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($testimonials as $testimonial)
                                {{-- Testimonial {{ $loop->iteration }} --}}
                                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-md-flex align-items-center gap-3">
                                                <div class="user-image">
                                                    <img class="rounded-circle" width="50"
                                                        src="{{ $testimonial->client_image == null ? asset('images/users/default.png') : asset('images/users/' . $testimonial->client_image) }}"
                                                        alt="User 1">
                                                </div>
                                                <div class="user-text">
                                                    {{-- Star Rating --}}
                                                    <div class="star-rating mb-3">
                                                        <i
                                                            class="fas fa-star {{ $testimonial->rating >= 1 ? 'text-primary' : '' }}"></i>
                                                        <i
                                                            class="fas fa-star {{ $testimonial->rating >= 2 ? 'text-primary' : '' }}"></i>
                                                        <i
                                                            class="fas fa-star {{ $testimonial->rating >= 3 ? 'text-primary' : '' }}"></i>
                                                        <i
                                                            class="fas fa-star {{ $testimonial->rating >= 4 ? 'text-primary' : '' }}"></i>
                                                        <i
                                                            class="fas fa-star {{ $testimonial->rating >= 5 ? 'text-primary' : '' }}"></i>
                                                    </div>
                                                    <p class="mb-2">
                                                        {{ $testimonial->review }}
                                                    </p>
                                                    <h5 class="mb-1 text-primary">{{ $testimonial->client_name }}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselAboutIndicators"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselAboutIndicators"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
