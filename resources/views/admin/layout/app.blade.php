<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PSU Admin Panel</title>

    <!-- Google Font: Source Sans Pro (or any other) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700">

    <!-- Font Awesome (Icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
          integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Bootstrap 4 or AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    @stack('styles')
    <!-- This lets child pages push additional <style> or CSS links -->
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Header (Navbar) -->
    @include('admin.layout.header')

    <!-- Sidebar -->
    @include('admin.layout.sidebar')

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Main content goes here -->
        <section class="content">
            <div class="container-fluid py-3">
                @yield('content')
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->

    <!-- Footer -->
    @include('admin.layout.footer')

</div>
<!-- ./wrapper -->

<!-- Scripts: jQuery, Bootstrap, AdminLTE, etc. -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

@stack('scripts')
<!-- This lets child pages push additional <script> tags -->
</body>
</html>
