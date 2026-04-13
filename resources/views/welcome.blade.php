<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>login</title>
  <meta content="" name="description">
  <meta content="" name="keywords">


  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">

  <link href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/quill/quill.snow.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/quill/quill.bubble.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/remixicon/remixicon.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/simple-datatables/style.css')}}" rel="stylesheet">

  <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">
  <link href="{{asset('assets/css/custom.css')}}" rel="stylesheet">
  <style>
    .login-box-latest .btn--form-login {
      width: 100%;
      background-color: #023970 !important;
    }
  </style>
</head>

<body>
  <section class="application-login-section">

    <div class="container">
      <div class="row justify-content-center">

        <div class="col-lg-10">
          <div class="login-field-box login-box-latest">

            <img src="{{asset('assets/img/logo.png')}}" alt="">
            <!-- <h4>Admin Login</h4> -->
            <!-- <h4>Total E-Com</h4> -->
            <p>Select to Continue</p>


            <div class="login-panel-btns">
              <ul>
                <li><a href="{{route('admin.login')}}" class="btn--form btn--form-login">Super Admin</a></li>
                <li><a href="{{route('admin.login')}}" class="btn--form btn--form-login">Sub Admin</a></li>
                <li><a href="{{route('admin.login')}}" class="btn--form btn--form-login">Planner</a></li>
                <li><a href="{{route('admin.login')}}" class="btn--form btn--form-login">Staff</a></li>
                <li><a href="{{route('admin.login')}}" class="btn--form btn--form-login">Company User</a></li>
              </ul>
            </div>
            <!--<a href="staff/login" style="text-align:left ; font-size:15px" >Login as Staff</a> -->
          </div>
        </div>
      </div>
    </div>

  </section>



  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{asset('assets/vendor/apexcharts/apexcharts.min.js')}}"></script>
  <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('assets/vendor/chart.js/chart.umd.js')}}"></script>
  <script src="{{asset('assets/vendor/echarts/echarts.min.js')}}"></script>
  <script src="{{asset('assets/vendor/quill/quill.js')}}"></script>
  <script src="{{asset('assets/vendor/simple-datatables/simple-datatables.js')}}"></script>
  <script src="{{asset('assets/vendor/tinymce/tinymce.min.js')}}"></script>
  <script src="{{asset('assets/vendor/php-email-form/validate.js')}}"></script>

  <!-- Template Main JS File -->
  <script src="{{asset('assets/js/main.js')}}"></script>

</body>

</html>