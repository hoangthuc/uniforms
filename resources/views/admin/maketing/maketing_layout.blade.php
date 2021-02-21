<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Dashboard | Widgets</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('admink/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ekko Lightbox -->
    <link rel="stylesheet" href="{{ asset('admink/plugins/ekko-lightbox/ekko-lightbox.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('admink/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('admink/plugins/summernote/summernote-bs4.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('admink/dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('admink/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')  }}">
    <link rel="stylesheet" href="{{ asset('css/style_admin.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

@include('admin.sidebar_admin')

<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @yield('content')
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <a>Copyright &copy; 2020.</a>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 3.0.5
        </div>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
<!-- jQuery -->
<script src="{{ asset('admink/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('admink/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('admink/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- jQuery UI -->
<script src="{{ asset('admink/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Ekko Lightbox -->
<script src="{{ asset('admink/plugins/ekko-lightbox/ekko-lightbox.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('admink/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')  }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('admink/dist/js/adminlte.min.js') }}"></script>
<!-- Edfitor summernote -->
<script src="{{ asset('admink/plugins/summernote/summernote-bs4.min.js')  }}"></script>
<!-- admin function  custom -->
<script src="{{ asset('js/functions_admin.js')  }}"></script>
<script src="{{ asset('plugins/chart.js/Chart.min.js')  }}"></script>
<script src="{{ asset('js/analytics.js')  }}"></script>
<script>
    var setting = {
        'ajax_url':'{{ url('admin/admin_ajax') }}',
        'token':'{{ csrf_token() }}',
    };
    $('.editor_summernote').summernote(
        {
            placeholder: 'Enter Message',
            tabsize: 2,
            height: 300
        }
    );
    remove_item = function(event){
        var t = $(event).parent().remove();
    }
</script>
@yield('footer_script')
</body>

</html>