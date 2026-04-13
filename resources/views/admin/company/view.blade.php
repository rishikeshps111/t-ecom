@extends('admin.layouts.app')
@section('content')

    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="company-preview-container">
                    <h6>Company ID : <span style="color: red;">{{$company->company_code ?? 'N/A'}}</span></h6>
                    <div class="row preview-col-reverse">
                        <div class="col-lg-12 mb-2">
                            <img src="{{asset('assets/img/logo.png')}}" alt="" class="" style="width:200px">
                        </div>
                        <div class="col-lg-12 mb-2">
                            <div class="company-preview-info">
                                <h3>{{$company->company_name ?? 'N/A'}}</h3>
                                <!-- <h5>₹ 20 lakh <span>per cent</span></h5> -->
                                <hr>
                                <ul>
                                    <li>Company Code: <span>{{ $company->company_code ?? 'N/A'}}</span></li>
                                    <li>Company Type: <span>{{ $company->company_type ?? 'N/A'}}</span></li>
                                    <li>Industry: <span>{{ $company->industry ?? 'N/A'}}</span></li>
                                    <li>Status: <span>{{ $company->status ?? 'N/A'}}</span></li>
                                    <li>SSM Number: <span>{{ $company->ssm_number ?? 'N/A'}}</span></li>
                                    <li>Incorporation Date: <span>{{ $company->incorporation_date ?? 'N/A'}}</span></li>
                                    <li>Commencement Date: <span>{{ $company->commencement_date ?? 'N/A'}}</span></li>
                                    <li>Paid Up Capital: <span>{{ $company->paid_up_capital ?? 'N/A'}}</span></li>
                                    <li>Authorized Capital: <span>{{ $company->authorized_capital ?? 'N/A'}}</span></li>
                                    <li>Employees: <span>{{ $company->employees ?? 'N/A'}}</span></li>
                                    <li>Email: <span>{{ $company->email_address ?? 'N/A'}}</span></li>
                                    <li>Mobile: <span>{{ $company->mobile_no ?? 'N/A'}}</span></li>
                                    <li>Website: <span>{{ $company->company_website ?? 'N/A'}}</span></li>
                                </ul>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="company-view-dt">
                                            <h5>Registered Address</h5>
                                            <p>
                                                {{ $company->address->address1 ?? 'N/A'}}<br>
                                                {{ $company->address->address2 ?? 'N/A'}}<br>
                                                {{ $company->address->city ?? 'N/A'}},
                                                {{ $company->address->state ?? 'N/A'}} -
                                                {{ $company->address->postcode ?? 'N/A'}}<br>
                                                {{ $company->address->country ?? 'N/A'}}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="company-view-dt">
                                            <h5>Business Address</h5>
                                            <p>
                                                {{ $company->address->business_address1 ?? 'N/A'}}<br>
                                                {{ $company->address->business_address2 ?? 'N/A'}}<br>
                                                {{ $company->address->business_city ?? 'N/A'}},
                                                {{ $company->address->business_state ?? 'N/A'}} -
                                                {{ $company->address->business_postcode ?? 'N/A'}}<br>
                                                {{ $company->address->business_country ?? 'N/A'}}
                                            </p>
                                        </div>
                                    </div>
                                </div>



                            </div>

                        </div>
                    </div>

                    @php $director = $company->directors->first(); @endphp
                    <div class="row">
                        <div class="col-lg-6 col-right-0 mb-2">
                            <div
                                class="company-info-second-section-right-box company-preview-bottom-box company-preview-bottom-box-cs">
                                <h5>Director(s) Information</h5>
                                <p> Fullname: {{ $director->name ?? '' }}</p>
                                <small>{{ $director->email ?? '' }} , {{ $director->mobile ?? '' }}</small>
                                <p>Identification Number : {{$director->identification_number ?? 'N/A'}}</p>
                                <p><strong>Nationality:</strong> {{ $director->nationality ?? 'N/A'}}</p>
                                <p><strong>DOB:</strong> {{ $director->date_of_birth ?? 'N/A'}}</p>
                                <p><strong>Position:</strong> {{ $director->position ?? 'N/A'}}</p>
                                <p><strong>Appointment Date:</strong> {{ $director->appointment_date ?? 'N/A'}}</p>
                                <p><strong>Email:</strong> {{ $director->email ?? 'N/A'}}</p>
                                <p><strong>Mobile:</strong> {{ $director->mobile ?? 'N/A'}}</p>
                                <p><strong>Address:</strong> {{ $director->address ?? 'N/A'}}</p>
                            </div>
                        </div>
                        @php $shareholder = $company->shareholders->first(); @endphp
                        <div class="col-lg-6 col-left-0 mb-2">
                            <div
                                class="company-info-second-section-right-box company-preview-bottom-box line-height-p company-preview-bottom-box-cs">
                                <h5>Shareholder(s) Information</h5>
                                <p>Fullname: {{$shareholder->name ?? ''}}</p>
                                <p>Registration Number : {{$shareholder->identification ?? 'N/A'}}</p>
                                <p><strong>Type:</strong>
                                    {{ !empty($shareholder->type) ? ucfirst($shareholder->type) : 'N/A' }}
                                </p>
                                <p><strong>Nationality:</strong> {{ $shareholder->nationality ?? 'N/A'}}</p>
                                <p><strong>Shares:</strong> {{ $shareholder->shares ?? 'N/A'}}</p>
                                <p><strong>Ownership (%):</strong> {{ $shareholder->ownership ?? 'N/A'}}</p>
                                <p><strong>Share Class:</strong> {{ $shareholder->share_class ?? 'N/A'}}</p>
                            </div>
                        </div>
                        <div class="col-lg-6 col-right-0 mb-2">
                            <div
                                class="company-info-second-section-right-box company-preview-bottom-box line-height-p company-preview-bottom-box-cs">
                                <h5>Announcements</h5>
                                <p>No Data</p>
                            </div>
                        </div>
                        <div class="col-lg-6 col-left-0 mb-2">
                            <div
                                class="company-info-second-section-right-box company-preview-bottom-box company-preview-bottom-box-cs">
                                <h5>Documents</h5>
                                <p>No Data</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection