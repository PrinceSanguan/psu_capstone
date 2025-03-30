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
                                        <p>View Seat Plan</p>
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
                            <h1 class="m-0">View Seat Plan</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('faculty.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('faculty.classes.index') }}">My Classes</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('faculty.classes.details', ['sectionId' => $section->id, 'subjectId' => $subject->id, 'schoolYear' => $schoolYear, 'semester' => $semester]) }}">{{ $subject->code }} - {{ $section->name }}</a></li>
                                <li class="breadcrumb-item active">View Seat Plan</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
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

                                    <a href="{{ route('faculty.seatplan.create', ['sectionId' => $section->id, 'subjectId' => $subject->id, 'schoolYear' => $schoolYear, 'semester' => $semester]) }}" class="btn btn-primary btn-block">
                                        <i class="fas fa-edit"></i> Edit Seat Plan
                                    </a>
                                    <a href="{{ route('faculty.classes.details', ['sectionId' => $section->id, 'subjectId' => $subject->id, 'schoolYear' => $schoolYear, 'semester' => $semester]) }}" class="btn btn-default btn-block">
                                        Back to Class Details
                                    </a>
                                </div>
                            </div>

                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Seat Plan Legend</h3>
                                </div>
                                <div class="card-body">
                                    <div class="legend-item d-flex align-items-center mb-3">
                                        <div class="legend-box occupied"></div>
                                        <div class="ml-2">Occupied Seat</div>
                                    </div>
                                    <div class="legend-item d-flex align-items-center">
                                        <div class="legend-box empty"></div>
                                        <div class="ml-2">Empty Seat</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Seat Plan ({{ $seatPlan->rows }} x {{ $seatPlan->columns }})</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                            <i class="fas fa-expand"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="seat-plan-container">
                                        <div class="text-center mb-3">
                                            <div class="room-label p-2 bg-secondary text-white">WHITEBOARD</div>
                                        </div>
                                        <div id="seatGrid" class="seat-grid">
                                            <!-- Seat grid will be generated here by JavaScript -->
                                        </div>
                                    </div>
                                    <div class="text-center mt-4">
                                        <button type="button" class="btn btn-info" id="printSeatPlan">
                                            <i class="fas fa-print"></i> Print Seat Plan
                                        </button>
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
            position: relative;
            transition: all 0.2s;
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
        .seat .seat-position {
            position: absolute;
            bottom: 2px;
            right: 5px;
            font-size: 0.6rem;
            color: #6c757d;
        }
        .room-label {
            display: inline-block;
            min-width: 200px;
            border-radius: 5px;
        }
        .legend-box {
            width: 30px;
            height: 30px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .legend-box.occupied {
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .legend-box.empty {
            background-color: #f8f9fa;
        }
        @media print {
            .main-header, .main-sidebar, .main-footer, .card-header button, .card-tools, .btn, .breadcrumb {
                display: none !important;
            }
            .content-wrapper, .content, .card, .card-body {
                padding: 0 !important;
                margin: 0 !important;
                background-color: white !important;
                box-shadow: none !important;
                width: 100% !important;
            }
            .content-wrapper {
                margin-left: 0 !important;
                transform: none !important;
            }
            .container-fluid {
                padding: 0 !important;
            }
            .col-md-4 {
                display: none !important;
            }
            .col-md-8 {
                width: 100% !important;
                flex: 0 0 100% !important;
                max-width: 100% !important;
            }
            .seat-grid {
                page-break-inside: avoid;
            }
            .seat {
                border: 1px solid #000 !important;
            }
            .seat.occupied {
                background-color: #eee !important;
            }
            .seat-position {
                color: #000 !important;
            }
            .student-number {
                color: #000 !important;
            }
            .room-label {
                background-color: #eee !important;
                color: #000 !important;
                border: 1px solid #000 !important;
            }
        }
    </style>

    <script>
        $(function() {
            // Get arrangement data from PHP
            const arrangementData = {!! json_encode($arrangementData) !!};
            const rows = {{ $seatPlan->rows }};
            const columns = {{ $seatPlan->columns }};

            // Generate grid
            generateSeatGrid(rows, columns, arrangementData);

            // Print functionality
            $('#printSeatPlan').on('click', function() {
                window.print();
            });

            // Function to generate seat grid
            function generateSeatGrid(rows, columns, arrangement) {
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
                        const studentId = arrangement[seatId];

                        const seat = $('<div>', {
                            class: 'seat' + (studentId ? ' occupied' : ''),
                            'data-seat-id': seatId,
                            'data-row': row,
                            'data-col': col
                        });

                        const seatContent = $('<div>');

                        if (studentId) {
                            const student = findStudentById(studentId);
                            seatContent.html(`
                                <div class="student-name">${student ? student.name : 'Unknown Student'}</div>
                                <div class="student-number">${student ? student.student_number : ''}</div>
                            `);
                        }

                        // Add seat position label
                        seatContent.append(`<div class="seat-position">R${row+1}C${col+1}</div>`);

                        seat.append(seatContent);
                        grid.append(seat);
                    }
                }
            }

            // Function to find student by ID
            function findStudentById(studentId) {
                // Create a dictionary of students from PHP data
                const students = {!! json_encode($students) !!};
                return students[studentId] || null;
            }
        });
    </script>
</body>
</html>
