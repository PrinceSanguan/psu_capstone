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
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    @include('admin.layout.sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Admin Dashboard</h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $students->count() }}</h3>
                                <p>Total Students</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <!-- Example link to show all students or add new -->
                            <a href="#studentList" class="small-box-footer">
                                View <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    <!-- ./col -->

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>24</h3>
                                <p>Active Subjects</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <!-- Example link to manage grading system -->
                            <a href="{{ route('admin.editGradingSystem', 1) }}"
                               class="small-box-footer">
                                Manage Grading <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    <!-- ./col -->

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>5</h3>
                                <p>Pending Registrations</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-clock"></i>
                            </div>
                            <!-- Example link to add new student -->
                            <a href="{{ route('admin.createStudent') }}"
                               class="small-box-footer">
                                Add Student <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    <!-- ./col -->

                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>12</h3>
                                <p>Reports Generated</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <!-- Example link to teacher syllabus times -->
                            <a href="{{ route('admin.syllabi.index') }}"
                               class="small-box-footer">
                                View Syllabi Uploads <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    <!-- ./col -->
                </div>
                <!-- /.row -->

                <!-- Student List -->
                <div class="row" id="studentList">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h3 class="card-title">Student List</h3>
                                <!-- "Add Student" button up here too if you like -->
                                <a href="{{ route('admin.createStudent') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-user-plus"></i> Add Student
                                </a>
                            </div>
                            <div class="card-body">
                                <table id="studentTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Student Number</th>
                                            <th>Major</th>
                                            <th>Sex</th>
                                            <th>Course</th>
                                            <th>Year</th>
                                            <th>Action</th> <!-- new column for removing -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($students as $student)
                                            <tr>
                                                <td>{{ $student->name }}</td>
                                                <td>{{ $student->student_number }}</td>
                                                <td>{{ $student->major }}</td>
                                                <td>{{ $student->sex }}</td>
                                                <td>{{ $student->course }}</td>
                                                <td>{{ $student->year }}</td>
                                                <td>
                                                    <!-- Remove student button -->
                                                    <form action="{{ route('admin.deleteStudent', $student->id) }}"
                                                          method="POST"
                                                          style="display:inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Are you sure?')">
                                                            <i class="fas fa-trash-alt"></i> Remove
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row (main row) -->

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
<!-- DataTables & Plugins -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap4.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<!-- Page specific script -->
<script>
    $(function() {
        var table = $('#studentTable').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "pageLength": 10,
            "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip'
        });

        // Move search box to custom container if desired
        $('#searchContainer').html($('.dataTables_filter'));
        $('.dataTables_filter').css('text-align', 'left');
    });
</script>
</body>
</html>
