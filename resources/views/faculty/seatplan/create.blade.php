<!-- resources/views/faculty/seatplan/create.blade.php -->
@extends('faculty.layout.app')

@section('content')
<div class="container-fluid">
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
                        <li class="breadcrumb-item active">Create Seat Plan</li>
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

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <!-- Left Column: Class Info & Student List -->
                <div class="col-md-4">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Class Information</h3>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item"><b>Section:</b> <a class="float-right">{{ $section->name }}</a></li>
                                <li class="list-group-item"><b>Subject:</b> <a class="float-right">{{ $subject->name }}</a></li>
                                <li class="list-group-item"><b>Code:</b> <a class="float-right">{{ $subject->code }}</a></li>
                                <li class="list-group-item"><b>School Year:</b> <a class="float-right">{{ $schoolYear }}</a></li>
                                <li class="list-group-item"><b>Semester:</b> <a class="float-right">{{ $semester }}</a></li>
                                <li class="list-group-item"><b>Students:</b> <a class="float-right">{{ $students->count() }}</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Student List</h3>
                        </div>
                        <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                            <ul class="list-group list-group-flush" id="student-list">
                                @foreach($students as $student)
                                    <li class="list-group-item student-item" data-id="{{ $student->id }}" data-name="{{ $student->name }}" data-number="{{ $student->student_number }}">
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
                        <div class="card-footer bg-white">
                            <small class="text-muted">Click on a student or the arrow button to select a student for seat assignment.</small>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Seat Plan Configuration -->
                <div class="col-md-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Seat Plan Configuration</h3>
                        </div>
                        <form action="{{ route('faculty.seatplan.store', [
                            'sectionId' => $section->id,
                            'subjectId' => $subject->id,
                            'schoolYear' => $schoolYear,
                            'semester' => $semester
                        ]) }}" method="POST" id="seatPlanForm">
                            @csrf
                            <div class="card-body">
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
                                    <i class="fas fa-info-circle"></i> To assign a student to a seat, click on a student from the list then click on an empty seat. To remove a student from a seat, click the "X" button on the occupied seat.
                                </div>

                                <div class="seat-plan-container mt-4">
                                    <div class="text-center p-3 bg-secondary text-white mb-3">
                                        <h4>WHITEBOARD</h4>
                                    </div>
                                    <div id="seatGrid" class="seat-grid" style="display: grid; grid-gap: 10px;">
                                        <!-- Seats will be generated here by JavaScript -->
                                    </div>
                                </div>

                                <!-- Hidden input for arrangement data -->
                                <input type="hidden" name="arrangement" id="arrangementData" value="{{ $existingSeatPlan->arrangement ?? '{}' }}">
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Save Seat Plan
                                </button>
                                <a href="{{ route('faculty.classes.details', [
                                    'sectionId' => $section->id,
                                    'subjectId' => $subject->id,
                                    'schoolYear' => $schoolYear,
                                    'semester' => $semester
                                ]) }}" class="btn btn-default">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('styles')
<style>
    .student-item {
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .student-item:hover {
        background-color: #f8f9fa;
    }
    .student-item.selected {
        background-color: #e8f4f8;
        border-left: 3px solid #007bff;
    }
    .seat {
        position: relative;
        transition: all 0.2s ease;
        min-height: 80px;
    }
    .seat:hover {
        transform: scale(1.05);
        box-shadow: 0 0 8px rgba(0,0,0,0.1);
    }
    .remove-student {
        position: absolute;
        top: 5px;
        right: 5px;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        padding: 0;
        line-height: 1;
        font-size: 10px;
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize variables
    let selectedStudent = null;
    let arrangement = {};

    // If an existing seat plan exists, parse its arrangement
    @if(isset($existingSeatPlan) && $existingSeatPlan->arrangement)
        try {
            arrangement = JSON.parse('{!! addslashes($existingSeatPlan->arrangement) !!}');
        } catch(e) {
            console.error("Error parsing existing arrangement: ", e);
            arrangement = {};
        }
    @endif

    // Update hidden input with initial arrangement data
    document.getElementById('arrangementData').value = JSON.stringify(arrangement);

    // Generate grid button click
    document.getElementById('generateGrid').addEventListener('click', function() {
        generateGrid();
    });

    // Clear grid button click
    document.getElementById('clearGrid').addEventListener('click', function() {
        if (confirm('Are you sure you want to clear the entire grid? This will remove all student assignments.')) {
            arrangement = {};
            generateGrid();
            document.getElementById('arrangementData').value = JSON.stringify(arrangement);
        }
    });

    // Add event delegation for student items and assign buttons
    document.getElementById('student-list').addEventListener('click', function(e) {
        // Check if clicked element is a student-item or inside one
        let studentItem = null;

        if (e.target.classList.contains('student-item')) {
            studentItem = e.target;
        } else if (e.target.classList.contains('assign-button') ||
                   e.target.closest('.assign-button')) {
            // If clicked on assign button or its child (icon)
            studentItem = e.target.closest('.student-item');
        } else if (e.target.closest('.student-item')) {
            // If clicked inside a student item
            studentItem = e.target.closest('.student-item');
        }

        if (studentItem) {
            // Remove selected class from all student items
            document.querySelectorAll('.student-item').forEach(item => {
                item.classList.remove('selected');
            });

            // Add selected class to clicked student item
            studentItem.classList.add('selected');

            // Set selected student
            selectedStudent = {
                id: studentItem.dataset.id,
                name: studentItem.dataset.name,
                number: studentItem.dataset.number
            };
        }
    });

    // Add event delegation for seat grid
    document.getElementById('seatGrid').addEventListener('click', function(e) {
        let seat = null;

        // Check if clicked on remove button
        if (e.target.classList.contains('remove-student') ||
            e.target.closest('.remove-student')) {
            e.stopPropagation();
            seat = e.target.closest('.seat');
            if (seat) {
                const seatId = seat.dataset.seatId;
                delete arrangement[seatId];
                updateSeatDisplay(seatId);
                document.getElementById('arrangementData').value = JSON.stringify(arrangement);
            }
            return;
        }

        // Check if clicked on a seat or inside one
        if (e.target.classList.contains('seat')) {
            seat = e.target;
        } else if (e.target.closest('.seat')) {
            seat = e.target.closest('.seat');
        }

        if (seat && selectedStudent) {
            const seatId = seat.dataset.seatId;

            // Check if this student is already assigned elsewhere
            for (const id in arrangement) {
                if (arrangement[id] === selectedStudent.id) {
                    delete arrangement[id];
                    updateSeatDisplay(id);
                }
            }

            // Assign student to this seat
            arrangement[seatId] = selectedStudent.id;
            updateSeatDisplay(seatId);

            // Clear selection
            document.querySelectorAll('.student-item').forEach(item => {
                item.classList.remove('selected');
            });
            selectedStudent = null;

            // Update hidden input
            document.getElementById('arrangementData').value = JSON.stringify(arrangement);
        }
    });

    // Handle form submission
    document.getElementById('seatPlanForm').addEventListener('submit', function(e) {
        // Update arrangement data one final time
        document.getElementById('arrangementData').value = JSON.stringify(arrangement);

        // Optional: Validate if empty
        if (Object.keys(arrangement).length === 0) {
            if (!confirm('You haven\'t assigned any students to seats. Continue anyway?')) {
                e.preventDefault();
            }
        }
    });

    // Function to generate the seat grid
    function generateGrid() {
        const rows = parseInt(document.getElementById('rows').value);
        const columns = parseInt(document.getElementById('columns').value);

        if (isNaN(rows) || isNaN(columns) || rows <= 0 || columns <= 0) {
            alert('Please enter valid numbers for rows and columns');
            return;
        }

        const grid = document.getElementById('seatGrid');
        grid.innerHTML = '';

        // Set grid styling
        grid.style.display = 'grid';
        grid.style.gridTemplateColumns = `repeat(${columns}, 1fr)`;
        grid.style.gridGap = '10px';

        // Create seats
        for (let row = 0; row < rows; row++) {
            for (let col = 0; col < columns; col++) {
                const seatId = `${row}-${col}`;
                const seat = document.createElement('div');
                seat.className = 'seat card bg-light';
                seat.dataset.seatId = seatId;
                seat.style.cursor = 'pointer';
                seat.style.minHeight = '80px';

                // Initial empty seat content
                seat.innerHTML = `
                    <div class="card-body p-2 text-center">
                        <i class="fas fa-chair fa-2x"></i>
                        <div class="student-name mt-1"></div>
                    </div>
                `;

                grid.appendChild(seat);
            }
        }

        // Populate seats from existing arrangement
        for (const seatId in arrangement) {
            updateSeatDisplay(seatId);
        }

        // Update hidden input
        document.getElementById('arrangementData').value = JSON.stringify(arrangement);
    }

    // Function to update a seat's display
    function updateSeatDisplay(seatId) {
        const seat = document.querySelector(`.seat[data-seat-id="${seatId}"]`);
        if (!seat) return;

        const studentId = arrangement[seatId];

        if (studentId) {
            // Find student details
            const studentItem = document.querySelector(`.student-item[data-id="${studentId}"]`);
            if (!studentItem) return;

            const studentName = studentItem.dataset.name;
            const studentNumber = studentItem.dataset.number;

            // Update seat appearance
            seat.className = 'seat card bg-success text-white';
            seat.innerHTML = `
                <div class="card-body p-2 text-center position-relative">
                    <strong>${studentName}</strong>
                    <small class="d-block">${studentNumber}</small>
                    <button type="button" class="remove-student btn btn-sm btn-danger">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
        } else {
            // Reset to empty seat
            seat.className = 'seat card bg-light';
            seat.innerHTML = `
                <div class="card-body p-2 text-center">
                    <i class="fas fa-chair fa-2x"></i>
                    <div class="student-name mt-1"></div>
                </div>
            `;
        }
    }

    // Generate initial grid on page load
    generateGrid();
});
</script>
@endsection
