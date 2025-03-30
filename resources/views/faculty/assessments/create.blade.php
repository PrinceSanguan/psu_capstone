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
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-tasks"></i>
                                <p>
                                    Assessments
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link active">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Create Assessment</p>
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
                            <h1 class="m-0">Create Assessment</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('faculty.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('faculty.classes.index') }}">My Classes</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('faculty.classes.details', ['sectionId' => $section->id, 'subjectId' => $subject->id, 'schoolYear' => $schoolYear, 'semester' => $semester]) }}">{{ $subject->code }}</a></li>
                                <li class="breadcrumb-item active">Create Assessment</li>
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
                        </div>

                        <div class="col-md-8">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Assessment Details</h3>
                                </div>
                                <form action="{{ route('faculty.assessment.store', ['sectionId' => $section->id, 'subjectId' => $subject->id, 'schoolYear' => $schoolYear, 'semester' => $semester]) }}" method="POST">
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
                                            <label for="title">Assessment Title</label>
                                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" placeholder="e.g. Quiz 1, Midterm Exam" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="type">Assessment Type</label>
                                            <select class="form-control" id="type" name="type" required>
                                                <option value="" selected disabled>Select type</option>
                                                <option value="quiz" {{ old('type') == 'quiz' ? 'selected' : '' }}>Quiz</option>
                                                <option value="unit_test" {{ old('type') == 'unit_test' ? 'selected' : '' }}>Unit Test</option>
                                                <option value="activity" {{ old('type') == 'activity' ? 'selected' : '' }}>Activity</option>
                                                <option value="midterm_exam" {{ old('type') == 'midterm_exam' ? 'selected' : '' }}>Midterm Exam</option>
                                                <option value="final_exam" {{ old('type') == 'final_exam' ? 'selected' : '' }}>Final Exam</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="term">Assessment Term</label>
                                            <select class="form-control" id="term" name="term" required>
                                                <option value="" selected disabled>Select term</option>
                                                <option value="midterm" {{ old('term') == 'midterm' ? 'selected' : '' }}>Midterm</option>
                                                <option value="final" {{ old('term') == 'final' ? 'selected' : '' }}>Final</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="max_score">Maximum Score</label>
                                            <input type="number" class="form-control" id="max_score" name="max_score" value="{{ old('max_score') }}" min="1" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="schedule_date">Schedule Date (Optional)</label>
                                            <input type="date" class="form-control" id="schedule_date" name="schedule_date" value="{{ old('schedule_date') }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="schedule_time">Schedule Time (Optional)</label>
                                            <input type="time" class="form-control" id="schedule_time" name="schedule_time" value="{{ old('schedule_time') }}">
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">Create Assessment</button>
                                        <a href="{{ route('faculty.classes.details', ['sectionId' => $section->id, 'subjectId' => $subject->id, 'schoolYear' => $schoolYear, 'semester' => $semester]) }}" class="btn btn-default">Cancel</a>
                                    </div>
                                </form>
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
    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
