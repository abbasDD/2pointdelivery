@extends('frontend.layouts.app')

@section('title', 'Contact Us')

@section('content')

    {{-- Header Map Section  --}}
    <section class="map map-2">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2537.769946107448!2d-104.69207632313156!3d50.501239283705175!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x531c1bf8eb8833bd%3A0x88d3a46be53d5b35!2s7551%20Mapleford%20Blvd%2C%20Zehner%2C%20SK%20S0G%205K0%2C%20Canada!5e0!3m2!1sen!2s!4v1718168632347!5m2!1sen!2s"
            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </section>

    {{-- Contact Us Section --}}
    <section class="contact-info">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-4">
                    <div class="row">
                        <div class="col-12 col-sm-6 col-lg-12">
                            <div class="">
                                <h4 class="text-primary">contact details</h4>
                                <ul class="list-unstyled">
                                    <li>
                                        <p><i class="fas fa-map-marker-alt text-primary"></i> 7551 Mapleford Blvd, Regina,
                                            S4Y0C6</p>
                                    </li>
                                    <li>
                                        <p>
                                            <i class="fas fa-envelope text-primary"></i>
                                            <a href="mailto::info@2pointdelivery.com">info@2pointdelivery.com</a>
                                        </p>
                                    </li>
                                    <li>
                                        <p>
                                            <i class="fas fa-phone-alt text-primary"></i>
                                            <a href="tel:+16399972710">+1 (639) 997-2710</a>
                                        </p>
                                    </li>
                                    <li>
                                        <p>
                                            <i class="fas fa-fax text-primary"></i>
                                            <a href="tel:+13068079001">+1 (306) 807-9001</a>
                                        </p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-12">
                            <div class="opening-hours">
                                <h4 class="text-primary">opening hours</h4>
                                <div class="d-flex align-items-center justify-content-between ">
                                    <p>Monday-Friday</p>
                                    <p>10:00 - 18:00</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between ">
                                    <p>Saturday</p>
                                    <p>10:00 - 14:00</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between ">
                                    <p>Sunday</p>
                                    <p>Closed</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-8">
                    {{-- Heading --}}
                    <div class="heading mb-3">
                        <h6 class="text-primary">Get in touch</h6>
                        <h2 class="mb-1">Contact Us</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    </div>
                    {{-- Form --}}
                    <form class="contactForm" method="post" action="#">
                        <div class="row">
                            {{-- Contact Name --}}
                            <div class="col-12 col-lg-6 mb-3">
                                <input class="form-control" type="text" name="contact-name" placeholder="Enter Name"
                                    required="" />
                            </div>
                            {{-- Contact Email --}}
                            <div class="col-12 col-lg-6 mb-3">
                                <input class="form-control" type="text" name="contact-email" placeholder="Enter Email"
                                    required="" />
                            </div>
                            {{-- Subject --}}
                            <div class="col-12 mb-3">
                                <input class="form-control" type="text" name="contact-subject" placeholder="Subject"
                                    required="" />
                            </div>
                            {{-- Contact Message --}}
                            <div class="col-12 mb-3">
                                <textarea class="form-control" name="contact-message" cols="30" rows="4" placeholder="Your Message"
                                    required=""></textarea>
                            </div>
                            {{-- Submit --}}
                            <div class="col-12 mb-3">
                                <button class="btn btn-primary" type="submit" value="Submit">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End .row-->
        </div>
        <!-- End .container-->
    </section>


    {{-- Get Apps Section  --}}
    @include('frontend.includes.getapps')


@endsection
