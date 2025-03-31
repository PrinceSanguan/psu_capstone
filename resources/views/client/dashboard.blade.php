{{-- resources/views/client/dashboard.blade.php --}}
@include('admin.layout.header')

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
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
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    @include('admin.layout.sidebar')

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ Auth::user()->name }}'s Dashboard</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('faculty.dashboard') }}">Dashboard</a>
                            </li>
                            <li class="breadcrumb-item active">My Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Dashboard Cards Row -->
                <div class="row">
                    <!-- Total Subjects Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Subjects
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $totalSubjects }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-book fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Sections Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Total Sections
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $totalSections }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-users fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Unread Messages Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Unread Messages
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $unreadMessages }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-envelope fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Assessments Card -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Upcoming Assessments
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            {{ $upcomingAssessments->count() }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of Dashboard Cards Row -->

                <!-- Enrolled Classes and Upcoming Assessments Tables Row -->
                <div class="row">
                    <!-- Enrolled Classes -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">My Classes</h6>
                            </div>
                            <div class="card-body">
                                @if($enrollments->isEmpty())
                                    <p class="text-center">You are not enrolled in any classes.</p>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-bordered" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Subject</th>
                                                    <th>Section</th>
                                                    <th>Teacher</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($enrollments->take(5) as $enrollment)
                                                    <tr>
                                                        <td>{{ $enrollment->subject_code }} - {{ $enrollment->subject_name }}</td>
                                                        <td>{{ $enrollment->section_name }}</td>
                                                        <td>{{ $enrollment->faculty_name }}</td>
                                                        <td>
                                                            <a href="{{ route('client.classes.details', [
                                                                'sectionId' => $enrollment->section_id,
                                                                'subjectId' => $enrollment->subject_id,
                                                                'schoolYear' => $enrollment->school_year,
                                                                'semester' => $enrollment->semester
                                                            ]) }}" class="btn btn-sm btn-primary">
                                                                <i class="fas fa-eye"></i> View
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @if($enrollments->count() > 5)
                                        <div class="text-center mt-3">
                                            <a href="{{ route('client.classes.index') }}" class="btn btn-link">View All Classes</a>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Assessments Table -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Upcoming Assessments</h6>
                            </div>
                            <div class="card-body">
                                @if($upcomingAssessments->isEmpty())
                                    <p class="text-center">No upcoming assessments scheduled.</p>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-bordered" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Subject</th>
                                                    <th>Title</th>
                                                    <th>Type</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($upcomingAssessments->take(5) as $assessment)
                                                    <tr>
                                                        <td>{{ $assessment->subject_code }}</td>
                                                        <td>{{ $assessment->title }}</td>
                                                        <td>{{ ucfirst(str_replace('_', ' ', $assessment->type)) }}</td>
                                                        <td>
                                                            {{ date('M d, Y', strtotime($assessment->schedule_date)) }}
                                                            @if($assessment->schedule_time)
                                                                <br>{{ date('h:i A', strtotime($assessment->schedule_time)) }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @if($upcomingAssessments->count() > 5)
                                        <div class="text-center mt-3">
                                            <a href="{{ route('client.schedules.index') }}" class="btn btn-link">View All Schedules</a>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of Enrolled Classes and Upcoming Assessments Row -->

                <!-- Recent Scores Row -->
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Recent Scores</h6>
                                <a href="{{ route('client.grades.index') }}" class="btn btn-sm btn-primary">View All Grades</a>
                            </div>
                            <div class="card-body">
                                @if($recentScores->isEmpty())
                                    <p class="text-center">No recent scores available.</p>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-bordered" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Subject</th>
                                                    <th>Assessment</th>
                                                    <th>Type</th>
                                                    <th>Score</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($recentScores as $score)
                                                    <tr>
                                                        <td>{{ $score->subject_code }}</td>
                                                        <td>{{ $score->assessment_title }}</td>
                                                        <td>{{ ucfirst(str_replace('_', ' ', $score->assessment_type)) }}</td>
                                                        <td>
                                                            {{ $score->score }} / {{ $score->max_score }}
                                                            ({{ round(($score->score / $score->max_score) * 100, 2) }}%)
                                                        </td>
                                                        <td>{{ date('M d, Y', strtotime($score->created_at)) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of Recent Scores Row -->

            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    @include('admin.layout.footer')
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<!-- DataTables & Plugins (if needed) -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(function () {
        // Initialize DataTables if needed for your tables
        $('#studentTable, #subjectTable').DataTable();
    });
</script>
</body>
</html>
