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

    @include('admin.layout.sidebar')

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6"><h1 class="m-0">Admin Dashboard</h1></div>
                </div>
            </div>
        </div>

        <section class="content">
        <div class="container-fluid">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Student Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Student List</h3>
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
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($students as $s)
                                    <tr>
                                        <td>{{ $s->name }}</td>
                                        <td>{{ $s->student_number }}</td>
                                        <td>{{ $s->major }}</td>
                                        <td>{{ $s->sex }}</td>
                                        <td>{{ $s->course }}</td>
                                        <td>{{ $s->year }}</td>
                                        <td>
                                            <form action="{{ route('admin.deleteStudent', $s->id) }}"
                                                  method="POST"
                                                  style="display:inline-block"
                                                  onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash-alt"></i> Remove
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div><!-- /.card-body -->
                    </div><!-- /.card -->
                </div>
            </div><!-- /.row -->

        </div><!-- /.container-fluid -->
        </section>
    </div>
    <!-- /.content-wrapper -->

    @include('admin.layout.footer')
</div>
<!-- ./wrapper -->

<!-- Scripts (jQuery, DataTables, etc.) -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<!-- If you want DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(function() {
        $('#studentTable').DataTable();
    });
</script>
</body>
</html>
