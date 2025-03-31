@extends('faculty.layout.app')

@section('content')
<div class="container-fluid">
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
                        <li class="breadcrumb-item">
                            <a href="{{ route('faculty.classes.details', [
                                'sectionId' => $section->id,
                                'subjectId' => $subject->id,
                                'schoolYear' => $schoolYear,
                                'semester' => $semester
                            ]) }}">
                                {{ $subject->code }} - {{ $section->name }}
                            </a>
                        </li>
                        <li class="breadcrumb-item active">View Seat Plan</li>
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
                <!-- Left Column: Class Info & Actions -->
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
                                <li class="list-group-item"><b>Seat Plan Size:</b> <a class="float-right">{{ $seatPlan->rows }} x {{ $seatPlan->columns }}</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Actions</h3>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('faculty.seatplan.create', [
                                'sectionId' => $section->id,
                                'subjectId' => $subject->id,
                                'schoolYear' => $schoolYear,
                                'semester' => $semester
                            ]) }}" class="btn btn-warning btn-block mb-2">
                                <i class="fas fa-edit"></i> Edit Seat Plan
                            </a>
                            <a href="{{ route('faculty.classes.details', [
                                'sectionId' => $section->id,
                                'subjectId' => $subject->id,
                                'schoolYear' => $schoolYear,
                                'semester' => $semester
                            ]) }}" class="btn btn-default btn-block">
                                <i class="fas fa-arrow-left"></i> Back to Class Details
                            </a>
                        </div>
                    </div>

                    <!-- Print Instructions -->
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Print Seat Plan</h3>
                        </div>
                        <div class="card-body">
                            <p>To print this seat plan:</p>
                            <ol>
                                <li>Click the print button below</li>
                                <li>Set paper orientation to landscape</li>
                                <li>Set scale to "Fit to page"</li>
                            </ol>
                            <button class="btn btn-success btn-block" onclick="window.print()">
                                <i class="fas fa-print"></i> Print Seat Plan
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Seat Plan Display -->
                <div class="col-md-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Seat Plan for {{ $section->name }} - {{ $subject->name }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="text-center p-3 bg-secondary text-white mb-3">
                                <h4>WHITEBOARD (Front of Room)</h4>
                            </div>

                            <div id="seatPlanDisplay" class="seat-grid mb-4">
                                <!-- Seat plan will be generated here by JavaScript -->
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="float-right">
                                        <div class="d-inline-block mr-3">
                                            <i class="fas fa-square text-success"></i> Occupied Seat
                                        </div>
                                        <div class="d-inline-block">
                                            <i class="fas fa-square text-light border"></i> Empty Seat
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('styles')
<style>
    .seat-grid {
        display: grid;
        grid-gap: 10px;
    }

    .seat {
        min-height: 80px;
        border-radius: 4px;
        border: 1px solid #ddd;
    }

    .seat.occupied {
        background-color: #28a745;
        color: white;
    }

    .seat.empty {
        background-color: #f8f9fa;
    }

    .student-number {
        font-size: 0.8em;
        opacity: 0.8;
    }

    /* Print Styles */
    @media print {
        body {
            background-color: white !important;
        }

        .main-header, .main-sidebar, .main-footer, .card-header, .breadcrumb, .actions-card, .no-print {
            display: none !important;
        }

        .content-wrapper {
            background-color: white !important;
            margin-left: 0 !important;
        }

        .card {
            box-shadow: none !important;
            border: none !important;
        }

        .seat-grid {
            grid-gap: 5px !important;
        }

        .seat {
            border: 1px solid #aaa !important;
        }
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Parse the arrangement data
    let arrangementData = {};
    try {
        arrangementData = @json($arrangementData) || {};
    } catch (e) {
        console.error('Error parsing arrangement data:', e);
        arrangementData = {};
    }

    // Create a map of students by ID for easy lookup
    const students = @json($students);

    // Generate the seat plan display
    function generateSeatPlanDisplay() {
        const seatPlanDisplay = document.getElementById('seatPlanDisplay');
        const rows = {{ $seatPlan->rows }};
        const columns = {{ $seatPlan->columns }};

        // Set grid layout
        seatPlanDisplay.style.display = 'grid';
        seatPlanDisplay.style.gridTemplateColumns = `repeat(${columns}, 1fr)`;

        // Clear existing content
        seatPlanDisplay.innerHTML = '';

        // Generate seats
        for (let row = 0; row < rows; row++) {
            for (let col = 0; col < columns; col++) {
                const seatId = `${row}-${col}`;
                const seat = document.createElement('div');
                seat.className = 'seat p-2 text-center';

                // Check if seat is occupied
                const studentId = arrangementData[seatId];
                if (studentId && students[studentId]) {
                    // Occupied seat with student info
                    seat.className += ' occupied';
                    seat.innerHTML = `
                        <strong>${students[studentId].name}</strong>
                        <div class="student-number">${students[studentId].student_number || ''}</div>
                    `;
                } else {
                    // Empty seat
                    seat.className += ' empty';
                    seat.innerHTML = `
                        <i class="fas fa-chair text-muted"></i>
                        <div>Empty</div>
                    `;
                }

                // Add seat position indicator (for debugging or reference)
                const posLabel = document.createElement('small');
                posLabel.className = 'position-absolute text-muted';
                posLabel.style.bottom = '2px';
                posLabel.style.right = '5px';
                posLabel.style.fontSize = '0.7em';
                posLabel.textContent = `R${row+1}C${col+1}`;
                seat.appendChild(posLabel);

                // Add to the grid
                seatPlanDisplay.appendChild(seat);
            }
        }
    }

    // Generate the initial display
    generateSeatPlanDisplay();
});
</script>
@endsection
