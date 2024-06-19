<!DOCTYPE html>
<html lang="en">
<head style="height:100%">
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>K8SecLabs</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ url('vendors/feather/feather.css') }}">
  <link rel="stylesheet" href="{{ url('vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ url('vendors/css/vendor.bundle.base.css') }}">
  <!-- endinject -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ url('css/main/style.css') }}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{ url('img/favicon.png') }}" />
</head>
<body style="min-height: 100%;">
  <div class="content-wrapper">
    <div class="home-tab">
      @yield('main-content')
    </div>
  </div>

  <!-- plugins:js -->
  <script src="{{ url('vendors/js/vendor.bundle.base.js') }} "></script>
  <!-- inject:js -->
  <script src="{{ url('js/main/template.js') }}"></script>
  <script src="{{ url('js/main/jquery.min.js') }}"></script>
</body>
</html>
