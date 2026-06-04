@extends('frontend.layouts.app')

@section('title', 'Total Net Group')

@section('content')

    <div class="page-baner"
        style="background-image: linear-gradient(0deg, rgba(0, 0, 0, 0.64) 0%, rgba(0, 0, 0, 0.59) 100%), url({{ asset('frontend/img/baner/4.jpg') }});">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h1>Contact us</h1>
                </div>
            </div>

        </div>
    </div>
    <section class="sec-padding-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-3">
                    <div class="contact-box">
                        <h6>TOTAL BUSINESS HOLDINGS SDN BHD</h6>
                        <ul>
                            <li><i class="fa-solid fa-location-dot"></i>
                                41 & 43, Jalan Kenari 21,
                                Bandar Puchong Jaya,
                                40170 47100
                                Malaysia</li>
                            <li><i class="fa-solid fa-phone"></i>+603 8076 2928</li>
                            <li><i class="fa-solid fa-envelope"></i>total.net.my</li>
                        </ul>
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3984.1916640315308!2d101.61986267497066!3d3.0432477969325795!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc4b50bbc2ca17%3A0x7ef2a2790553cee6!2s41%20%26%2043%2C%20Jalan%20Kenari%2021%2C%20Bandar%20Puchong%20Jaya%2C%2047100%20Puchong%2C%20Selangor%2C%20Malaysia!5e0!3m2!1sen!2sin!4v1780471764805!5m2!1sen!2sin"
                            style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>

                </div>
                <div class="col-lg-8 ps-5  mb-3 ps-5-0">
                    <div class="contact-form">
                        <h6>Contact us about anything related to our company or services.
                            We'll do our best to get back to you as soon as possible.</h6>
                        <form action="">
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label for="">Your Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control shadow-none">
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label for="">Phone Number </label>
                                    <input type="text" class="form-control shadow-none">
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label for="">Email <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control shadow-none">
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label for="">Your Company</label>
                                    <input type="text" class="form-control shadow-none">
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label for="">Subject <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control shadow-none">
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label for="">Your Question</label>
                                    <textarea name="" id="" class="form-control shadow-none"></textarea>
                                </div>
                                <div class="col-lg-12">
                                    <button type="submit">Send Message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="cta-box">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <h2>STRATEGIES YOUR BUSINESS FOR THE FUTURE</h2>
                    <p>Join us and make your company a better place.</p>
                    <a href="{{ route('contact') }}">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
@endsection