<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <script>
      (function() {
         try {
            var t = localStorage.getItem('homezen-theme') || 'light';
            document.documentElement.setAttribute('data-theme', t);
            document.documentElement.setAttribute('data-bs-theme', t);
         } catch (e) {}
      })();
   </script>
   <meta name="csrf-token" content="{{ csrf_token() }}">
   <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
   <meta http-equiv="Pragma" content="no-cache">
   <meta http-equiv="Expires" content="0">
   <title>{{ $title ?? 'Dashboard' }} - HomeZen</title>

   <!-- Bootstrap 5 CSS CDN -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

   <!-- Icons CDN -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
   <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
   <link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet">

   <!-- DataTables CSS -->
   <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
   <link rel="icon" type="image/x-icon" href="/favicon.ico">

   <!-- Universal Custom Enterprise CSS -->
   <link href="{{ asset('assets/css/custom.css') }}?v=1" rel="stylesheet">

   @yield('styles')
</head>

<body>

   <!-- Header -->
   @include('um.layouts.header')

   <!-- Sidebar -->
   @include('um.layouts.sidebar')

   <!-- Main Content -->
   <main id="main" class="main">
      @yield('content')
   </main>
   <!-- Vendor Scripts -->
   <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
   <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
   <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
   <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
   <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
   <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
   <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

   <!-- Sidebar Toggle Script -->
   <script>
      $(document).ready(function() {
         $('.toggle-sidebar-btn').on('click', function(e) {
            e.preventDefault();
            $('body').toggleClass('toggle-sidebar');
         });
      });
   </script>

   @yield('scripts')
</body>

</html>