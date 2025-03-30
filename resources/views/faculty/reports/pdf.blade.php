<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $subject->code }} - {{ $section->name }} Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 14px;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        h1, h2, h3, h4 {
            margin-top: 0;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header h2 {
            font-size: 18px;
            font-weight: normal;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-section h3 {
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .comments-section {
            margin: 20px 0;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        .signature-section {
            margin-top: 40px;
            text-align: center;
        }
        .signature-line {
            display: inline-block;
            border-top: 1px solid #000;
            width: 200px;
            margin-bottom: 5px;
        }
        .signature-box {
            display: inline-block;
            width: 45%;
            margin: 0 2%;
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 3px 7px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 4px;
        }
        .badge-success {
            background-color: #dff0d8;
            color: #3c763d;
        }
        .badge-danger {
            background-color: #f2dede;
            color: #a94442;
        }
        .page-break {
            page-break-after: always;
        }
        .performance-summary {
            margin-bottom: 20px;
        }
        .stats-box {
            display: inline-block;
            width: 48%;
            margin-right: 1%;
            padding: 10px;
            border: 1px solid #ddd;
            box-sizing: border-box;
            vertical-align: top;
        }
        .chart-container {
            text-align: center;
            margin: 20px 0;
        }
        .chart-placeholder {
            height: 200px;
            background-color: #f2f2f2;
            border: 1px dashed #ccc;
            text-align: center;
            line-height: 200px;
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-warning {
            color: #8a6d3b;
            background-color: #fcf8e3;
            border-color: #faebcc;
        }
    </style>
</head>
<body>
    <div class="header">
        @if($reportType == 'class_grades')
            <h1>Class Grades Summary: {{ $subject->code }} - {{ $subject->name }}</h1>
        @elseif($reportType == 'student_performance')
            <h1>Student Performance Report: {{ $subject->code }} - {{ $subject->name }}</h1>
        @elseif($reportType == 'assessment_results')
            <h1>Assessment Results Analysis: {{ $subject->code }} - {{ $subject->name }}</h1>
        @elseif($reportType == 'attendance')
            <h1>Attendance Records: {{ $subject->code }} - {{ $subject->name }}</h1>
        @else
            <h1>{{ $subject->code }} - {{ $subject->name }}</h1>
        @endif
        <h2>{{ $section->name }} | {{ $schoolYear }} | {{ $semester }} Semester</h2>
        <p>Generated on {{ date('F d, Y') }}</p>
    </div>

    <div class="info-section">
        <h3>Class Overview</h3>

        <div class="performance-summary">
            <div class="stats-box">
                <h4>Class Information</h4>
                <p><strong>Total Students:</strong> {{ $stats['total_students'] }}</p>
                <p><strong>Passing:</strong> {{ $stats['passing_count'] }} ({{ number_format($stats['passing_percentage'], 2) }}%)</p>
                <p><strong>Failing:</strong> {{ $stats['failing_count'] }} ({{ number_format($stats['failing_percentage'], 2) }}%)</p>
                @php
                    $totalGrade = 0;
                    foreach($studentGrades as $grade) {
                        $totalGrade += $grade['overall_grade'];
                    }
                    $classAverage = count($studentGrades) > 0 ? $totalGrade / count($studentGrades) : 0;
                @endphp
                <p><strong>Class Average:</strong> {{ number_format($classAverage, 2) }}%</p>
                <p><strong>Total Assessments:</strong> {{ count($assessments) }}</p>
            </div>
            <div class="stats-box">
                <h4>Grading System</h4>
                <p><strong>Quizzes:</strong> {{ $gradingSystem->quiz_percentage }}%</p>
                <p><strong>Unit Tests:</strong> {{ $gradingSystem->unit_test_percentage }}%</p>
                <p><strong>Activities:</strong> {{ $gradingSystem->activity_percentage }}%</p>
                <p><strong>Exams:</strong> {{ $gradingSystem->exam_percentage }}%</p>
            </div>
        </div>

        @if($includeCharts)
            <div class="chart-container">
                <div class="chart-placeholder">
                    [Performance Chart - Passing {{ $stats['passing_percentage'] }}% vs Failing {{ $stats['failing_percentage'] }}%]
                </div>
                <p><small>Note: Charts are represented as placeholders in PDF format</small></p>
            </div>
        @endif
    </div>

    <div class="info-section">
        <h3>Student Grades</h3>
        <table>
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

    <div class="info-section">
        <h3>Assessment Breakdown</h3>
        @if(count($assessments) > 0)
            <table>
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
        @else
            <div class="alert alert-warning">
                No assessments have been created yet.
            </div>
        @endif
    </div>

    @if($includeAttendance)
    <div class="info-section">
        <h3>Attendance Records</h3>
        <div class="alert alert-warning">
            Attendance records are not available for this report.
        </div>
    </div>
    @endif

    @if($includeComments)
    <div class="comments-section">
        <h3>Faculty Comments</h3>
        <p>{{ $teacherComments ? $teacherComments : 'No additional comments provided.' }}</p>
    </div>
    @endif

    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line"></div>
            <p><strong>{{ Auth::user()->name }}</strong></p>
            <p style="margin-top: 0;">Faculty</p>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
            <p><strong>Department Chair</strong></p>
            <p style="margin-top: 0;">Department of Computer Science</p>
        </div>
    </div>
</body>
</html>
