<section id="aboutus" class="about">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-7">
                <div class="about-text">
                    <div class="heading">
                        <h6>{{ __('frontend.about_section.heading') }}</h6>
                        <h2>{{ __('frontend.about_section.title') }}</h2>
                    </div>
                    <div class="content mt-3">
                        <p>
                            {{ __('frontend.about_section.content1') }}
                        </p>
                        <p>
                            {{ __('frontend.about_section.content2') }}
                        </p>
                        {{-- Redirect to About Us --}}
                        <div class="arrow-button">
                            <a href="{{ route('about-us') }}">
                                <i class="fas fa-long-arrow-alt-right mr-2"></i>
                                {{ __('frontend.about_section.button_text') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="slider">
                    <div id="carouselAboutIndicators" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            {{-- Slide 1 --}}
                            <div class="carousel-item active">
                                <div class="about-img about-img-left">
                                    <div class="about-img-warp bg-overlay bg-section"
                                        style="background-image: url('{{ asset('frontend/images/about/about-img.jpg') }}');">
                                    </div>
                                    <div class="counter">
                                        <div class="counter-icon">
                                            <i class="fas fa-shipping-fast"></i>
                                        </div>
                                        <div class="counter-num">
                                            <span class="counting">1,102</span>
                                            <p class="mb-1">m</p>
                                        </div>
                                        <div class="counter-name">
                                            <h6 class="mb-0">delivering goods</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Slide 2 --}}
                            <div class="carousel-item">
                                <div class="about-img about-img-left">
                                    <div class="about-img-warp bg-overlay bg-section"
                                        style="background-image: url('{{ asset('frontend/images/about/about-img.jpg') }}');">
                                    </div>
                                    <div class="counter">
                                        <div class="counter-icon">
                                            <i class="fas fa-shipping-fast"></i>
                                        </div>
                                        <div class="counter-num">
                                            <span class="counting">102</span>
                                            <p class="mb-1">K</p>
                                        </div>
                                        <div class="counter-name">
                                            <h6 class="mb-0">Happy Clients</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Add more carousel items here -->
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
