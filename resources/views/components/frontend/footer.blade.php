<!-- Remove the container if you want to extend the Footer to full width. -->
<div class="">

    <!-- Footer -->
    <footer class="text-center text-lg-start text-white bg-dark">
        <!-- Section: Social media -->
        <section class="bg-primary p-0">
            <div class="container d-md-flex justify-content-between p-4">
                <!-- Left -->
                <div class="">
                    <p class="mb-0">Get connected with us on social networks:</p>
                </div>
                <!-- Left -->

                <!-- Right -->
                <div>
                    <a href="https://facebook.com/" target="_blank" class="text-white me-4"><i
                            class="fab fa-facebook-f"></i></a>
                    <a href="https://twitter.com/" target="_blank" class="text-white me-4"><i
                            class="fab fa-twitter"></i>
                    </a>
                    <a href="https://instagram.com/" target="_blank" class="text-white me-4"><i
                            class="fab fa-instagram"></i></a>
                    <a href="https://linkedin.com/" target="_blank" class="text-white me-4">
                        <i class="fab fa-linkedin"></i></a>
                </div>
                <!-- Right -->
            </div>
        </section>
        <!-- Section: Social media -->

        <!-- Section: Links  -->
        <section class="">
            <div class="container text-center text-md-start">
                <!-- Grid row -->
                <div class="row mt-3">
                    <!-- Grid column -->
                    <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
                        <!-- Content -->
                        <div class="logo-white mb-3 text-white">
                            <img src="{{ config('website_logo') ? asset('images/logo/' . config('website_logo')) : asset('images/logo/icon.png') }}"
                                alt="2 Point" width="50">
                            <span class=" ml-2">{{ config('website_name') ?: 'Website Name' }} </span>
                        </div>
                        <p>
                            Let us shoulder the weight of delivering your cherished possessions with ease. Entrust us
                            with the responsibility of ensuring your items reach their destination stress-free.
                        </p>
                    </div>
                    <!-- Grid column -->

                    <!-- Grid column -->
                    <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
                        <!-- Links -->
                        <h6 class="text-uppercase fw-bold">Quick Links</h6>
                        <hr class="mb-4 bg-white mt-0 d-inline-block mx-auto" />
                        <p>
                            <a href="{{ route('services') }}" class="text-white">Services</a>
                        </p>
                        <p>
                            <a href="{{ route('about-us') }}" class="text-white">About Us</a>
                        </p>
                        <p>
                            <a href="{{ route('helper.register') }}" class="text-white">Join as Helper</a>
                        </p>
                        <p>
                            <a href="{{ route('help') }}" class="text-white">Help</a>
                        </p>
                    </div>
                    <!-- Grid column -->

                    <!-- Grid column -->
                    <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
                        <!-- Links -->
                        <h6 class="text-uppercase fw-bold">Useful links</h6>
                        <hr class="mb-4 bg-white mt-0 d-inline-block mx-auto" />
                        <p>
                            <a href="{{ route('contact-us') }}" class="text-white">Contact Us</a>
                        </p>
                        <p>
                            <a href="#!" class="text-white">Terms & Conditions</a>
                        </p>
                        <p>
                            <a href="#!" class="text-white">Privacy Policy</a>
                        </p>
                        <p>
                            <a href="#!" class="text-white">Cancellation Policy</a>
                        </p>
                    </div>
                    <!-- Grid column -->

                    <!-- Grid column -->
                    <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                        <!-- Links -->
                        <h6 class="text-uppercase fw-bold">Contact</h6>
                        <hr class="mb-4 bg-white mt-0 d-inline-block mx-auto" />
                        <p><i class="fas fa-home mr-3"></i> 7551 Mapleford Blvd, Regina, S4Y0C6</p>
                        <p><i class="fas fa-envelope mr-3"></i> info@2pointdelivery.com</p>
                        <p><i class="fas fa-phone mr-3"></i> +1 (639) 997-2710</p>
                        <p><i class="fas fa-print mr-3"></i> +1 (306) 807-9001</p>
                    </div>
                    <!-- Grid column -->
                </div>
                <!-- Grid row -->
            </div>
        </section>
        <!-- Section: Links  -->

        <!-- Copyright -->
        <div class="bg-primary">
            <div class="container">
                <div class="d-md-flex justify-content-between align-items-center p-3">
                    <div>
                        © 2024 Copyright:
                        <a class="text-white" href="#">{{ config('website_name') ?: '2 Point' }}</a>
                    </div>
                    <div>
                        <span class="text-white ml-3">Designed by <a class="text-white" href="https://elabdtech.com/"
                                target="_blank">Elabd Technologies</a></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Copyright -->
    </footer>
    <!-- Footer -->

</div>
<!-- End of .container -->
