<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReportExport implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    protected $section;
    protected $subject;
    protected $students;
    protected $gradingSystem;
    protected $assessments;
    protected $studentGrades;
    protected $schoolYear;
    protected $semester;
    protected $stats;
    protected $includeCharts;
    protected $includeComments;
    protected $includeAttendance;
    protected $teacherComments;
    protected $reportType;

    /**
     * Constructor to initialize the export with all necessary data
     */
    public function __construct($section, $subject, $students, $gradingSystem, $assessments, $studentGrades,
                              $schoolYear, $semester, $stats, $includeCharts, $includeComments,
                              $includeAttendance, $teacherComments, $reportType)
    {
        $this->section = $section;
        $this->subject = $subject;
        $this->students = $students;
        $this->gradingSystem = $gradingSystem;
        $this->assessments = $assessments;
        $this->studentGrades = $studentGrades;
        $this->schoolYear = $schoolYear;
        $this->semester = $semester;
        $this->stats = $stats;
        $this->includeCharts = $includeCharts;
        $this->includeComments = $includeComments;
        $this->includeAttendance = $includeAttendance;
        $this->teacherComments = $teacherComments;
        $this->reportType = $reportType;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        // Create collection with header info
        $data = new Collection();

        // Add class info
        $data->push(['Class Information']);
        $data->push(['Section', $this->section->name]);
        $data->push(['Subject', $this->subject->name]);
        $data->push(['Code', $this->subject->code]);
        $data->push(['School Year', $this->schoolYear]);
        $data->push(['Semester', $this->semester]);
        $data->push(['Total Students', $this->stats['total_students']]);
        $data->push(['Passing Students', $this->stats['passing_count'] . ' (' . number_format($this->stats['passing_percentage'], 2) . '%)']);
        $data->push(['Failing Students', $this->stats['failing_count'] . ' (' . number_format($this->stats['failing_percentage'], 2) . '%)']);

        // Add grading system info
        $data->push(['']);
        $data->push(['Grading System']);
        $data->push(['Quizzes', $this->gradingSystem->quiz_percentage . '%']);
        $data->push(['Unit Tests', $this->gradingSystem->unit_test_percentage . '%']);
        $data->push(['Activities', $this->gradingSystem->activity_percentage . '%']);
        $data->push(['Exams', $this->gradingSystem->exam_percentage . '%']);

        // Add blank row as separator
        $data->push(['']);
        $data->push(['']);

        // Add student grades header
        $data->push(['Student Grades']);

        // Add column headers for student grades
        $gradeHeaders = [
            'Student Number',
            'Student Name',
            'Midterm Grade',
            'Final Grade',
            'Overall Grade',
            'Status'
        ];
        $data->push($gradeHeaders);

        // Add student grade data
        foreach ($this->studentGrades as $grade) {
            $data->push([
                $grade['student']->student_number,
                $grade['student']->name,
                number_format($grade['midterm_grade'], 2),
                number_format($grade['final_grade'], 2),
                number_format($grade['overall_grade'], 2),
                $grade['status']
            ]);
        }

        // Add blank row as separator
        $data->push(['']);
        $data->push(['']);

        // Add assessment data if available
        if (count($this->assessments) > 0) {
            $data->push(['Assessment Breakdown']);

            $assessmentHeaders = [
                'Assessment',
                'Type',
                'Term',
                'Max Score',
                'Class Average',
                'Average Percentage'
            ];
            $data->push($assessmentHeaders);

            foreach ($this->assessments as $assessment) {
                $scores = DB::table('student_scores')
                    ->where('assessment_id', $assessment->id)
                    ->get();

                $count = $scores->count();
                $avg = $count > 0 ? $scores->sum('score') / $count : 0;
                $avgPercent = $assessment->max_score > 0 ? ($avg / $assessment->max_score) * 100 : 0;

                $data->push([
                    $assessment->title,
                    ucfirst(str_replace('_', ' ', $assessment->type)),
                    ucfirst($assessment->term),
                    $assessment->max_score,
                    number_format($avg, 2),
                    number_format($avgPercent, 2) . '%'
                ]);
            }
        }

        // Add comments if included
        if ($this->includeComments && !empty($this->teacherComments)) {
            $data->push(['']);
            $data->push(['']);
            $data->push(['Faculty Comments']);
            $data->push([$this->teacherComments]);
        }

        // Add report generation info
        $data->push(['']);
        $data->push(['']);
        $data->push(['Report Information']);
        $data->push(['Generated On', date('F d, Y')]);
        $data->push(['Report Type', $this->getReportTypeName()]);

        return $data;
    }

    /**
     * @return string
     */
    private function getReportTypeName()
    {
        switch ($this->reportType) {
            case 'class_grades':
                return 'Class Grades Summary';
            case 'student_performance':
                return 'Student Performance Report';
            case 'assessment_results':
                return 'Assessment Results Analysis';
            case 'attendance':
                return 'Attendance Records';
            default:
                return 'Class Report';
        }
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // The headings are integrated into the collection() method
        return [];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->subject->code . ' - ' . $this->section->name;
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // Style for titles
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A11')->getFont()->setBold(true);
        $sheet->getStyle('A19')->getFont()->setBold(true);

        // Find the student grades header row and style it
        $studentHeaderRow = 22; // This may need adjustment based on data
        $sheet->getStyle('A' . $studentHeaderRow . ':F' . $studentHeaderRow)->getFont()->setBold(true);

        // Find the assessment header row if assessments exist
        if (count($this->assessments) > 0) {
            $assessmentStartRow = $studentHeaderRow + count($this->studentGrades) + 3;
            $sheet->getStyle('A' . $assessmentStartRow)->getFont()->setBold(true);
            $sheet->getStyle('A' . ($assessmentStartRow + 1) . ':F' . ($assessmentStartRow + 1))->getFont()->setBold(true);
        }

        // Style for comments section if included
        if ($this->includeComments && !empty($this->teacherComments)) {
            $commentsRow = $assessmentStartRow + count($this->assessments) + 3;
            $sheet->getStyle('A' . $commentsRow)->getFont()->setBold(true);
        }

        return [
            // Additional styles as needed
        ];
    }
}
