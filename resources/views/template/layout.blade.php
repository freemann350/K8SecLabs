<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>K8SecLabs</title>
  <!-- plugins:css -->
  <link href="https://cdn.datatables.net/v/bs5/dt-2.0.3/datatables.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ url('vendors/feather/feather.css') }}">
  <link rel="stylesheet" href="{{ url('vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ url('vendors/ti-icons/css/themify-icons.css') }}">
  <link rel="stylesheet" href="{{ url('vendors/typicons/typicons.css') }}">
  <link rel="stylesheet" href="{{ url('vendors/simple-line-icons/css/simple-line-icons.css') }}">
  <link rel="stylesheet" href="{{ url('vendors/css/vendor.bundle.base.css') }}">
  <link rel="stylesheet" href="{{ url('css/main/toastr.css') }}">
  <!-- endinject -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ url('css/main/sweetalert2.min.css') }}">
  <link rel="stylesheet" href="{{ url('css/main/style.css') }}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{ url('img/favicon.png') }}" />
</head>
<body class="sidebar-dark">
  <div class="container-scroller"> 
    <!-- TOPBAR -->
    <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row navbar-dark">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <div class="me-3">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
            <span class="icon-menu"></span>
          </button>
        </div>
        <div>
          <a class="navbar-brand brand-logo" href="{{ route ('Dashboard') }}">
            <img src="{{url('img/ksl.png')}}" alt="logo"/>
          </a>
          <a class="navbar-brand brand-logo-mini" href="{{ route ('Dashboard') }}">
            <img src="{{url('img/favicon.png')}}" alt="logo"/>
          </a>
        </div>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-top"> 
        <ul class="navbar-nav">
          <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
            <h1 class="welcome-text">Hello, <span class="text-black fw-bold">{{isset(Auth::user()->name) ? Auth::user()->name : 'NONAME'}}</span></h1>
          </li>
        </ul>
        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown d-none d-lg-block user-dropdown">
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </nav>
    <!-- TOPBAR END -->
    <div class="container-fluid page-body-wrapper">
      <!-- SIDEBAR -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item nav-category">Your pages</li>
          <li class="nav-item {{ Route::currentRouteName() == 'Dashboard' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route ('Dashboard') }}">
              <i class="menu-icon mdi mdi-grid"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ (Route::currentRouteName() == 'User.editMe' && Auth::user()->id == request()->route('User')) ? 'active' : '' }}" href="{{route('User.editMe')}}">
              <i class="menu-icon mdi mdi-account-box"></i>
              <span class="menu-title">Your information</span>
            </a>
          </li>
          @if (Auth::user()->role == "A")
          <li class="nav-item nav-category">Administration</li>
          <li class="nav-item {{ Route::currentRouteName() == 'Users.index' ? 'active' : '' }}">
            <a class="nav-link" href="{{route('Users.index')}}">
              <i class="menu-icon mdi mdi-account-supervisor"></i>
              <span class="menu-title">User management</span>
            </a>
          </li>
          @endif
          @if (Auth::user()->role != "T")
          <li class="nav-item nav-category">Lecturering</li>
          <li class="nav-item {{ str_contains(Route::currentRouteName(),'Categories.') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" data-bs-toggle="collapse" href="#categories" aria-expanded="false" aria-controls="categories">
              <i class="menu-icon mdi mdi-shape"></i>
              <span class="menu-title">Categories</span>
            </a>
            <div class="collapse {{ str_contains(Route::currentRouteName(),'Categories.') ? 'show' : '' }}" id="categories">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{ route ('Categories.index') }}">List of Categories</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route ('Categories.create') }}">New Category</a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item {{ str_contains(Route::currentRouteName(),'Definitions.') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" data-bs-toggle="collapse" href="#definitions" aria-expanded="false" aria-controls="definitions">
              <i class="menu-icon mdi mdi-file-cabinet"></i>
              <span class="menu-title">Definitions</span>
            </a>
            <div class="collapse {{ str_contains(Route::currentRouteName(),'Definitions.') ? 'show' : '' }}" id="definitions">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{ route('Definitions.index') }}">Your Definitions</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('Definitions.catalog') }}">Definition Catalog</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('Definitions.create') }}">New Definition</a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item {{ str_contains(Route::currentRouteName(),'Environments.') ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" data-bs-toggle="collapse" href="#environments" aria-expanded="false" aria-controls="definitions">
              <i class="menu-icon mdi mdi-view-grid"></i>
              <span class="menu-title">Environments</span>
            </a>
            <div class="collapse {{ str_contains(Route::currentRouteName(),'Environments.') ? 'show' : '' }}" id="environments">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{ route ('Environments.index') }}">Your Environments</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ route('Environments.create') }}">New Environment</a></li>
              </ul>
            </div>
          </li>
          @endif
          <li class="nav-item nav-category">Training</li>
          <li class="nav-item {{ str_contains(Route::currentRouteName(),'EnvironmentAccesses.') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route ('EnvironmentAccesses.index') }}">
              <i class="menu-icon mdi mdi-book-variant"></i>
              <span class="menu-title">Environment History</span>
            </a>
          </li>
          <li class="nav-item nav-category"></li>
          <li class="nav-item">
            <a class="nav-link" id="logout" href="#">
              <i class="menu-icon mdi  mdi-logout"></i>
              <span class="menu-title">Logout</span>
            </a>
          </li>
        </ul>
      </nav>
      <!-- SIDEBAR END -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-sm-12">
              <div class="home-tab">
                @yield('main-content')
              </div>
            </div>
          </div>
        </div>
        <!-- END content-wrapper -->
        <!-- FOOTER -->
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">K8SecLabs - Kubernetes based Cyber Range Control Panel</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Copyright © 2024. All rights reserved. IPL@EI</span>
          </div>
        </footer>
        <!-- END FOOTER -->
      </div>
      <!-- END main-panel -->
    </div>
    <!-- END page-body-wrapper -->
  </div>

  <!-- plugins:js -->
  <script src="{{ url('vendors/js/vendor.bundle.base.js') }} "></script>
  <!-- inject:js -->
  <script src="{{ url('js/main/off-canvas.js') }} "></script>
  <script src="{{ url('js/main/hoverable-collapse.js') }}"></script>
  <script src="{{ url('js/main/template.js') }}"></script>
  <script src="https://cdn.datatables.net/v/bs5/dt-2.0.3/datatables.min.js"></script>
  <script src="{{ url('js/main/jquery.min.js') }}"></script>
  <script src="{{ url('js/main/toastr.min.js') }}"></script>
  <script src="{{ url('js/main/sweetalert2@11.js') }}"></script>
  @if (str_contains(Route::currentRouteName(),'.index') || str_contains(Route::currentRouteName(),'.catalog') || Route::currentRouteName() =='Environments.show')
    <script>
        let table = new DataTable('#dt', {});
        let table1 = new DataTable('#dt1', {});
    </script>
  @endif

  @include('template/scripts/swal')

  @include('template/scripts/toastr')

  @if (Route::currentRouteName() == 'Environments.create')
  <script>
  function appendInput(baseDivName) {
    const baseDiv = document.getElementById(baseDivName);
    const baseInput = document.createElement('div');
    baseInput.classList.add('dynamic-input');

    if (baseDivName === 'variables') {
      baseInput.innerHTML = `
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text">Type</span>
        </div>
        <select class="form-select fix-height" name="type[]" onchange="handleTypeChange(this)">
          <option value="string" selected>String</option>
          <option value="number">Number</option>
          <option value="rand">Random Number</option>
          <option value="flag">Flag (empty value creates random sha256 flags)</option>
        </select>
      </div>
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text">Variable</span>
        </div>
        <input type="text" class="form-control fix-height" name="variable[]">
        <div class="input-group-prepend value-label">
          <span class="input-group-text">Value</span>
        </div>
        <input type="text" class="form-control fix-height value-input" name="value[]">
        <button type="button" class="btn btn-danger removeInput fix-height"><i class="ti-trash removeInput"></i></button>
      </div>
      `;
    }

    baseDiv.appendChild(baseInput);
  }

  function handleTypeChange(selectElement) {
    const parentDiv = selectElement.closest('.dynamic-input');
    const valueLabel = parentDiv.querySelector('.value-label span');
    const valueInput = parentDiv.querySelector('.value-input');

    if (selectElement.value === 'rand') {
      valueLabel.innerText = 'Min';
      valueInput.name = 'min[]';
      valueInput.placeholder = 'Min';

      const maxInput = document.createElement('input');
      maxInput.type = 'text';
      maxInput.classList.add('form-control', 'fix-height', 'max-input');
      maxInput.name = 'max[]';
      maxInput.placeholder = 'Max';

      const maxLabel = document.createElement('div');
      maxLabel.classList.add('input-group-prepend', 'max-label');
      maxLabel.innerHTML = '<span class="input-group-text">Max</span>';

      valueInput.insertAdjacentElement('afterend', maxInput);
      valueInput.insertAdjacentElement('afterend', maxLabel);
    } else {
      valueLabel.innerText = 'Value';
      valueInput.name = 'value[]';
      valueInput.placeholder = '';

      const maxLabel = parentDiv.querySelector('.max-label');
      const maxInput = parentDiv.querySelector('.max-input');

      if (maxLabel) maxLabel.remove();
      if (maxInput) maxInput.remove();
    }
  }

  document.addEventListener('click', function(event) {
    if (event.target.classList.contains('removeInput')) {
      event.target.closest('.dynamic-input').remove();
    }
  });
</script>
  @endif

  @if (isset($json))
      @include('template/scripts/prettyJson')
  @endif
</body>
</html>
