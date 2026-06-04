@extends('frontend.layouts.app')

@section('title', 'Total Net Group')

@section('content')

    <div class="page-baner"
        style="background-image: linear-gradient(0deg, rgba(0, 0, 0, 0.64) 0%, rgba(0, 0, 0, 0.59) 100%), url({{ asset('frontend/img/baner/5.jpg') }});">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h1>Taxation Services</h1>
                    <p>Compliance Strategy</p>
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
                            src="//www.youtube.com/embed/4G7IoU-s22o?autoplay=1&amp;mute=1&amp;rel=0&amp;modestbranding=1"
                            frameborder="0" allowfullscreen="allowfullscreen"></iframe>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="svc-video-right">
                        <h3>Queennie Ng</h3>
                        <h6>Tax Manager</h6>
                        <p>As the pace and complexity of tax laws continue to increase, Total Tax Services Sdn Bhd works
                            with you to help tailor solutions using local knowledge and business experience. With
                            achieving your tax objectives in today's ever-evolving business landscape, we assist our
                            clients to have best decisions to strategically move their business forward</p>
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
                            <h3>Tan Chun Guan</h3>
                            <p>Tan Chun Guan is a licensed holder of a tax advisor, GST advisor. MYGCAP and ASEAN
                                Chartered Accountant. He is a well-known speaker for tax and GST planning and other
                                strategy and corporate topics. </p>
                            <ul>
                                <li>MIA, Malaysia Institute of Accountant </li>
                                <li>CTIM, Chartered Tax Institute of Malaysia </li>
                                <li>MACS, Malaysian Association of Company Secretaries </li>
                                <li>MICCS, Malaysian Institute of Chartered Corporate Secretaries</li>
                                <li>MFPC, Malaysian Financial Planning Council </li>
                                <li>MIM, Malaysian Institute of Management </li>
                                <li>JCI, Junior Chamber International</li>
                                <li>Hua Zhong, Chinese Federation</li>
                                <li>ACCIM, The Associated Chinese Chambers of Commerce and Industry of Malaysia </li>
                                <li>BNI, Business Network International Queenstown </li>
                                <li>SME Association Malaysia</li>
                                <li>Tax Advisors and Auditors of over 30 Associations and Networks.</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-3">
                    <div class="people-widget">
                        <img src="{{ asset('frontend/img/Queennie Ng.png') }}" alt="">
                        <div>
                            <h3>Queennie Ng</h3>
                            <p>Ng Wei Qian is the tax manager of the Total Tax Services Sdn Bhd. She has over 5 years of
                                tax experience. Her academic achievements included B.A. (Hons) in accounting and finance
                                from the University of Greenwich in London, UK. She is also an HRDF licensed speaker.
                            </p>

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
                        <img src="{{ asset('frontend/img/i1.jpg') }}" alt="">
                        <h3>Tax Investigate & Field Audit</h3>
                        <p>Step 1: Tax Classification</p>
                        <p>Step 2: Determine Tax</p>
                        <p>Step 3: Determine Value</p>
                        <p>Step 4: Posted Account</p>
                    </div>

                </div>
                <div class="col-lg-4 mb-3">
                    <div class="svc-widget">
                        <img src="{{ asset('frontend/img/i2.jpg') }}" alt="">
                        <h3>Transfer Pricing</h3>
                        <p>Transfer pricing (TP) is the setting of transfer prices for transactions relating to sales
                            and purchases of goods, services, intangibles and financing provided between associated
                            persons within a Group.</p>
                    </div>

                </div>
                <div class="col-lg-4 mb-3">
                    <div class="svc-widget">
                        <img src="{{ asset('frontend/img/i3.jpg') }}" alt="">
                        <h3>Business Process Solution</h3>
                        <p>Performing tax health checks is the major requirements for a company long term journey.
                            Focused on determining the accuracy of a income tax returns.</p>
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
                            <div class="test-widget">
                                <p>I've been impressed with Total tax knowledge, which are relevant to my businesses.
                                    Their people are easy to communicate with and explain everything clearly. The
                                    diverse nature of my business activities means that various unconnected issues need
                                    to be considered, but total are able to see the whole picture and advise
                                    accordingly.</p>
                                <div class="testi-user">
                                    <img src="{{ asset('frontend/img/t1.png') }}" alt="">
                                    <div>
                                        <h4>Total Business Holding Sdn Bhd</h4>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="test-widget">
                                <p>Your professionalism in personal taxation and account not only guided us on being
                                    right track of keeping account records correctly, but also provided useful advises
                                    on personal income tax issue.</p>
                                <div class="testi-user">
                                    <img src="{{ asset('frontend/img/t2.png') }}" alt="">
                                    <div>


                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="test-widget">
                                <p> Tax Solutions is very professional, efficient, and knowledgeable. I was able to be
                                    seen quickly and they resolved my extension without delay.</p>
                                <div class="testi-user">
                                    <img src="{{ asset('frontend/img/t3.png') }}" alt="">
                                    <div>
                                        <h4>Iris DOE</h4>
                                        <h6>CEO of MyCompany</h6>


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