@extends('faculty.layout.app')

@section('content')
<div class="container-fluid">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Generate Reports: {{ $subject->code }} - {{ $section->name }}</h1>
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
                        <li class="breadcrumb-item active">Generate Reports</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success">
                    {!! session('success') !!}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info">
                    {{ session('info') }}
                </div>
            @endif

            <div class="row">
                <div class="col-md-4">
                    <!-- Class Information Card -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Class Information</h3>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item"><b>Section:</b> <span class="float-right">{{ $section->name }}</span></li>
                                <li class="list-group-item"><b>Subject:</b> <span class="float-right">{{ $subject->name }}</span></li>
                                <li class="list-group-item"><b>Code:</b> <span class="float-right">{{ $subject->code }}</span></li>
                                <li class="list-group-item"><b>School Year:</b> <span class="float-right">{{ $schoolYear }}</span></li>
                                <li class="list-group-item"><b>Semester:</b> <span class="float-right">{{ $semester }}</span></li>
                                <li class="list-group-item"><b>Total Students:</b> <span class="float-right">{{ count($students) }}</span></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Report Options Card -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Report Options</h3>
                        </div>
                        <div class="card-body">
                            <form id="reportForm" method="POST" action="{{ route('faculty.reports.download', [
                                'sectionId' => $section->id,
                                'subjectId' => $subject->id,
                                'schoolYear' => $schoolYear,
                                'semester' => $semester
                            ]) }}">
                                @csrf
                                <div class="form-group">
                                    <label>Report Type</label>
                                    <select class="form-control" id="reportType" name="reportType">
                                        <option value="class_grades">Class Grades Summary</option>
                                        <option value="student_performance">Student Performance</option>
                                        <option value="assessment_results">Assessment Results</option>
                                        <option value="attendance">Attendance Records</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>File Format</label>
                                    <select class="form-control" id="fileFormat" name="fileFormat">
                                        <option value="html">HTML Report</option>
                                        <option value="pdf">PDF Document</option>
                                        <option value="excel">Excel Spreadsheet</option>
                                    </select>
                                    <small class="form-text text-muted">
                                        Note: HTML reports can be printed to PDF using your browser's print function.
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label>Include Components</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="includeCharts" name="includeCharts" value="1" checked>
                                        <label class="form-check-label" for="includeCharts">Charts and Graphs</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="includeComments" name="includeComments" value="1" checked>
                                        <label class="form-check-label" for="includeComments">Teacher Comments</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="includeAttendance" name="includeAttendance" value="1">
                                        <label class="form-check-label" for="includeAttendance">Attendance Data</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="teacherComments">Additional Comments</label>
                                    <textarea class="form-control" id="teacherComments" name="teacherComments" rows="3" placeholder="Add any comments or notes to include in the report..."></textarea>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-success btn-block">
                                        <i class="fas fa-file-download"></i> Generate & Download Report
                                    </button>
                                </div>
                            </form>

                            <div class="mt-2">
                                <a href="#" class="btn btn-info btn-block" id="previewReport">
                                    <i class="fas fa-eye"></i> Preview Report
                                </a>

                                <a href="{{ route('faculty.classes.details', [
                                    'sectionId' => $section->id,
                                    'subjectId' => $subject->id,
                                    'schoolYear' => $schoolYear,
                                    'semester' => $semester
                                ]) }}" class="btn btn-default btn-block mt-3">
                                    <i class="fas fa-arrow-left"></i> Back to Class Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <!-- Report Preview Card -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Report Preview</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                    <i class="fas fa-expand"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="report-preview" id="reportPreview">
                                <!-- Report Header -->
                                <div class="report-header text-center p-4 bg-light border-bottom">
                                    <h2>{{ $subject->code }} - {{ $subject->name }}</h2>
                                    <h3>{{ $section->name }} | {{ $schoolYear }} | {{ $semester }} Semester</h3>
                                    <p class="text-muted">Generated on {{ date('F d, Y') }}</p>
                                </div>

                                <!-- Report Content -->
                                <div class="report-content p-4">
                                    <div class="mb-4">
                                        <h4>Class Overview</h4>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card card-primary">
                                                    <div class="card-header">
                                                        <h5 class="card-title">Performance Summary</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="chart-responsive">
                                                            <canvas id="reportPieChart" height="200"></canvas>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-6 text-center">
                                                                <h5 class="text-success">{{ $stats['passing_count'] }}</h5>
                                                                <span>Passing</span>
                                                            </div>
                                                            <div class="col-6 text-center">
                                                                <h5 class="text-danger">{{ $stats['failing_count'] }}</h5>
                                                                <span>Failing</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Total Students</span>
                                                        <span class="info-box-number">{{ count($students) }}</span>
                                                    </div>
                                                </div>
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-success"><i class="fas fa-chart-line"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Class Average</span>
                                                        <span class="info-box-number">
                                                            @php
                                                                $totalGrade = 0;
                                                                foreach($studentGrades as $grade) {
                                                                    $totalGrade += $grade['overall_grade'];
                                                                }
                                                                $classAverage = count($studentGrades) > 0 ? $totalGrade / count($studentGrades) : 0;
                                                            @endphp
                                                            {{ number_format($classAverage, 2) }}%
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-warning"><i class="fas fa-tasks"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Total Assessments</span>
                                                        <span class="info-box-number">{{ count($assessments) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <h4>Student Grades</h4>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Student Number</th>
                                                        <th>Student Name</th>
                                                        <th>Midterm</th>
                                                        <th>Final</th>
                                                        <th>Overall</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($studentGrades as $index => $grade)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $grade['student']->student_number }}</td>
                                                            <td>{{ $grade['student']->name }}</td>
                                                            <td>{{ number_format($grade['midterm_grade'], 2) }}</td>
                                                            <td>{{ number_format($grade['final_grade'], 2) }}</td>
                                                            <td>{{ number_format($grade['overall_grade'], 2) }}</td>
                                                            <td>
                                                                @if($grade['status'] == 'Passing')
                                                                    <span class="badge badge-success">Passing</span>
                                                                @else
                                                                    <span class="badge badge-danger">Failing</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <h4>Assessment Breakdown</h4>
                                        @if(count($assessments) > 0)
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Assessment</th>
                                                            <th>Type</th>
                                                            <th>Term</th>
                                                            <th>Max Score</th>
                                                            <th>Class Average</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($assessments as $assessment)
                                                            @php
                                                                $scores = DB::table('student_scores')
                                                                    ->where('assessment_id', $assessment->id)
                                                                    ->get();

                                                                $count = $scores->count();
                                                                $avg = $count > 0 ? $scores->sum('score') / $count : 0;
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $assessment->title }}</td>
                                                                <td>{{ ucfirst(str_replace('_', ' ', $assessment->type)) }}</td>
                                                                <td>{{ ucfirst($assessment->term) }}</td>
                                                                <td>{{ $assessment->max_score }}</td>
                                                                <td>{{ number_format($avg, 2) }} ({{ number_format(($avg / $assessment->max_score) * 100, 2) }}%)</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle"></i> No assessments have been created yet.
                                        </div>
                                        @endif
                                    </div>

                                    <div class="mt-5 comments-section" id="commentsSection">
                                        <h4>Faculty Comments</h4>
                                        <div class="card">
                                            <div class="card-body">
                                                <p class="font-italic text-muted" id="commentsPreview">No additional comments provided.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-5 mb-4 text-center">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5 class="mb-3">Prepared by:</h5>
                                                <div class="signature-line"></div>
                                                <p class="mb-0"><strong>{{ Auth::user()->name }}</strong></p>
                                                <p class="text-muted">Faculty</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h5 class="mb-3">Noted by:</h5>
                                                <div class="signature-line"></div>
                                                <p class="mb-0"><strong>Department Chair</strong></p>
                                                <p class="text-muted">Department of Computer Science</p>
                                            </div>
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
.report-preview {
    background-color: white;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.signature-line {
    border-bottom: 1px solid #000;
    width: 80%;
    margin: 30px auto 10px;
}

@media print {
    body * {
        visibility: hidden;
    }
    #reportPreview, #reportPreview * {
        visibility: visible;
    }
    #reportPreview {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize pie chart
    const pieChartCanvas = document.getElementById('reportPieChart').getContext('2d');
    const pieChart = new Chart(pieChartCanvas, {
        type: 'pie',
        data: {
            labels: ['Passing', 'Failing'],
            datasets: [{
                data: [{{ $stats['passing_count'] }}, {{ $stats['failing_count'] }}],
                backgroundColor: ['#28a745', '#dc3545'],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Form interaction
    const reportType = document.getElementById('reportType');
    const fileFormat = document.getElementById('fileFormat');
    const includeCharts = document.getElementById('includeCharts');
    const includeComments = document.getElementById('includeComments');
    const includeAttendance = document.getElementById('includeAttendance');
    const teacherComments = document.getElementById('teacherComments');
    const commentsPreview = document.getElementById('commentsPreview');
    const commentsSection = document.getElementById('commentsSection');

    // Update comments preview
    teacherComments.addEventListener('input', function() {
        if (teacherComments.value.trim() === '') {
            commentsPreview.textContent = 'No additional comments provided.';
        } else {
            commentsPreview.textContent = teacherComments.value;
        }
    });

    // Toggle comments section
    includeComments.addEventListener('change', function() {
        commentsSection.style.display = includeComments.checked ? 'block' : 'none';
    });

    // Preview report button
    document.getElementById('previewReport').addEventListener('click', function(e) {
        e.preventDefault();

        // Update report based on selection
        updateReportPreview();

        // Scroll to report preview
        document.getElementById('reportPreview').scrollIntoView({ behavior: 'smooth' });
    });

    // Function to update report preview based on selection
    function updateReportPreview() {
        // Update report type title
        let reportTitle;
        switch(reportType.value) {
            case 'class_grades':
                reportTitle = 'Class Grades Summary';
                break;
            case 'student_performance':
                reportTitle = 'Student Performance Report';
                break;
            case 'assessment_results':
                reportTitle = 'Assessment Results Analysis';
                break;
            case 'attendance':
                reportTitle = 'Attendance Records';
                break;
        }

        // Update report title
        document.querySelector('.report-header h2').textContent = `${reportTitle}: ${document.querySelector('.report-header h2').textContent.split(':')[1] || '{{ $subject->code }} - {{ $subject->name }}'}`;

        // Update charts visibility
        const chartElements = document.querySelectorAll('.chart-responsive');
        chartElements.forEach(el => {
            el.style.display = includeCharts.checked ? 'block' : 'none';
        });

        // Update comments visibility
        commentsSection.style.display = includeComments.checked ? 'block' : 'none';
    }

    // Initial update
    updateReportPreview();
});
</script>
@endsection
