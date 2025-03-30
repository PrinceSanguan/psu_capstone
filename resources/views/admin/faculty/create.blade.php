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

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Add New Faculty</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.faculty.index') }}">Faculty</a>
                                </li>
                                <li class="breadcrumb-item active">Add New</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Faculty Information</h3>
                        </div>
                        <form action="{{ route('admin.faculty.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                {{-- Display validation errors --}}
                                @if($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <!-- Name -->
                                <div class="form-group">
                                    <label for="name">Full Name</label>
                                    <input type="text"
                                           name="name"
                                           id="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name') }}"
                                           required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Student Number -->
                                <div class="form-group">
                                    <label for="student_number">Faculty Number</label>
                                    <input type="text"
                                           name="student_number"
                                           id="student_number"
                                           class="form-control @error('student_number') is-invalid @enderror"
                                           value="{{ old('student_number') }}"
                                           placeholder="e.g. FAC-2023-001"
                                           required>
                                    @error('student_number')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Major -->
                                <div class="form-group">
                                    <label for="major">Major / Specialization</label>
                                    <input type="text"
                                           name="major"
                                           id="major"
                                           class="form-control @error('major') is-invalid @enderror"
                                           value="{{ old('major') }}"
                                           placeholder="e.g. Information Technology">
                                    @error('major')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Sex -->
                                <div class="form-group">
                                    <label for="sex">Sex</label>
                                    <select name="sex"
                                            id="sex"
                                            class="form-control @error('sex') is-invalid @enderror"
                                            required>
                                        <option value="">-- Select Sex --</option>
                                        <option value="M" {{ old('sex') == 'M' ? 'selected' : '' }}>Male</option>
                                        <option value="F" {{ old('sex') == 'F' ? 'selected' : '' }}>Female</option>
                                    </select>
                                    @error('sex')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Course -->
                                <div class="form-group">
                                    <label for="course">Course</label>
                                    <input type="text"
                                           name="course"
                                           id="course"
                                           class="form-control @error('course') is-invalid @enderror"
                                           value="{{ old('course') }}"
                                           placeholder="e.g. BSIT"
                                           required>
                                    @error('course')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Year -->
                                <div class="form-group">
                                    <label for="year">Year</label>
                                    <input type="text"
                                           name="year"
                                           id="year"
                                           class="form-control @error('year') is-invalid @enderror"
                                           value="{{ old('year') }}"
                                           placeholder="e.g. N/A"
                                           required>
                                    @error('year')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password"
                                           name="password"
                                           id="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           required>
                                    @error('password')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href="{{ route('admin.faculty.index') }}" class="btn btn-default">Cancel</a>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div><!-- /.container-fluid -->
            </section>
        </div>
        <!-- /.content-wrapper -->

        <!-- Footer -->
        @include('admin.layout.footer')
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
