<!doctype html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
     <!-- CSRF Token -->
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    
    <link rel="shortcut icon" type="image/png" href="{{ asset('/image/nabdLogo.png')}}" />
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('../assets/css/styles.min.css')}}" />
    <!--description du site-->
    <meta name="description" content="demande et don du sang en Algérie">
    <!--les mots clé-->
    <meta name="keywords" content="don du sang, demande du sang, donneur, demandeur, sang, centre de transfusion sanguine ">
    <title>
        {{ config('app.name') }}
    </title>
    <style>
      .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        border: none !important;
        background: transparent !important;
      }

    </style>
 
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    @include('layouts.public.navbar')
    
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
        @yield('content')
        @include('layouts.public.footer')
    </div>
  </div>
  <script src="{{ asset('/assets/libs/jquery/dist/jquery.min.js') }}"></script>

  <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/js/sidebarmenu.js') }}"></script>
  <script src="{{ asset('assets/js/app.min.js') }}"></script>
  <script src="{{ asset('assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
  <script src="{{ asset('assets/libs/simplebar/dist/simplebar.js') }}"></script>
  <script src="{{ asset('assets/js/dashboard.js') }}"></script>
  <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
  <script src="{{ asset('/assets/js/data-table.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>