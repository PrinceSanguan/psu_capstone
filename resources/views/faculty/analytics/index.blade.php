@extends('faculty.layout.app')

@section('content')
<div class="container-fluid">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Student Analytics: {{ $subject->code }} - {{ $section->name }}</h1>
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
                        <li class="breadcrumb-item active">Analytics</li>
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

            <!-- Class Overview Card -->
            <div class="row">
                <div class="col-md-4">
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
                                <li class="list-group-item"><b>Total Students:</b> <span class="float-right">{{ is_countable($students) ? count($students) : 0 }}</span></li>
                            </ul>
                            <!-- Inside the blade template where grading system is displayed -->
                            @if(isset($gradingSystem))
                            <div class="mt-4">
                                <h5>Applied Grading System</h5>
                                <div class="progress-group">
                                    <span class="progress-text">Quizzes</span>
                                    <span class="float-right">{{ $gradingSystem->quiz_percentage }}%</span>
                                    <div class="progress">
                                        <div class="progress-bar bg-primary" style="width: {{ $gradingSystem->quiz_percentage }}%"></div>
                                    </div>
                                </div>
                                <div class="progress-group">
                                    <span class="progress-text">Unit Tests</span>
                                    <span class="float-right">{{ $gradingSystem->unit_test_percentage }}%</span>
                                    <div class="progress">
                                        <div class="progress-bar bg-info" style="width: {{ $gradingSystem->unit_test_percentage }}%"></div>
                                    </div>
                                </div>
                                <div class="progress-group">
                                    <span class="progress-text">Activities</span>
                                    <span class="float-right">{{ $gradingSystem->activity_percentage }}%</span>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" style="width: {{ $gradingSystem->activity_percentage }}%"></div>
                                    </div>
                                </div>
                                <div class="progress-group">
                                    <span class="progress-text">Exams</span>
                                    <span class="float-right">{{ $gradingSystem->exam_percentage }}%</span>
                                    <div class="progress">
                                        <div class="progress-bar bg-warning" style="width: {{ $gradingSystem->exam_percentage }}%"></div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Performance Summary</h3>
                        </div>
                        <div class="card-body">
                            <div class="chart-responsive">
                                <canvas id="pieChart" height="200"></canvas>
                            </div>
                            <div class="mt-4">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-success">Passing (≥75%)</span>
                                    <span class="text-success">{{ $stats['passing_percentage'] }}% ({{ $stats['passing_count'] }})</span>
                                </div>
                                <div class="progress mb-3" style="height: 10px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $stats['passing_percentage'] }}%"></div>
                                </div>

                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-danger">Failing (<75%)</span>
                                    <span class="text-danger">{{ $stats['failing_percentage'] }}% ({{ $stats['failing_count'] }})</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $stats['failing_percentage'] }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Actions</h3>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('faculty.reports.generate', [
                                'sectionId' => $section->id,
                                'subjectId' => $subject->id,
                                'schoolYear' => $schoolYear,
                                'semester' => $semester
                            ]) }}" class="btn btn-info btn-block mb-2">
                                <i class="fas fa-file-pdf"></i> Generate Report
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
                </div>

                <div class="col-md-8">
                    <!-- Overall Performance Chart -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Student Performance Overview</h3>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="barChart" style="min-height: 250px; height: 300px; max-height: 300px; width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Assessment Performance Table -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Student Grade Details</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Student</th>
                                            <th>Midterm Grade</th>
                                            <th>Final Grade</th>
                                            <th>Overall Grade</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($studentGrades as $grade)
                                            <tr>
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
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No student grades found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Analytics -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Assessment Analytics</h3>
                        </div>
                        <div class="card-body">
                            @if(is_countable($assessments) && count($assessments) > 0)
                                <div class="chart">
                                    <canvas id="assessmentChart" style="min-height: 250px; height: 300px; max-height: 300px; width: 100%;"></canvas>
                                </div>
                                <div class="mt-4">
                                    <h5>Assessment Summary</h5>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Assessment</th>
                                                    <th>Type</th>
                                                    <th>Average Score (%)</th>
                                                    <th>Highest</th>
                                                    <th>Lowest</th>
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
                                                        $avgPercent = $assessment->max_score > 0 ? ($avg / $assessment->max_score) * 100 : 0;
                                                        $highest = $count > 0 ? $scores->max('score') : 0;
                                                        $lowest = $count > 0 ? $scores->min('score') : 0;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $assessment->title }}</td>
                                                        <td>{{ ucfirst(str_replace('_', ' ', $assessment->type)) }}</td>
                                                        <td>{{ number_format($avgPercent, 2) }}%</td>
                                                        <td>{{ $highest }}/{{ $assessment->max_score }}</td>
                                                        <td>{{ $lowest }}/{{ $assessment->max_score }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> No assessments have been created yet.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pie Chart for Passing/Failing ratio
    const pieChartCanvas = document.getElementById('pieChart').getContext('2d');
    new Chart(pieChartCanvas, {
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

    // Bar Chart for Student Performance
    const studentNames = [
        @forelse($studentGrades as $grade)
            '{{ substr($grade['student']->name, 0, 15) }}...',
        @empty
        @endforelse
    ];

    const midtermGrades = [
        @forelse($studentGrades as $grade)
            {{ $grade['midterm_grade'] }},
        @empty
        @endforelse
    ];

    const finalGrades = [
        @forelse($studentGrades as $grade)
            {{ $grade['final_grade'] }},
        @empty
        @endforelse
    ];

    const barChartCanvas = document.getElementById('barChart').getContext('2d');
    new Chart(barChartCanvas, {
        type: 'bar',
        data: {
            labels: studentNames,
            datasets: [
                {
                    label: 'Midterm Grade',
                    backgroundColor: 'rgba(60,141,188,0.8)',
                    data: midtermGrades
                },
                {
                    label: 'Final Grade',
                    backgroundColor: 'rgba(210,214,222,0.8)',
                    data: finalGrades
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        stepSize: 20
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });

    // Assessment Performance Chart
    @if(is_countable($assessments) && count($assessments) > 0)
    const assessmentTitles = [
        @foreach($assessments as $assessment)
            '{{ substr($assessment->title, 0, 15) }}...',
        @endforeach
    ];

    const assessmentAverages = [
        @foreach($assessments as $assessment)
            @php
                $scores = DB::table('student_scores')
                    ->where('assessment_id', $assessment->id)
                    ->get();

                $count = $scores->count();
                $avg = $count > 0 ? $scores->sum('score') / $count : 0;
                $avgPercent = $assessment->max_score > 0 ? ($avg / $assessment->max_score) * 100 : 0;
            @endphp
            {{ number_format($avgPercent, 2) }},
        @endforeach
    ];

    const assessmentColors = Array(assessmentTitles.length).fill().map((_, i) =>
        `hsl(${i * (360 / assessmentTitles.length)}, 70%, 60%)`
    );

    const assessmentChartCanvas = document.getElementById('assessmentChart').getContext('2d');
    new Chart(assessmentChartCanvas, {
        type: 'bar',
        data: {
            labels: assessmentTitles,
            datasets: [{
                label: 'Average Score (%)',
                backgroundColor: assessmentColors,
                data: assessmentAverages
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        stepSize: 20
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
    @endif
});
</script>
@endsection
