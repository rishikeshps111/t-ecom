@extends('frontend.layouts.app')

@section('title', 'Total Net Group')

@section('content')
    <div class="page-baner"
        style="background-image: linear-gradient(0deg, rgba(0, 0, 0, 0.64) 0%, rgba(0, 0, 0, 0.59) 100%), url({{ asset('frontend/img/baner/3.jpg') }});">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h1>Secretarial Services</h1>
                    <p>Structural Strategy</p>
                </div>
            </div>

        </div>
    </div>
    <section class="sec-padding-2">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5">
                    <div class="svc-video">
                        <iframe
                            src="//www.youtube.com/embed/QTPp6z5u4Ik?autoplay=1&amp;mute=1&amp;enablejsapi=1&amp;rel=0&amp;modestbranding=1"
                            frameborder="0" allowfullscreen="allowfullscreen"></iframe>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="svc-video-right">
                        <h3>Chan Sau Yoke</h3>
                        <p>Company secretarial services Act as named Company Secretary Maintaining statutory books and
                            documents Safekeeping of Company Common Seal Provision of registered office address
                            Monitoring compliance with statutory requirements under Companies Act, 2016 Company's
                            Constitution Striking off and voluntary winding up </p>
                        <p>Company registration and incorporation in Malaysia, Incorporation services for private
                            limited companies. Advisory services for business start up in Malaysia

                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="sec-padding-2 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="sec-title">
                        <h2>Our People</h2>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-lg-6 mb-3">
                    <div class="people-widget">
                        <img src="{{ asset('frontend/img/Chun Guan.png') }}" alt="">
                        <div>
                            <h3>Chan Sau Yoke </h3>
                            <p>Chan Sau Yoke is a Licensed Secretary and has more than 18 years of experience in
                                handling corporate secretarial matters in the profession and the corporate sector.</p>
                            <p>She graduated with a Bachelor of Law from United Kingdom. She is currently pursuing
                                Certificate in Legal Practice in Malaysia.</p>
                            <p> She awarded "TRUSTWORTHY SERVICES" from SHANGHAI BUSINESS MEDIA in 2021.She also
                                interviewed by British media Publishing House from London, UK in 2023 & her profile
                                published in "Successful Person in Malaysia", 5th edition.</p>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <section class="sec-padding-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-3">
                    <div class="svc-widget">
                        <img src="{{ asset('frontend/img/i4.jpg') }}" alt="">
                        <h3>Incorporation in Malaysia</h3>

                    </div>

                </div>
                <div class="col-lg-4 mb-3">
                    <div class="svc-widget">
                        <img src="{{ asset('frontend/img/i5.jpg') }}" alt="">
                        <h3>Company secretarial services</h3>

                    </div>

                </div>
                <div class="col-lg-4 mb-3">
                    <div class="svc-widget">
                        <img src="{{ asset('frontend/img/i6.jpg') }}" alt="">
                        <h3>Advisory services</h3>

                    </div>

                </div>
            </div>
        </div>
    </section>
    <section class="sec-padding-2 pt-0">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="sec-title">
                        <h2>Testimonials</h2>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-lg-12">
                    <div class="owl-carousel owl-theme testimonial-carousel carousel-btn2">
                        <div class="item">
                            <div class="test-widget" style="min-height: unset !important;">
                                <p>Total provides a viable option of corporate secretarial service, to assist you in
                                    handling business effectively which covers all aspect</p>
                                <div class="testi-user">
                                    <img src="{{ asset('frontend/img/Chun Guan.png') }}" alt="">
                                    <div>
                                        <h4>Total Business Holding Sdn Bhd</h4>

                                    </div>
                                </div>
                            </div>
                        </div>

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