<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <script>
   (function () {
      try {
         var t = localStorage.getItem('oms-theme') || 'light';
         document.documentElement.setAttribute('data-theme', t);
         document.documentElement.setAttribute('data-bs-theme', t);
      } catch (e) {}
   })();
</script>
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
   <meta http-equiv="Pragma" content="no-cache">
   <meta http-equiv="Expires" content="0">
   <title>{{ $title }} - OMS</title>

   <!-- Vendor CSS Files -->
   <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
   <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
   <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
   <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
   <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
   <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">

   <!-- Main CSS -->
   <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
   <link href="{{ asset('assets/css/loader.css') }}" rel="stylesheet">
   <!-- Custom CSS -->
   <link href="{{ asset('assets/css/custom.css') }}?v=100" rel="stylesheet">
   <link href="{{ asset('assets/css/theme.css') }}?v={{ filemtime(public_path('assets/css/theme.css')) }}" rel="stylesheet">
   <link href="{{ asset('assets/css/loader.css') }}" rel="stylesheet">

   <!-- DataTables Bootstrap 5 CSS -->
   <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
   <link rel="icon" type="image/x-icon" href="/favicon.ico?v=5">
   @yield('styles')
</head>

<body>



   @include('partials.loader')

   {{-- header removed: logo, language and user now live in the sidebar --}}

   @include('superadmin.layouts.sidebar')

   <main id="main" class="main">
      @yield('content')
   </main>

   <footer id="footer" class="footer">
      <div class="copyright d-flex align-items-center justify-content-center gap-0">
         &copy; Copyright
         <img src="{{ asset('assets/img/offnexa-logo-v2.png') }}" alt="NMSoffice" style="height:40px;">
      </div>
   </footer>

   <!-- jQuery -->
   <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

   <!-- Bootstrap -->
   <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

   <!-- DataTables -->
   <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
   <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
   <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
   <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
   <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
   <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

   <!-- Other Vendor JS -->
   <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
   <script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
   <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
   <script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>

   <!-- Remove if not using TinyMCE -->
   {{-- <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script> --}}

   <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

   <!-- NiceAdmin Main JS -->
   <script src="{{ asset('assets/js/main.js') }}"></script>
   <!-- Custom Scripts -->
   <script src="{{ asset('assets/js/custom.js') }}"></script>

   <!-- Page Scripts -->
   @yield('scripts')

<script src="{{ asset('assets/js/theme.js') }}?v={{ filemtime(public_path('assets/js/theme.js')) }}"></script>
</body>

</html>