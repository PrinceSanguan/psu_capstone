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
                <span class="brand-text font-weight-light">Harvard Faculty Portal</span>
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
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ route('faculty.dashboard') }}" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('faculty.classes.index') }}" class="nav-link active">
                                <i class="nav-icon fas fa-chalkboard"></i>
                                <p>My Classes</p>
                            </a>
                        </li>
                        <li class="nav-header">CLASS MANAGEMENT</li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
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
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Seat Plans
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('faculty.classes.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Manage Seat Plans</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
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
                                    <a href="{{ route('faculty.classes.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Create Quiz/Activity</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-chart-bar"></i>
                                <p>Student Analytics</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-file-pdf"></i>
                                <p>Generate Reports</p>
                            </a>
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
                            <h1 class="m-0">{{ $subject->code }} - {{ $subject->name }}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('faculty.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('faculty.classes.index') }}">My
                                        Classes</a></li>
                                <li class="breadcrumb-item active">{{ $subject->code }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-3">
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
                                        <li class="list-group-item">
                                            <b>Students</b> <a class="float-right">{{ $students->count() }}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Quick Actions</h3>
                                </div>
                                <div class="card-body p-0">
                                    <ul class="nav nav-pills flex-column">
                                        <li class="nav-item">
                                            <a href="{{ route('faculty.syllabus.upload', ['sectionId' => $section->id, 'subjectId' => $subject->id, 'schoolYear' => $schoolYear, 'semester' => $semester]) }}"
                                                class="nav-link">
                                                <i class="fas fa-file-upload"></i> Upload Syllabus
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('faculty.seatplan.create', ['sectionId' => $section->id, 'subjectId' => $subject->id, 'schoolYear' => $schoolYear, 'semester' => $semester]) }}"
                                                class="nav-link">
                                                <i class="fas fa-chair"></i> Create Seat Plan
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('faculty.assessment.create', ['sectionId' => $section->id, 'subjectId' => $subject->id, 'schoolYear' => $schoolYear, 'semester' => $semester]) }}"
                                                class="nav-link">
                                                <i class="fas fa-tasks"></i> Create Assessment
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('faculty.analytics', ['sectionId' => $section->id, 'subjectId' => $subject->id, 'schoolYear' => $schoolYear, 'semester' => $semester]) }}"
                                                class="nav-link">
                                                <i class="fas fa-chart-bar"></i> View Analytics
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('faculty.reports.generate', ['sectionId' => $section->id, 'subjectId' => $subject->id, 'schoolYear' => $schoolYear, 'semester' => $semester]) }}"
                                                class="nav-link">
                                                <i class="fas fa-file-pdf"></i> Generate Report
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-header p-2">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item"><a class="nav-link active" href="#students"
                                                data-toggle="tab">Students</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#assessments"
                                                data-toggle="tab">Assessments</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#syllabus"
                                                data-toggle="tab">Syllabus</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#seatplan"
                                                data-toggle="tab">Seat Plan</a></li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content">
                                        <!-- Students Tab -->
                                        <div class="active tab-pane" id="students">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Student Number</th>
                                                        <th>Name</th>
                                                        <th>Sex</th>
                                                        <th>Course</th>
                                                        <th>Year</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($students as $student)
                                                        <tr>
                                                            <td>{{ $student->student_number }}</td>
                                                            <td>{{ $student->name }}</td>
                                                            <td>{{ $student->sex }}</td>
                                                            <td>{{ $student->course }}</td>
                                                            <td>{{ $student->year }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center">No students
                                                                enrolled in this class</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Assessments Tab -->
                                        <div class="tab-pane" id="assessments">
                                            <div class="d-flex justify-content-end mb-3">
                                                <a href="{{ route('faculty.assessment.create', ['sectionId' => $section->id, 'subjectId' => $subject->id, 'schoolYear' => $schoolYear, 'semester' => $semester]) }}"
                                                    class="btn btn-primary">
                                                    <i class="fas fa-plus"></i> Create Assessment
                                                </a>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h5>Midterm Assessments</h5>
                                                    <table class="table table-bordered table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th>Title</th>
                                                                <th>Type</th>
                                                                <th>Max Score</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php $hasMidtermAssessments = false; @endphp
                                                            @foreach ($assessments as $assessment)
                                                                @if ($assessment->term == 'midterm')
                                                                    @php $hasMidtermAssessments = true; @endphp
                                                                    <tr>
                                                                        <td>{{ $assessment->title }}</td>
                                                                        <td>{{ ucfirst(str_replace('_', ' ', $assessment->type)) }}
                                                                        </td>
                                                                        <td>{{ $assessment->max_score }}</td>
                                                                        <td>
                                                                            <a href="{{ route('faculty.scores.manage', ['assessmentId' => $assessment->id]) }}"
                                                                                class="btn btn-info btn-sm">
                                                                                <i class="fas fa-edit"></i> Scores
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                            @if (!$hasMidtermAssessments)
                                                                <tr>
                                                                    <td colspan="4" class="text-center">No midterm
                                                                        assessments yet</td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <h5>Final Assessments</h5>
                                                    <table class="table table-bordered table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th>Title</th>
                                                                <th>Type</th>
                                                                <th>Max Score</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php $hasFinalAssessments = false; @endphp
                                                            @foreach ($assessments as $assessment)
                                                                @if ($assessment->term == 'final')
                                                                    @php $hasFinalAssessments = true; @endphp
                                                                    <tr>
                                                                        <td>{{ $assessment->title }}</td>
                                                                        <td>{{ ucfirst(str_replace('_', ' ', $assessment->type)) }}
                                                                        </td>
                                                                        <td>{{ $assessment->max_score }}</td>
                                                                        <td>
                                                                            <a href="{{ route('faculty.scores.manage', ['assessmentId' => $assessment->id]) }}"
                                                                                class="btn btn-info btn-sm">
                                                                                <i class="fas fa-edit"></i> Scores
                                                                            </a>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                            @if (!$hasFinalAssessments)
                                                                <tr>
                                                                    <td colspan="4" class="text-center">No final
                                                                        assessments yet</td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Syllabus Tab -->
                                        <div class="tab-pane" id="syllabus">
                                            @if ($syllabus)
                                                <div class="alert alert-success">
                                                    <i class="fas fa-check-circle"></i> Syllabus has been uploaded on
                                                    {{ date('F d, Y h:i A', strtotime($syllabus->upload_timestamp)) }}
                                                </div>
                                                <div class="text-center">
                                                    <p><strong>Filename:</strong> {{ $syllabus->original_filename }}
                                                    </p>
                                                    <a href="{{ route('faculty.syllabus.download', ['id' => $syllabus->id]) }}"
                                                        class="btn btn-primary">
                                                        <i class="fas fa-download"></i> Download Syllabus
                                                    </a>
                                                    <a href="{{ route('faculty.syllabus.upload', ['sectionId' => $section->id, 'subjectId' => $subject->id, 'schoolYear' => $schoolYear, 'semester' => $semester]) }}"
                                                        class="btn btn-warning">
                                                        <i class="fas fa-upload"></i> Upload New Version
                                                    </a>
                                                </div>
                                            @else
                                                <div class="alert alert-warning">
                                                    <i class="fas fa-exclamation-triangle"></i> No syllabus has been
                                                    uploaded yet.
                                                </div>
                                                <div class="text-center">
                                                    <p>Please upload a syllabus for this class.</p>
                                                    <a href="{{ route('faculty.syllabus.upload', ['sectionId' => $section->id, 'subjectId' => $subject->id, 'schoolYear' => $schoolYear, 'semester' => $semester]) }}"
                                                        class="btn btn-primary">
                                                        <i class="fas fa-upload"></i> Upload Syllabus
                                                    </a>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Seat Plan Tab -->
                                        <div class="tab-pane" id="seatplan">
                                            @if ($seatPlan)
                                                <div class="alert alert-success">
                                                    <i class="fas fa-check-circle"></i> Seat plan has been created.
                                                </div>
                                                <div class="text-center mb-3">
                                                    <a href="{{ route('faculty.seatplan.view', ['sectionId' => $section->id, 'subjectId' => $subject->id, 'schoolYear' => $schoolYear, 'semester' => $semester]) }}"
                                                        class="btn btn-primary">
                                                        <i class="fas fa-eye"></i> View Seat Plan
                                                    </a>
                                                    <a href="{{ route('faculty.seatplan.create', ['sectionId' => $section->id, 'subjectId' => $subject->id, 'schoolYear' => $schoolYear, 'semester' => $semester]) }}"
                                                        class="btn btn-warning">
                                                        <i class="fas fa-edit"></i> Edit Seat Plan
                                                    </a>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><strong>Rows:</strong> {{ $seatPlan->rows }}</p>
                                                        <p><strong>Columns:</strong> {{ $seatPlan->columns }}</p>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="alert alert-warning">
                                                    <i class="fas fa-exclamation-triangle"></i> No seat plan has been
                                                    created yet.
                                                </div>
                                                <div class="text-center">
                                                    <p>Please create a seat plan for this class.</p>
                                                    <a href="{{ route('faculty.seatplan.create', ['sectionId' => $section->id, 'subjectId' => $subject->id, 'schoolYear' => $schoolYear, 'semester' => $semester]) }}"
                                                        class="btn btn-primary">
                                                        <i class="fas fa-plus"></i> Create Seat Plan
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
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
    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>

</html>
