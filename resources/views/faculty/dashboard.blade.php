@include('admin.layout.header')  {{-- Use your common header --}}

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

    <!-- Faculty Sidebar -->
    @include('faculty.layout.sidebar')

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Faculty Dashboard</h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->

        <!-- Main Content -->
        <section class="content">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <!-- Stat Boxes -->
                <div class="row">
                    <!-- My Classes -->
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $assignedClasses->count() }}</h3>
                                <p>My Classes</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <a href="{{ route('faculty.classes.index') }}" class="small-box-footer">
                                View Classes <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    <!-- Total Students -->
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $studentCount }}</h3>
                                <p>Total Students</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <a href="{{ route('faculty.classes.index') }}" class="small-box-footer">
                                Student Info <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    <!-- Syllabi Uploaded -->
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $syllabiCount }}</h3>
                                <p>Syllabi Uploaded</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-file-upload"></i>
                            </div>
                            <a href="{{ route('faculty.syllabus.index') }}" class="small-box-footer">
                                View Syllabi <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    <!-- Pending Tasks (Example: difference between classes & syllabi) -->
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $assignedClasses->count() - $syllabiCount }}</h3>
                                <p>Pending Tasks</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-tasks"></i>
                            </div>
                            <a href="{{ route('faculty.classes.index') }}" class="small-box-footer">
                                View Tasks <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- End Stat Boxes -->

                <div class="row">
                    <!-- Classes Table -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header border-transparent">
                                <h3 class="card-title">My Classes</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table m-0">
                                        <thead>
                                            <tr>
                                                <th>Section</th>
                                                <th>Subject</th>
                                                <th>Code</th>
                                                <th>School Year</th>
                                                <th>Semester</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($assignedClasses as $class)
                                            <tr>
                                                <td>{{ $class->section_name }}</td>
                                                <td>{{ $class->subject_name }}</td>
                                                <td>{{ $class->subject_code }}</td>
                                                <td>{{ $class->school_year }}</td>
                                                <td>{{ $class->semester }}</td>
                                                <td>
                                                    <a href="{{ route('faculty.classes.details', [
                                                        'sectionId' => $class->section_id,
                                                        'subjectId' => $class->subject_id,
                                                        'schoolYear' => $class->school_year,
                                                        'semester' => $class->semester
                                                    ]) }}" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No classes assigned</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer clearfix">
                                <a href="{{ route('faculty.classes.index') }}" class="btn btn-sm btn-info float-right">
                                    View All Classes
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activities -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Recent Activities</h3>
                            </div>
                            <div class="card-body p-0">
                                <ul class="products-list product-list-in-card pl-2 pr-2">
                                    @if(isset($recentActivities) && count($recentActivities) > 0)
                                        @foreach($recentActivities as $activity)
                                            <li class="item">
                                                <div class="product-info">
                                                    <a href="javascript:void(0)" class="product-title">
                                                        {{ $activity['description'] }}
                                                        <span class="badge badge-info float-right">
                                                            {{ \Carbon\Carbon::parse($activity['timestamp'])->diffForHumans() }}
                                                        </span>
                                                    </a>
                                                    <span class="product-description">
                                                        {{ $activity['title'] }}
                                                    </span>
                                                </div>
                                            </li>
                                        @endforeach
                                    @else
                                        <li class="item text-center p-2">No recent activities found</li>
                                    @endif
                                </ul>
                            </div>
                            <div class="card-footer text-center">
                                <a href="{{ route('faculty.classes.index') }}" class="uppercase">
                                    View All Activities
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Row -->
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->

    @include('admin.layout.footer')
</div>
<!-- ./wrapper -->

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
