<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{asset('assets/css/styles.css')}}" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

<link href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">

<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">


    <!-- Scripts -->
    @vite(["resources/css/app.css", "resources/js/app.js"])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <style>
        .dataTables_wrapper .dataTables_paginate .paginate_button
        {
            padding: 0px !important;
            margin: 0px !important;
        }
        div.dataTables_wrapper div.dataTables_length select 
        {
            width: 50% !important;
        }

    </style>
</head>
<body>
    @include('layouts.inc.admin-navbar')

    <div id="layoutSidenav">
    @include('layouts.inc.admin-sidebar')

    <div id="layoutSidenav_content">
        <main>
            @yield('content')
        </main>
        @include('layouts.inc.admin-footer')
    </div>
    </div>



    <script src=" {{asset('assets/js/jquery-3.6.0.min.js')}}"></script>
    <script src=" {{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
    <script src=" {{asset('assets/js/scripts.js')}}"></script>

    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
    $(document).ready(function() {
        $("#mySummernote").summernote({
            height: 150,
        });
        $('.dropdown-toggle').dropdown();
    });
</script>
<script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready( function () {
    $('#myDataTable').DataTable();
} );
</script>

@yield('scripts')

</body>
</html>