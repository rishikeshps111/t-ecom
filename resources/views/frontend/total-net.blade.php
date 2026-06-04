@extends('frontend.layouts.app')

@section('title', 'Total Net Group')

@section('content')

    <div class="page-baner"
        style="background-image: linear-gradient(0deg, rgba(0, 0, 0, 0.64) 0%, rgba(0, 0, 0, 0.59) 100%), url({{ asset('frontend/img/bn1.png') }});">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h1>Total Net Group</h1>
                </div>
            </div>

        </div>
    </div>
    <section class="sec-padding-2">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5">
                    <div class="about-img">
                        <img src="{{ asset('frontend/img/baner/1.jpg') }}" alt="">
                    </div>

                </div>
                <div class="col-lg-7">
                    <div class="svc-video-right">
                        <h3>Dato Ben Ng</h3>
                        <h6>Founder Of Total Group</h6>
                        <p>Dato Ben Ng has over 34 years of hands-on experience as a visionary, strategic planner and
                            industry leader. Due to his vast knowledge and experience in the fields of company taxation
                            and company law, today he is renowned in the industry as a Business Strategy Advisor. His
                            experience is complimented by his academic achievements, namely an Accounting and Finance
                            Degree from Abertay University, Dundee, Scotland; a Master's Degree in Financial Planning
                            from the Phoenix International University, New Zealand .</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="sec-padding-2 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="achivements-title">
                        <h2>Achievements</h2>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="achivements-main">
                        <ul>
                            <li>Corporate Secretary Certificate, University Malaya</li>
                            <li> Certified Financial Planner (CFP), Institute of Management Studies (IMS)</li>
                            <li>Chartered Financial and Life Practitioners (ChLP) Council, Asia-Pacific Financial
                                Services Association</li>
                            <li>Chartered financial Practitioner ( FChFP) under Financial and Life Practitioners Council
                            </li>
                            <li>Registered Financial Consultant under International Association Of RFC</li>
                            <li>Registered Financial Planner (RFP) under Malaysia Financial Planning Council</li>
                            <li>MFPC Certified RFP Trainer (CRT)</li>
                            <li>Financial Planning Association Malaysia (FPAM), Member</li>
                            <li>Malaysian Association of Company Secretaries (MACS), Member</li>
                            <li>Train the Trainer, Human Resource Development Corporation, Certified Speaker</li>
                            <li>Total Business Academy, Speaker</li>
                            <li> Business of Asia Forum, Annual Special Guest Speaker</li>
                            <li>Asia Metropolitan University EMBA ( Accounting and Finance ) Lecturer</li>
                            <li>《从业务人员到财务顾问》 From sales personnel to financial consultant, 2003</li>
                            <li>《你也犯太税？》 Having problems with taxation? 2010</li>
                            <li>《你税醒了没有？》 Have you realized the taxation mistakes you've made? 2011</li>
                            <li>《 Slap Your Tax 》 , 2012</li>
                            <li>《成为第一名的真相》 To be Number 1, 2014</li>
                            <li>《成为第一名的真相 卷二》 To be Number 1, Vol 2, 2019</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <section class="section-padding bg-dark-blue">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="section-para">
                        <p>Total Business Holdings Sdn Bhd established in the year 1992. At Total Group, we consider it
                            our privilege to assist clients in strategizing their businesses, as well as providing
                            initiatives to diagnosing cross sector industrial problems. We do so by implementing their
                            business success factors, such as effective integration of resources, coherent methodology
                            and a thorough, systematic, disciplined approach in defining business objectives.</p>
                        <p>We provide business advice and support clients through every aspect of business life cycle's
                            transition namely from infant, growth, maturity and divestment stage. We able to diagnose
                            the efficacy of resources utilization using Radar Chart, Tax Audit Software, Consolidation
                            Accounting Software, restructure and re-integrate their resources, re-outline their business
                            strategy, to align client's business objectives.</p>
                        <p>With the aims to transform our client's to be champion in the industry, we form a team of
                            expertise and professionals from corporate secretarial, accounting outsourcing, taxation,
                            digital, branding and training arm to ensure our clients' businesses ahead of new challenges
                            and to equip them with practical knowledge and skills to transform their business. The
                            training modules include various areas of business strategy, taxation, management
                            accounting, corporate law, human resource management, digital marketing, mergers and
                            acquisition strategy, corporate risk management, brand and business financing, fine-tune
                            their organizations for optimal performance that enables them to achieve business objectives
                            and transform value to become agile, forward-thinking organizations that are ready for
                            whatever the future may bring.</p>
                    </div>

                </div>

            </div>
        </div>
    </section>
    <section class="sec-padding-2 ">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="sec-title">
                        <h2>Our Team Spirit</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="owl-carousel owl-theme  team-carousel carousel-btn2">
                        <div class="item">
                            <div class="team-item">
                                <div class="team-images">
                                    <img src="{{ asset('frontend/img/team/1.png') }}" alt="Images">
                                </div>
                                <div class="team-content">
                                    <div class="team-text">
                                        <h5 class="title"><a href="#!">DATO' DR BEN NG</a></h5>
                                        <span class="sub-title">President, Total Business Holdings</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="team-item">
                                <div class="team-images">
                                    <img src="{{ asset('frontend/img/team/2.jpg') }}" alt="Images">
                                </div>
                                <div class="team-content">
                                    <div class="team-text">
                                        <h5 class="title"><a href="#!">DR KHO CHNG GUAN</a></h5>
                                        <span class="sub-title">Managing Director, Total Success Synergy</span>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="team-item">
                                <div class="team-images">
                                    <img src="{{ asset('frontend/img/team/3.jpg') }}" alt="Images">
                                </div>
                                <div class="team-content">
                                    <div class="team-text">
                                        <h5 class="title"><a href="#!">JSON TAN</a></h5>
                                        <span class="sub-title">Executive Director, Total BrandMe</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="team-item">
                                <div class="team-images">
                                    <img src="{{ asset('frontend/img/team/4.jpg') }}" alt="Images">
                                </div>
                                <div class="team-content">
                                    <div class="team-text">
                                        <h5 class="title"><a href="#!">ANGIE NG</a></h5>
                                        <span class="sub-title">Managing Director, Total Corporate Secretary</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="team-item">
                                <div class="team-images">
                                    <img src="{{ asset('frontend/img/team/5.jpg') }}" alt="Images">
                                </div>
                                <div class="team-content">
                                    <div class="team-text">
                                        <h5 class="title"><a href="#!">PEGGY NG</a></h5>
                                        <span class="sub-title">Managing Director, Total Business Outsourcing</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="team-item">
                                <div class="team-images">
                                    <img src="{{ asset('frontend/img/team/6.jpg') }}" alt="Images">
                                </div>
                                <div class="team-content">
                                    <div class="team-text">
                                        <h5 class="title"><a href="#!">TAN CHUN GUAN</a></h5>
                                        <span class="sub-title">Managing Director, Total Tax Services</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="team-item">
                                <div class="team-images">
                                    <img src="{{ asset('frontend/img/team/7.jpg') }}" alt="Images">
                                </div>
                                <div class="team-content">
                                    <div class="team-text">
                                        <h5 class="title"><a href="#!">CELICA CHEW</a></h5>
                                        <span class="sub-title">Managing Director, Total Business Academy</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="team-item">
                                <div class="team-images">
                                    <img src="{{ asset('frontend/img/team/8.jpg') }}" alt="Images">
                                </div>
                                <div class="team-content">
                                    <div class="team-text">
                                        <h5 class="title"><a href="#!">JASON KONG</a></h5>
                                        <span class="sub-title">Managing Director, Total Capital Advisory</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="team-item">
                                <div class="team-images">
                                    <img src="{{ asset('frontend/img/team/9.jpg') }}" alt="Images">
                                </div>
                                <div class="team-content">
                                    <div class="team-text">
                                        <h5 class="title"><a href="#!">KENNY NG</a></h5>
                                        <span class="sub-title">Managing Director, Total Business Consultancy</span>
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