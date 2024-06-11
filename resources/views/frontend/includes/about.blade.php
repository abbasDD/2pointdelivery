<section id="aboutus" class="about pt-5">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-5">
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
            <div class="col-12 col-lg-7">
                <div class="about-text">
                    <div class="heading">
                        <h6>About Us</h6>
                        <h2>Reliable Logistic & Transport Solutions Saves Your Time!</h2>
                    </div>
                    <div class="content mt-3">
                        <p>
                            Let us shoulder the weight of delivering your cherished possessions with ease. Entrust us
                            with
                            the responsibility of ensuring your items reach their destination stress-free. Our mission?
                            To
                            transform your relocation journey into a seamless adventure by handling every aspect of the
                            move, leaving you free to focus on what matters most: embracing your new beginnings.
                        </p>
                        <p>
                            Whether you're moving down the street or across the country, our dedicated team is committed
                            to
                            providing top-notch service every step of the way. From carefully packing and loading your
                            belongings to navigating the logistics of transportation and delivery, we've got you
                            covered.
                        </p>
                        {{-- Redirect to About Us --}}
                        <div class="read-more">
                            <a href="{{ route('about-us') }}">
                                <i class="fas fa-long-arrow-alt-right mr-2"></i> Read More
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
