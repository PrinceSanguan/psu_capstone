<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Faculty Portal</title>
    <!-- AdminLTE, Bootstrap, FontAwesome CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    @yield('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    {{-- Header --}}
    @include('faculty.layout.header')

    {{-- Sidebar --}}
    @include('faculty.layout.sidebar')

    {{-- Content Wrapper --}}
    <div class="content-wrapper">
        @yield('content')
    </div>

    {{-- Footer --}}
    @include('faculty.layout.footer')
</div>
@yield('scripts')
</body>
</html>
