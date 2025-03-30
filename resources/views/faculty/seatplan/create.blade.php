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
                        <li class="nav-item menu-open">
                            <a href="#" class="nav-link active">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Seat Plans
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link active">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Create Seat Plan</p>
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
                            <h1 class="m-0">Create Seat Plan</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('faculty.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('faculty.classes.index') }}">My Classes</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('faculty.classes.details', ['sectionId' => $section->id, 'subjectId' => $subject->id, 'schoolYear' => $schoolYear, 'semester' => $semester]) }}">{{ $subject->code }} - {{ $section->name }}</a></li>
                                <li class="breadcrumb-item active">Create Seat Plan</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
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
                                        <li class="list-group-item">
                                            <b>Students</b> <a class="float-right">{{ count($students) }}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Students</h3>
                                </div>
                                <div class="card-body p-0">
                                    <div style="max-height: 400px; overflow-y: auto;">
                                        <ul class="list-group list-group-flush" id="student-list">
                                            @foreach($students as $student)
                                                <li class="list-group-item student-item" data-id="{{ $student->id }}" data-name="{{ $student->name }}">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <i class="fas fa-user-graduate mr-2"></i>
                                                            <span>{{ $student->name }}</span>
                                                            <small class="text-muted d-block ml-4">{{ $student->student_number }}</small>
                                                        </div>
                                                        <button type="button" class="btn btn-xs btn-primary assign-button">
                                                            <i class="fas fa-arrow-right"></i>
                                                        </button>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-footer bg-white">
                                    <small class="text-muted">Click on a student or use the arrow button to assign to a seat.</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Seat Plan Configuration</h3>
                                </div>
                                <form action="{{ route('faculty.seatplan.store', ['sectionId' => $section->id, 'subjectId' => $subject->id, 'schoolYear' => $schoolYear, 'semester' => $semester]) }}" method="POST" id="seatPlanForm">
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

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="rows">Number of Rows</label>
                                                    <input type="number" class="form-control" id="rows" name="rows" min="1" max="20" value="{{ $existingSeatPlan->rows ?? 5 }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="columns">Number of Columns</label>
                                                    <input type="number" class="form-control" id="columns" name="columns" min="1" max="20" value="{{ $existingSeatPlan->columns ?? 5 }}" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <button type="button" id="generateGrid" class="btn btn-primary">Generate Grid</button>
                                            <button type="button" id="clearGrid" class="btn btn-warning ml-2">Clear Grid</button>
                                        </div>

                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            To assign a student to a seat, click on a student from the list and then click on an empty seat.
                                            To remove a student from a seat, click the "X" button on the occupied seat.
                                        </div>

                                        <div class="seat-plan-container mt-4">
                                            <div class="text-center mb-3">
                                                <div class="room-label p-2 bg-secondary text-white">WHITEBOARD</div>
                                            </div>
                                            <div id="seatGrid" class="seat-grid">
                                                <!-- Seat grid will be generated here -->
                                            </div>
                                        </div>

                                        <!-- Hidden input to store arrangement data -->
                                        <input type="hidden" name="arrangement" id="arrangementData" value="">
                                    </div>
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">Save Seat Plan</button>
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

    <style>
        .seat-grid {
            display: grid;
            gap: 10px;
            margin: 0 auto;
            max-width: 100%;
            overflow-x: auto;
        }
        .seat {
            width: 100%;
            height: 80px;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
            cursor: pointer;
            position: relative;
            transition: all 0.2s;
        }
        .seat:hover {
            background-color: #e9ecef;
        }
        .seat.occupied {
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .seat .student-name {
            font-weight: bold;
            text-align: center;
            font-size: 0.9rem;
            word-break: break-word;
        }
        .seat .student-number {
            font-size: 0.7rem;
            color: #6c757d;
        }
        .seat .remove-student {
            position: absolute;
            top: 2px;
            right: 2px;
            font-size: 0.7rem;
            color: #dc3545;
            cursor: pointer;
        }
        .room-label {
            display: inline-block;
            min-width: 200px;
            border-radius: 5px;
        }
        .student-item {
            cursor: pointer;
        }
        .student-item:hover {
            background-color: #f8f9fa;
        }
        .student-item.selected {
            background-color: #d4edda;
        }
    </style>

    <script>
        $(function() {
            let selectedStudent = null;
            let arrangement = {};

            // Load existing arrangement if available
            @if(isset($existingSeatPlan) && $existingSeatPlan->arrangement)
                arrangement = JSON.parse('{!! addslashes($existingSeatPlan->arrangement) !!}');
                const rows = {{ $existingSeatPlan->rows }};
                const columns = {{ $existingSeatPlan->columns }};
                generateSeatGrid(rows, columns);
                populateGridFromArrangement();
            @endif

            // Generate grid on button click
            $('#generateGrid').on('click', function() {
                const rows = parseInt($('#rows').val());
                const columns = parseInt($('#columns').val());

                if (rows > 0 && columns > 0) {
                    // Clear existing arrangement
                    arrangement = {};
                    generateSeatGrid(rows, columns);
                }
            });

            // Clear grid
            $('#clearGrid').on('click', function() {
                if (confirm('Are you sure you want to clear the entire grid? This will remove all student assignments.')) {
                    arrangement = {};
                    const rows = parseInt($('#rows').val());
                    const columns = parseInt($('#columns').val());
                    generateSeatGrid(rows, columns);
                }
            });

            // Select student
            $(document).on('click', '.student-item', function() {
                $('.student-item').removeClass('selected');
                $(this).addClass('selected');
                selectedStudent = {
                    id: $(this).data('id'),
                    name: $(this).data('name')
                };
            });

            // Assign button click
            $(document).on('click', '.assign-button', function() {
                const studentItem = $(this).closest('.student-item');
                $('.student-item').removeClass('selected');
                studentItem.addClass('selected');
                selectedStudent = {
                    id: studentItem.data('id'),
                    name: studentItem.data('name')
                };
            });

            // Click on seat
            $(document).on('click', '.seat', function() {
                const seatId = $(this).data('seat-id');

                // If already occupied by another student, remove that assignment
                if ($(this).hasClass('occupied')) {
                    delete arrangement[seatId];
                    updateSeatDisplay(seatId);
                }

                // If a student is selected, assign to this seat
                if (selectedStudent) {
                    // First remove student from any existing seat
                    for (const existingSeatId in arrangement) {
                        if (arrangement[existingSeatId] === selectedStudent.id) {
                            delete arrangement[existingSeatId];
                            updateSeatDisplay(existingSeatId);
                        }
                    }

                    // Assign to new seat
                    arrangement[seatId] = selectedStudent.id;
                    updateSeatDisplay(seatId);

                    // Deselect student
                    $('.student-item').removeClass('selected');
                    selectedStudent = null;
                }

                // Update hidden field
                $('#arrangementData').val(JSON.stringify(arrangement));
            });

            // Remove student from seat
            $(document).on('click', '.remove-student', function(e) {
                e.stopPropagation();
                const seatId = $(this).closest('.seat').data('seat-id');
                delete arrangement[seatId];
                updateSeatDisplay(seatId);
                $('#arrangementData').val(JSON.stringify(arrangement));
            });

            // Form submission
            $('#seatPlanForm').on('submit', function() {
                $('#arrangementData').val(JSON.stringify(arrangement));
                return true;
            });

            // Function to generate seat grid
            function generateSeatGrid(rows, columns) {
                const grid = $('#seatGrid');
                grid.empty();

                // Set grid template
                grid.css({
                    'grid-template-columns': `repeat(${columns}, 1fr)`,
                    'grid-template-rows': `repeat(${rows}, 1fr)`
                });

                // Create seats
                for (let row = 0; row < rows; row++) {
                    for (let col = 0; col < columns; col++) {
                        const seatId = `${row}-${col}`;
                        const seat = $('<div>', {
                            class: 'seat',
                            'data-seat-id': seatId,
                            'data-row': row,
                            'data-col': col
                        });

                        seat.html(`
                            <div class="student-name"></div>
                            <div class="student-number"></div>
                        `);

                        grid.append(seat);
                    }
                }

                // Update the hidden input field
                $('#arrangementData').val(JSON.stringify(arrangement));
            }

            // Populate grid from existing arrangement
            function populateGridFromArrangement() {
                // Reset all seats
                $('.seat').removeClass('occupied').find('.student-name, .student-number').empty();
                $('.seat .remove-student').remove();

                // Update seats from arrangement
                for (const seatId in arrangement) {
                    updateSeatDisplay(seatId);
                }
            }

            // Update seat display based on arrangement
            function updateSeatDisplay(seatId) {
                const seat = $(`.seat[data-seat-id="${seatId}"]`);
                const studentId = arrangement[seatId];

                if (studentId) {
                    // Find student info
                    const studentItem = $(`.student-item[data-id="${studentId}"]`);
                    const studentName = studentItem.data('name');
                    const studentNumber = studentItem.find('small').text().trim();

                    // Update seat display
                    seat.addClass('occupied');
                    seat.find('.student-name').text(studentName);
                    seat.find('.student-number').text(studentNumber);

                    // Add remove button if not exists
                    if (seat.find('.remove-student').length === 0) {
                        seat.append('<div class="remove-student"><i class="fas fa-times-circle"></i></div>');
                    }
                } else {
                    // Clear seat
                    seat.removeClass('occupied');
                    seat.find('.student-name, .student-number').empty();
                    seat.find('.remove-student').remove();
                }
            }
        });
    </script>
</body>
</html>
