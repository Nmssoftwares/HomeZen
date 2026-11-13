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
   <link href="{{ asset('assets/css/custom.css') }}?v=101" rel="stylesheet">

   <style>
      :root {
         --sidebar-width: 260px;
         --header-height: 60px;
         --bg-body: #f6f9ff;
      }

      body {
         font-family: 'Inter', sans-serif;
         background-color: var(--bg-body);
         color: #444444;
         margin: 0;
      }

      #header {
         height: var(--header-height);
         background: #fff;
         box-shadow: 0px 2px 20px rgba(1, 41, 112, 0.1);
         padding-left: 20px;
         padding-right: 20px;
         z-index: 997;
      }

      .logo {
         line-height: 1;
      }

      @media (min-width: 1200px) {
         .logo {
            width: var(--sidebar-width);
         }
      }

      #sidebar {
         position: fixed;
         top: var(--header-height);
         left: 0;
         bottom: 0;
         width: var(--sidebar-width);
         background: #fff;
         padding: 20px 10px;
         box-shadow: 0px 0px 20px rgba(1, 41, 112, 0.1);
         transition: all 0.3s;
         z-index: 996;
         overflow-y: auto;
      }

      .sidebar-nav {
         list-style: none;
         padding: 0;
         margin: 0;
      }

      .sidebar-nav .nav-item {
         margin-bottom: 5px;
      }

      .sidebar-nav .nav-link {
         display: flex;
         align-items: center;
         color: #012970;
         background: #f6f9ff;
         padding: 10px 15px;
         border-radius: 8px;
         font-weight: 600;
         font-size: 14px;
         text-decoration: none;
         transition: 0.2s;
      }

      .sidebar-nav .nav-link i {
         font-size: 18px;
         margin-right: 10px;
         color: #2c6f9b;
      }

      .sidebar-nav .nav-link:hover,
      .sidebar-nav .nav-link:not(.collapsed) {
         color: #2c6f9b;
         background: #e0f2fe;
      }

      #main {
         margin-top: var(--header-height);
         padding: 20px 30px;
         transition: all 0.3s;
      }

      @media (min-width: 1200px) {
         #main {
            margin-left: var(--sidebar-width);
         }
      }

      #footer {
         padding: 20px 30px;
         font-size: 14px;
         background: #fff;
         border-top: 1px solid #cddned;
         margin-top: 40px;
      }

      @media (min-width: 1200px) {
         #footer {
            margin-left: var(--sidebar-width);
         }
      }
   </style>

   @yield('styles')
</head>

<body>

   @include('superadmin.layouts.header')
   @include('superadmin.layouts.sidebar')

   <main id="main" class="main">
      @yield('content')
   </main>

   <footer id="footer" class="footer">
      <div class="copyright text-center text-muted small">
         &copy; {{ date('Y') }} <strong><span>HomeZen</span></strong>. All rights reserved.
      </div>
   </footer>

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

   <script>
      $(document).ready(function() {
         $('.toggle-sidebar-btn').on('click', function() {
            $('body').toggleClass('toggle-sidebar');
            if ($('body').hasClass('toggle-sidebar')) {
               $('#sidebar').css('width', '70px');
               $('#main, #footer').css('margin-left', '70px');
            } else {
               $('#sidebar').css('width', '260px');
               $('#main, #footer').css('margin-left', '260px');
            }
         });
      });
   </script>

   @yield('scripts')
</body>

</html>