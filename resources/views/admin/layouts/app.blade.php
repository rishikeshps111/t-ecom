<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> @yield('title', 'Dashboard')</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">


    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/brands.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fontawesome/css/solid.css') }}">

    @yield('style')

    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/dashboard.css') }}" rel="stylesheet">

</head>

<body class="body-img-background">

    @include('admin.layouts.header')

    @include('admin.layouts.sidebar')

    <main id="main" class="main">

        <!-- <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div> -->

        @yield('content')
        <footer id="footer" class="footer">
            <div class="copyright">
                &copy; Copyright <strong><span>2026</span></strong>. All Rights Reserved
            </div>

        </footer><!-- End Footer -->

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" id="messageToastContainer" style="z-index: 1100;">
    </div>

    <audio id="notificationSound">
        <source src="{{ asset('sounds/sound.mp3') }}" type="audio/mpeg">
    </audio>


    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/quill/quill.js') }}"></script>
    <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/calendar.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.0/dist/chart.min.js"></script>
    <script src="https://kit.fontawesome.com/111740f521.js" crossorigin="anonymous"></script>

    @yield('scripts')


    @php
        $unreadItemLists = unreadMessagesByUser();

        $unreadMessages = $unreadItemLists
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'message' => \Illuminate\Support\Str::limit($item->message, 80),
                    'url' => route('admin.messages.conversation', $item->chat->id),
                    'user' => $item->chat->user->name ?? 'Unknown',
                ];
            })
            ->values();
    @endphp

    <script>
        window.unreadMessages = @json($unreadMessages);
    </script>
    <script>
        $(document).ready(function () {

            // function to show unread messages as toast
            function showUnreadToasts() {
                if (!window.unreadMessages || window.unreadMessages.length === 0) return;

                let notificationSound = document.getElementById('notificationSound');

                window.unreadMessages.forEach((msg, index) => {
                    // skip if toast already exists
                    if ($('#toast_' + msg.id).length) return;

                    setTimeout(() => {
                        let toastId = 'toast_' + msg.id;

                        let toastHtml = `
    <div id="${toastId}" class="toast shadow-lg mb-2 p-0" role="alert" style="min-width: 280px; border-radius: 12px; overflow: hidden; font-family: 'Segoe UI', sans-serif;">
        <div style="background-color: #0d6efd; color: #fff; display: flex; align-items: center; justify-content: space-between; padding: 0.5rem 1rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <i class="fa-regular fa-message"></i>
                <strong style="font-size: 0.9rem;">${msg.user}</strong>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div style="padding: 0.75rem 1rem; font-size: 0.85rem; color: #333; background-color: #f8f9fa;">
            <div class="mb-2" style="white-space: pre-wrap;">${msg.message}</div>
            <a href="${msg.url}" class="btn btn-primary btn-sm w-100" style="border-radius: 6px; font-size: 0.8rem;">
                View Conversation
            </a>
        </div>
    </div>
`;

                        $('#messageToastContainer').append(toastHtml);

                        let toast = new bootstrap.Toast(document.getElementById(toastId), {
                            autohide: true,
                            delay: 8000
                        });

                        toast.show();
                        notificationSound.play().catch(() => { });
                    }, index * 400); // stagger effect
                });
            }

            // show toasts immediately on page load
            showUnreadToasts();

            // repeat every 1 minute
            setInterval(showUnreadToasts, 60000);
        });
    </script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('logoutBtn').addEventListener('click', function () {
            Swal.fire({
                title: 'Are you sure?',
                text: "You will be logged out!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, logout!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                }
            });
        });
    </script>
</body>

</html>