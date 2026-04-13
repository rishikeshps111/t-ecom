@extends('admin.layouts.app')
@section('title', 'Settings Management')
@section('style')
    @include('admin.scripts.css')
    <style>
        .main-table-container {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            flex-wrap: wrap;
            gap: 10px
        }

        .settings-button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px 20px;
            /*margin: 10px;*/
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            color: #fff;
            transition: 0.3s;
            white-space: nowrap;
            width: 24%;
        }

        .settings-button:hover {
            color: #fff;
            border-radius: 40px;
        }

        .settings-button i {
            font-size: 18px;
        }

        .settings-button.business {
            background-color: #4caf50;
        }

        /* green */
        .settings-button.subcategory {
            background-color: #2196f3;
        }

        /* blue */
        .settings-button.state {
            background-color: #ff9800;
        }

        /* orange */
        .settings-button.location {
            background-color: #9c27b0;
        }

        /* purple */
        .settings-button.prefix {
            background-color: #f44336;
        }

        /* red */
        .settings-button.financial {
            background-color: #00bcd4;
        }

        /* cyan */
        .settings-button.currency {
            background-color: #795548;
        }

        /* brown */
        .settings-button.company {
            background-color: #607d8b;
        }

        /* gray */

        .settings-button:hover {
            opacity: 0.85;
        }
    </style>
@endsection

@section('content')
    <section class="section dashboard section-top-padding">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <h3>Settings Management</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="main-table-container">
                    {{-- Buttons --}}

                    @role('Super Admin')
                    <a href="{{ route('admin.manage.item') }}" class="settings-button business">
                        <i class="fa-solid fa-briefcase"></i> Item Management
                    </a>
                    @endrole

                    @role('Super Admin')
                    <a href="{{ route('admin.biller-profiles.index') }}" class="settings-button subcategory">
                        <i class="fa-solid fa-layer-group"></i> Biller Profiles
                    </a>
                    @endrole


                    @role('Super Admin')
                    <a href="{{ route('admin.manage.category') }}" class="settings-button business">
                        <i class="fa-solid fa-briefcase"></i> Business Categories
                    </a>
                    @endrole

                    @role('Super Admin')
                    <a href="{{ route('admin.manage.sub.category') }}" class="settings-button subcategory">
                        <i class="fa-solid fa-layer-group"></i> Sub Categories
                    </a>
                    @endrole

                    @role('Super Admin')
                    <a href="{{ route('admin.manage.states') }}" class="settings-button state">
                        <i class="fa-solid fa-flag"></i> States
                    </a>
                    @endrole

                    @role('Super Admin')
                    <a href="{{ route('admin.manage.locations') }}" class="settings-button location">
                        <i class="fa-solid fa-map-marker-alt"></i> Locations
                    </a>
                    @endrole

                    @role('Super Admin')
                    <a href="{{ route('admin.prefixes.index') }}" class="settings-button prefix">
                        <i class="fa-solid fa-key"></i> Master Key Prefixes
                    </a>
                    @endrole

                    @role('Super Admin')
                    <a href="{{ route('admin.financial-years.index') }}" class="settings-button financial">
                        <i class="fa-solid fa-calendar"></i> Financial Years
                    </a>

                    <a href="{{ route('admin.currencies.index') }}" class="settings-button currency">
                        <i class="fa-solid fa-money-bill-wave"></i> Currencies
                    </a>

                    <a href="{{ route('admin.company-types.index') }}" class="settings-button company">
                        <i class="fa-solid fa-building"></i> Service Types
                    </a>
                    @endrole

                    @role('Super Admin')
                    <a href="{{ route('admin.note-types.index') }}" class="settings-button location">
                        <i class="fa-solid fa-layer-group"></i> Note Types
                    </a>
                    @endrole
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    @include('admin.scripts.script')
@endsection