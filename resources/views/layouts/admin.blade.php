<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">  
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="shortcut icon" href="{{ asset('dist/img/fan_arch_140x.webp') }}"> 
  <!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}" id="_token">
<title>Fan Arch Partners</title>    

{{-- data-tables --}}
<link  rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">

<link  rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">

<link  rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}" defer></script>
<!-- Fonts -->
<link rel="dns-prefetch" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
<!-- Styles -->
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<link href="{{ asset('css/custom.css') }}" rel="stylesheet">

  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
 <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

  <link rel="stylesheet" href=" {{ asset('dist/css/adminlte.min.css') }}">
  <link rel="stylesheet" href=" {{ asset('redactor/redactor.css') }}">

  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link href="https://unpkg.com/izitoast/dist/css/iziToast.min.css" rel="stylesheet" type="text/css" />
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  <div class="lds-spinner" style=" display: none;"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="dropdown-item" href="{{ route('logout') }}"
        onclick="event.preventDefault();
        document.getElementById('logout-form').submit();">
       <span> {{ __('Logout') }}</span>
        <i class="fas fa-sign-out-alt"></i>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>		
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/admin/home" class="brand-link"> 
      <img src="{{ asset('dist/img/fan_arch_140x.webp') }}" alt="AdminLTE Logo" class="brand-image elevation-3"
           style="opacity: .8;border-radius:8px;"> 
      <span class="brand-text font-weight-light">Fanarch Partners</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          @if(Auth::user()->image !==null)
          <img src="{{ asset('/public/images/'.Auth::user()->image) }}" alt="User Image">
          @else
          <img src="{{ asset('dist/img/admin.png') }}" class="img-circle elevation-2" alt="User Image">
          @endif
        </div>
        <div class="info">
          <a href="/admin/home" class="d-block">{{ Auth::user()->name }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <li class="nav-item">
            <a href="/admin/orders" class="nav-link">  
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Orders
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/admin/incentive" class="nav-link">  
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Incentive Page
              </p>
            </a>
          </li>
          @if(Auth::user()->is_admin == 1)
          <li class="nav-item">
            <a href="/admin/register" class="nav-link">
              <i class="fa fa-registered" aria-hidden="true" style=" font-size: 19px; padding: 4px; "></i>
              <p>
                Register
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/admin/vendors" class="nav-link">  
              <i class="fa fa-user" aria-hidden="true" style=" font-size: 19px; padding: 4px; "></i>
              <p>
                Clients
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/admin/profit" class="nav-link">    
              <i class="fas fa-wallet" aria-hidden="true" style="padding: 4px; "></i>
              <p>
                Profit
              </p> 
            </a>
          </li>
          <li class="nav-item">
            <a href="/admin/announce" class="nav-link">    
              <i class="fas fa-bullhorn" aria-hidden="true" style="padding: 4px; "></i>
              <p>
                Announcements  
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/admin/email" class="nav-link">    
              <i class="fas fa-envelope-square" aria-hidden="true" style="padding: 4px; "></i>
              <p>
                Email System  
              </p>
            </a>
          </li>
          @endif
          <li class="nav-item">
            <a href="/admin/account" class="nav-link">    
              <i class="fa fa-cog" aria-hidden="true" style="padding: 4px; "></i>
              <p>
                Account Settings
              </p>
            </a>
          </li>
          {{-- <li class="nav-item">
            <a href="/chatify" class="nav-link">  
              <i class="nav-icon fas fa-chart-pie"></i> 
              <p>
                Chat
              </p>
            </a>
          </li> --}}
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    @if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif 

     @yield('content')
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2014-2020 <a href="#">Fanarch</a>.</strong>
    All rights reserved.
    {{-- <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.0
    </div> --}}
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->


<script src="https://code.jquery.com/jquery-3.5.1.js" type="text/javascript"></script>

  {{-- data-tables --}}
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js" defer="defer"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js" defer="defer"></script>


<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js" defer="defer"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js" defer="defer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" defer="defer"> </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" defer="defer"> </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" defer="defer"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js" defer="defer"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js" defer="defer"></script>

<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>  

{{-- <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> --}}

<script src="{{ asset('redactor/redactor.min.js') }}"></script>   
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
      tinymce.init({
        selector: '#content22,#content44'
      });
// $R('#content22', { plugins: ['alignment','definedlinks', 'fontcolor', 'fontfamily', 'fontsize'],
//                     fontcolors: [
//                         '#000', '#333', '#555', '#777', '#999', '#aaa',   
//                         '#bbb', '#ccc', '#ddd', '#eee', '#f4f4f4'
//                     ]
//                 });


</script>
<script src="{{ asset('js/custom.js') }}"></script>
@stack('script')
<script src="https://kit.fontawesome.com/39b3121059.js" crossorigin="anonymous"></script> 

<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>

<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('dist/js/pages/dashboard.js') }}"></script>
<script>
      var onloadCallback = function() {
        grecaptcha.render('html_element', {
          'sitekey' : '{{ config('services.recaptcha.sitekey') }}'   
        });
      };
</script>
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
async defer>
</script> 
<style>
@media only screen and (max-width:767px){ 
  body, * {
    font-size: 98% !important;
}
}
</style>
</body>
</html>
