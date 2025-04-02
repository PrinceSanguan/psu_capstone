@extends('layouts.client')

@section('title', 'My Grades')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">My Grades</h1>

    @if(empty($subjectGrades))
        <div class="card shadow mb-4">
            <div class="card-body">
                <p class="text-center">No grade records available.</p>
            </div>
        </div>
    @else
        <!-- Overall GPA Card -->
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Overall Average</div>
                                @php
                                    $totalGrades = 0;
                                    $totalSubjects = count($subjectGrades);

                                    foreach($subjectGrades as $grade) {
                                        $totalGrades += $grade['overall_grade'];
                                    }

                                    $gpa = $totalSubjects > 0 ? $totalGrades / $totalSubjects : 0;
                                @endphp
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($gpa, 2) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Subjects Passed</div>
                                @php
                                    $passingCount = 0;

                                    foreach($subjectGrades as $grade) {
                                        if($grade['overall_grade'] >= 75) {
                                            $passingCount++;
                                        }
                                    }
                                @endphp
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $passingCount }} / {{ $totalSubjects }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Subjects Failed</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalSubjects - $passingCount }} / {{ $totalSubjects }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Overall Performance</div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                            @php
                                                $performancePercentage = $totalSubjects > 0 ? ($passingCount / $totalSubjects) * 100 : 0;
                                            @endphp
                                            {{ number_format($performancePercentage, 0) }}%
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="progress progress-sm mr-2">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ $performancePercentage }}%"
                                                aria-valuenow="{{ $performancePercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grade Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Grade Report</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Subject Code</th>
                                <th>Subject Name</th>
                                <th>Section</th>
                                <th>Faculty</th>
                                <th>Midterm</th>
                                <th>Final</th>
                                <th>Overall</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subjectGrades as $grade)
                                <tr>
                                    <td>{{ $grade['subject_code'] }}</td>
                                    <td>{{ $grade['subject_name'] }}</td>
                                    <td>{{ $grade['section_name'] }}</td>
                                    <td>{{ $grade['faculty_name'] }}</td>
                                    <td>{{ number_format($grade['midterm_grade'], 2) }}</td>
                                    <td>{{ number_format($grade['final_grade'], 2) }}</td>
                                    <td>{{ number_format($grade['overall_grade'], 2) }}</td>
                                    <td>
                                        @if($grade['overall_grade'] >= 75)
                                            <span class="badge badge-success">Passing</span>
                                        @else
                                            <span class="badge badge-danger">Failing</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('client.classes.details', [
                                            'sectionId' => $grade['section_id'],
                                            'subjectId' => $grade['subject_id'],
                                            'schoolYear' => $grade['school_year'],
                                            'semester' => $grade['semester']
                                        ]) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Grade Distribution Chart -->
        <div class="row">
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Grade Distribution</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-bar">
                            <canvas id="gradeDistributionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Grade Summary</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie pt-4">
                            <canvas id="gradeSummaryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#dataTable').DataTable();

        @if(!empty($subjectGrades))
            // Prepare data for grade distribution chart
            const grades = [
                @foreach($subjectGrades as $grade)
                    {{ $grade['overall_grade'] }},
                @endforeach
            ];

            const subjectLabels = [
                @foreach($subjectGrades as $grade)
                    "{{ $grade['subject_code'] }}",
                @endforeach
            ];

            // Grade distribution chart
            const ctxBar = document.getElementById('gradeDistributionChart').getContext('2d');
            const gradeDistributionChart = new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: subjectLabels,
                    datasets: [{
                        label: 'Overall Grade',
                        data: grades,
                        backgroundColor: grades.map(grade => grade >= 75 ? '#1cc88a' : '#e74a3b'),
                        borderColor: grades.map(grade => grade >= 75 ? '#169c6c' : '#c53030'),
                        borderWidth: 1
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            grid: {
                                color: "rgba(0, 0, 0, 0.05)"
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Grade summary chart (Passing vs Failing)
            const ctxPie = document.getElementById('gradeSummaryChart').getContext('2d');
            const gradeSummaryChart = new Chart(ctxPie, {
                type: 'doughnut',
                data: {
                    labels: ['Passing', 'Failing'],
                    datasets: [{
                        data: [{{ $passingCount }}, {{ $totalSubjects - $passingCount }}],
                        backgroundColor: ['#1cc88a', '#e74a3b'],
                        hoverBackgroundColor: ['#169c6c', '#c53030'],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        @endif
    });
</script>
@endsection
