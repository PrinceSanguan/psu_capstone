@include('admin.layout.header')

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="{{ route('faculty.dashboard') }}" class="brand-link">
                <i class="fas fa-graduation-cap brand-image elevation-3"></i>
                <span class="brand-text font-weight-light">PSU Faculty Portal</span>
            </a>

            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <i class="fas fa-user-circle img-circle elevation-2 text-light fa-2x"></i>
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">{{ Auth::user()->name }}</a>
                    </div>
                </div>

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ route('faculty.dashboard') }}" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('faculty.classes.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-chalkboard"></i>
                                <p>My Classes</p>
                            </a>
                        </li>
                        <li class="nav-header">CLASS MANAGEMENT</li>
                        <li class="nav-item">
                            <a href="#" class="nav-link active">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>
                                    Syllabi
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('faculty.syllabus.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>View Uploaded Syllabi</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Upload Syllabus</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('faculty.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('faculty.classes.index') }}">My Classes</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('faculty.classes.details', ['sectionId' => $section->id, 'subjectId' => $subject->id, 'schoolYear' => $schoolYear, 'semester' => $semester]) }}">{{ $subject->code }}</a></li>
                                <li class="breadcrumb-item active">Upload Syllabus</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Class Information</h3>
                                </div>
                                <div class="card-body box-profile">
                                    <ul class="list-group list-group-unbordered mb-3">
                                        <li class="list-group-item">
                                            <b>Section</b> <a class="float-right">{{ $section->name }}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Subject</b> <a class="float-right">{{ $subject->name }}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Code</b> <a class="float-right">{{ $subject->code }}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>School Year</b> <a class="float-right">{{ $schoolYear }}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Semester</b> <a class="float-right">{{ $semester }}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            @if(isset($existingSyllabus))
                            <div class="card card-warning">
                                <div class="card-header">
                                    <h3 class="card-title">Existing Syllabus</h3>
                                </div>
                                <div class="card-body">
                                    <p><strong>Filename:</strong> {{ $existingSyllabus->original_filename }}</p>
                                    <p><strong>Uploaded:</strong> {{ date('F d, Y h:i A', strtotime($existingSyllabus->upload_timestamp)) }}</p>
                                    <div class="text-center">
                                        <a href="{{ route('faculty.syllabus.download', ['id' => $existingSyllabus->id]) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                    <div class="mt-3">
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle"></i> Uploading a new syllabus will replace the existing one.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="col-md-8">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Upload Syllabus</h3>
                                </div>
                                <form action="{{ route('faculty.syllabus.store', ['sectionId' => $section->id, 'subjectId' => $subject->id, 'schoolYear' => $schoolYear, 'semester' => $semester]) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                        @if($errors->any())
                                            <div class="alert alert-danger">
                                                <ul class="mb-0">
                                                    @foreach($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <div class="form-group">
                                            <label for="syllabus_file">Syllabus File (PDF, DOC, DOCX)</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" id="syllabus_file" name="syllabus_file" required>
                                                    <label class="custom-file-label" for="syllabus_file">Choose file</label>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted">Maximum file size: 10MB</small>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">Upload Syllabus</button>
                                        <a href="{{ route('faculty.classes.details', ['sectionId' => $section->id, 'subjectId' => $subject->id, 'schoolYear' => $schoolYear, 'semester' => $semester]) }}" class="btn btn-default">Cancel</a>
                                    </div>
                                </form>
                            </div>

                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Guidelines for Syllabus Upload</h3>
                                </div>
                                <div class="card-body">
                                    <p>Please ensure your syllabus includes the following:</p>
                                    <ul>
                                        <li>Course description and objectives</li>
                                        <li>Grading system</li>
                                        <li>Weekly schedule of topics</li>
                                        <li>Required textbooks and references</li>
                                        <li>Assessment methods</li>
                                        <li>Classroom policies</li>
                                        <li>Contact information</li>
                                    </ul>
                                    <p>Accepted file formats: PDF, DOC, DOCX</p>
                                    <p>The uploaded syllabus will be accessible to students enrolled in this class.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <footer class="main-footer">
            <strong>Copyright &copy; 2025 <a href="#">Pangasinan State University</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0.0
            </div>
        </footer>
    </div>

    <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input@1.3.4/dist/bs-custom-file-input.min.js"></script>
    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

    <script>
        $(function() {
            bsCustomFileInput.init();
        });
    </script>
</body>
</html>
