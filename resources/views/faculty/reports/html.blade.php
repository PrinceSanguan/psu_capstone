<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $subject->code }} - {{ $section->name }} Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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
            display: flex;
            flex-wrap: wrap;
        }
        .stats-box {
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
        .print-button {
            position: fixed;
            top: 10px;
            right: 10px;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 1000;
        }
        .print-button:hover {
            background-color: #45a049;
        }
        .export-buttons {
            position: fixed;
            top: 60px;
            right: 10px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .export-button {
            display: inline-block;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .export-pdf {
            background-color: #f44336;
            color: white;
        }
        .export-excel {
            background-color: #4CAF50;
            color: white;
        }
        @media print {
            .print-button, .export-buttons {
                display: none;
            }
            body {
                margin: 0;
                padding: 0;
            }
            @page {
                size: A4;
                margin: 1cm;
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">Print Report</button>

    <div class="export-buttons">
        <a href="#" onclick="exportAsPDF()" class="export-button export-pdf">Save as PDF</a>
        <a href="#" onclick="exportAsExcel()" class="export-button export-excel">Save as Excel</a>
    </div>

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
                <p><!DOCTYPE html>
                    <html>
                    <head>
                        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                        <title>{{ $subject->code }} - {{ $section->name }} Report</title>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
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
                                display: flex;
                                flex-wrap: wrap;
                            }
                            .stats-box {
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
                            .print-button {
                                position: fixed;
                                top: 10px;
                                right: 10px;
                                padding: 10px 15px;
                                background-color: #4CAF50;
                                color: white;
                                border: none;
                                border-radius: 4px;
                                cursor: pointer;
                                font-weight: bold;
                                box-shadow: 0 2px 5px rgba(0,0,0,0.2);
                                z-index: 1000;
                            }
                            .print-button:hover {
                                background-color: #45a049;
                            }
                            .export-buttons {
                                position: fixed;
                                top: 60px;
                                right: 10px;
                                z-index: 1000;
                                display: flex;
                                flex-direction: column;
                                gap: 10px;
                            }
                            .export-button {
                                display: inline-block;
                                padding: 8px 15px;
                                border: none;
                                border-radius: 4px;
                                cursor: pointer;
                                font-weight: bold;
                                text-decoration: none;
                                text-align: center;
                                box-shadow: 0 2px 5px rgba(0,0,0,0.2);
                            }
                            .export-pdf {
                                background-color: #f44336;
                                color: white;
                            }
                            .export-excel {
                                background-color: #4CAF50;
                                color: white;
                            }
                            @media print {
                                .print-button, .export-buttons {
                                    display: none;
                                }
                                body {
                                    margin: 0;
                                    padding: 0;
                                }
                                @page {
                                    size: A4;
                                    margin: 1cm;
                                }
                            }
                        </style>
                    </head>
                    <body>
                        <button class="print-button" onclick="window.print()">Print Report</button>

                        <div class="export-buttons">
                            <a href="#" onclick="exportAsPDF()" class="export-button export-pdf">Save as PDF</a>
                            <a href="#" onclick="exportAsExcel()" class="export-button export-excel">Save as Excel</a>
                        </div>

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
                                    <svg width="400" height="200" viewBox="0 0 400 200">
                                        <!-- Passing/Failing Pie Chart -->
                                        <circle cx="100" cy="100" r="90" fill="#f2f2f2" stroke="#ccc" />

                                        @php
                                            $passingPercentage = $stats['passing_percentage'];
                                            $passingAngle = 3.6 * $passingPercentage;
                                            $failingAngle = 360 - $passingAngle;

                                            // Convert to radians
                                            $passingEndRad = $passingAngle * (M_PI / 180);

                                            // Calculate coordinates
                                            $x1 = 100 + 90 * sin($passingEndRad);
                                            $y1 = 100 - 90 * cos($passingEndRad);
                                        @endphp

                                        <!-- Passing slice (green) -->
                                        <path d="M 100 100 L 100 10 A 90 90 0 {{ $passingPercentage > 50 ? 1 : 0 }} 1 {{ $x1 }} {{ $y1 }} Z" fill="#28a745" />

                                        <!-- Legend -->
                                        <rect x="220" y="70" width="20" height="20" fill="#28a745" />
                                        <text x="250" y="85" font-family="Arial" font-size="14">Passing: {{ number_format($passingPercentage, 1) }}%</text>

                                        <rect x="220" y="100" width="20" height="20" fill="#dc3545" />
                                        <text x="250" y="115" font-family="Arial" font-size="14">Failing: {{ number_format($stats['failing_percentage'], 1) }}%</text>

                                        <text x="100" y="180" font-family="Arial" font-size="14" text-anchor="middle">Performance Summary</text>
                                    </svg>
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
                                <p><strong>{{ $user->name }}</strong></p>
                                <p style="margin-top: 0;">Faculty</p>
                            </div>
                            <div class="signature-box">
                                <div class="signature-line"></div>
                                <p><strong>Department Chair</strong></p>
                                <p style="margin-top: 0;">Department of Computer Science</p>
                            </div>
                        </div>

                        <script>
                            // Function to handle PDF export
                            function exportAsPDF() {
                                // First method: Use built-in print dialog configured for PDF
                                window.print();

                                // Alternative method: Use current URL but with format=pdf parameter
                                // const currentUrl = window.location.href;
                                // const pdfUrl = currentUrl.includes('?')
                                //     ? currentUrl + '&format=pdf'
                                //     : currentUrl + '?format=pdf';
                                // window.open(pdfUrl, '_blank');
                            }

                            // Function to handle Excel export
                            function exportAsExcel() {
                                // Get the current URL
                                const currentUrl = window.location.href;

                                // Extract the report ID from the URL (assuming URL format like /faculty/reports/view/123)
                                const urlParts = currentUrl.split('/');
                                const reportId = urlParts[urlParts.length - 1];

                                // Construct the Excel export URL
                                const excelUrl = '/faculty/reports/export-excel/' + reportId;

                                // Redirect to the Excel export URL
                                window.location.href = excelUrl;
                            }
                        </script>
                    </body>
                    </html>
