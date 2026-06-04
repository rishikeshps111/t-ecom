@extends('frontend.layouts.app')

@section('title', 'Total Net Group')

@section('content')
    <div class="baner">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="baner-caption">
                        <h1>One Stop Solution Provider <br>
                            To be number 1 </h1>
                        <p>Total provides business advice and support clients through every aspect of business life
                            cycle's transition namely from infant, growth, maturity and divestment stage. We able to
                            diagnose the efficacy of resources utilization using Radar Chart, Tax Audit Software,
                            Consolidation Accounting Software, restructure and re-integrate their resources, re-outline
                            their business strategy, to align client's business objectives.</p>
                        <a href="{{ route('total.net') }}">Read More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="sec-padding-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <div class="service-widget">
                        <img src="{{ asset('frontend/img/s1.jpg') }}" alt="Secretarial services">
                        <div>
                            <h3>SECRETARIAl services</h3>
                            <a href="{{ route('service.one') }}">Read More</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-3">
                    <div class="service-widget">
                        <img src="{{ asset('frontend/img/s2.jpeg') }}" alt="Accounting outsourcing">
                        <div>
                            <h3>ACCOUNTING Outsourcing</h3>
                            <a href="#">Read More</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-3">
                    <div class="service-widget">
                        <img src="{{ asset('frontend/img/s3.jpg') }}" alt="Taxation services">
                        <div>
                            <h3>TAXation SERVICES</h3>
                            <a href="{{ route('service.two') }}">Read More</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-3">
                    <div class="service-widget">
                        <img src="{{ asset('frontend/img/s4.jpg') }}" alt="Business financing">
                        <div>
                            <h3>Business financing</h3>
                            <a href="{{ route('service.three') }}">Read More</a>
                        </div>
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
                        <h2>Our References</h2>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                @foreach (range(1, 18) as $logo)
                    @php
                        $extension = in_array($logo, [9, 10, 11, 12, 14, 17, 18], true) ? 'png' : 'jpg';
                    @endphp
                    <div class="col-lg-2 mb-3">
                        <div class="logfolio">
                            <img src="{{ asset("frontend/img/logos/{$logo}.{$extension}") }}" alt="Reference logo {{ $logo }}">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <div class="cta-box" id="contact">
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