@extends('frontend.layouts.app')

@section('title', 'Total Net Group')

@section('content')

    <div class="page-baner"
        style="background-image: linear-gradient(0deg, rgba(0, 0, 0, 0.64) 0%, rgba(0, 0, 0, 0.59) 100%), url({{ asset('frontend/img/baner/2.jpg') }});">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h1>Structural Finance</h1>
                    <p>Total Structural Solutions Sdn Bhd is a renowned financial consulting firm that provides
                        comprehensive financial strategy and loan consulting services.</p>
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
                            src="//www.youtube.com/embed/6W8vMrDaMLc?autoplay=1&amp;mute=1&amp;enablejsapi=1&amp;rel=0&amp;modestbranding=1"
                            frameborder="0" allowfullscreen="allowfullscreen"></iframe>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="svc-video-right">
                        <h3> Lennon Chia</h3>
                        <p>With a team of experienced professionals and a track record of success, Total Structural
                            Solutions Sdn Bhd is committed to helping businesses achieve their financial goals. The
                            company's services include financial planning, loan structuring, credit analysis, risk
                            management, and more. Total Structural Solutions Sdn Bhd is dedicated to providing
                            customized solutions that meet the unique needs of each client.

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
                            <h3>Lennon Chia</h3>
                            <p>Your Trusted Partner for Financial Success!</p>
                            <p>Lennon Chia, a Director at Total Structural Solutions Sdn Bhd, brings over 10 years of
                                expertise in corporate finance, financial analysis, and SME consulting. With an
                                impressive track record in delivering top-tier solutions, Lennon is known for his
                                dedication, reliability, and commitment to excellence. His deep understanding of the
                                banking industry and unwavering passion for helping SMEs thrive sets him apart. Lennon's
                                analytical prowess, coupled with an IEMBA in accounting and finance, ensures tailored
                                strategies that drive tangible results. Experience the difference of working with a
                                trusted advisor. Contact Lennon Chia today and unlock your business's true potential</p>

                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-3">
                    <div class="people-widget">
                        <img src="{{ asset('frontend/img/Vincent Tang.png') }}" alt="">
                        <div>
                            <h3>Vincent Tang
                            </h3>
                            <p>Associate Director, Total Structural Solutions Sdn Bhd</p>
                            <p>
                                Vincent Tang is an Associate Director at Total Structural Solutions Sdn Bhd, with over
                                10 years of experience in the banking industry. With expertise in corporate finance,
                                business consulting, financial analysis, SME consulting, and business credit paper
                                writing, Vincent has consistently ranked in the top 3 for SME sales and has been
                                recognized as a skilled business professional. Vincent is certified as a Business Credit
                                Professional (BCP) accredited by AICB and holds an IEMBA majoring in accounting and
                                finance. Vincent is committed to providing high-quality solutions for his clients and is
                                known for his dedication, reliability, and excellence in the industry.
                            </p>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <section class="sec-padding-2">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-4 mb-3">
                    <div class="svc-widget">
                        <img src="{{ asset('frontend/img/i7.jpg') }}" alt="">
                        <h3>Business Financing</h3>

                    </div>

                </div>
                <div class="col-lg-4 mb-3">
                    <div class="svc-widget">
                        <img src="{{ asset('frontend/img/i8.jpg') }}" alt="">
                        <h3>Financial Strategist</h3>

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
                                <p>Vincent and the team have been invaluable in providing professional financing advice
                                    and solving my needs. Their contributions to our business are highly appreciated.
                                </p>
                                <div class="testi-user">

                                    <div>
                                        <h4>Mr Koay - David Gurupatham & Koay (DGK)</h4>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="test-widget" style="min-height: unset !important;">
                                <p>Through their expert guidance and personalized approach, TOTAL team helped me to
                                    develop a solid financial plan that aligned with my long-term goals. They were
                                    responsive to my inquiries, promptly addressed any concerns, and were always there
                                    to provide guidance and support.
                                </p>
                                <div class="testi-user">

                                    <div>
                                        <h4>Daniel Yee - Marckem Sdn Bhd </h4>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="test-widget" style="min-height: unset !important;">
                                <p>
                                    经过Lennon 的专业数据分析，建议，未来筹备资金等等 <br>

                                    让我们filken
                                    可以更安稳的发展。因为我们会知道我们的资金分配制度是否在正确的方向，也很清楚我们还有多少资金空间可以使用，这的确使我们可以更安心的使用，规划我们的资金链。 <br>

                                    谢谢你,Lennon ，合作愉快！
                                </p>
                                <div class="testi-user">

                                    <div>
                                        <h4>Zack Liew - Filken Sdn Bhd</h4>

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