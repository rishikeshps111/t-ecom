<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Total Net Group')</title>

    <link href="{{ asset('frontend/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/fontawesome-free-7.0.0-web/css/fontawesome.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/fontawesome-free-7.0.0-web/css/brands.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/fontawesome-free-7.0.0-web/css/solid.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link href="{{ asset('frontend/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/responsive.css') }}" rel="stylesheet">

    @stack('styles')
</head>

<body>
    <header class="header">
        <nav class="navbar navbar-expand-lg">
            <div class="container navTop">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{ asset('frontend/img/logo.png') }}" alt="Total Net Group">
                </a>

                <div id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 btns-nav">
                        <li class="nav-item">
                            <a class="nav-link" target="_blank" rel="noopener" aria-current="page"
                                href="{{ route('login') }}">Sign In</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('contact') }}">Contact Us</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="container">
            <p>Copyright &copy; Total Net Group | Powered by <a href="https://wztech.biz/" target="_blank"
                    rel="noopener">Wztech.biz</a></p>
        </div>
    </footer>

    <script src="{{ asset('frontend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('frontend/js/script.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    @stack('scripts')
</body>

</html>