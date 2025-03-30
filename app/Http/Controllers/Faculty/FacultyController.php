<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subject;
use App\Models\Section;
use App\Models\Assessment;
use App\Models\StudentScore;
use App\Models\Syllabus;
use App\Models\SeatPlan;
use App\Models\GradingSystem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FacultyController extends Controller
{
    /**
     * Display faculty dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Get assigned classes
        $assignedClasses = DB::table('section_subject')
            ->where('faculty_id', $user->id)
            ->join('sections', 'section_subject.section_id', '=', 'sections.id')
            ->join('subjects', 'section_subject.subject_id', '=', 'subjects.id')
            ->select('section_subject.*', 'sections.name as section_name',
                     'subjects.name as subject_name', 'subjects.code as subject_code',
                     'sections.id as section_id', 'subjects.id as subject_id')
            ->get();

        // Count students in assigned classes
        $studentCount = DB::table('section_subject')
            ->where('faculty_id', $user->id)
            ->join('section_student', function($join) {
                $join->on('section_subject.section_id', '=', 'section_student.section_id')
                     ->on('section_subject.school_year', '=', 'section_student.school_year')
                     ->on('section_subject.semester', '=', 'section_student.semester');
            })
            ->count(\DB::raw('DISTINCT section_student.student_id'));

        // Count syllabi uploaded
        $syllabiCount = DB::table('syllabi')
            ->where('faculty_id', $user->id)
            ->count();

        // Get recent activities
        $recentActivities = $this->getRecentActivities($user->id);

        return view('faculty.dashboard', compact('user', 'assignedClasses', 'studentCount', 'syllabiCount', 'recentActivities'));
    }

    /**
     * Get recent activities for faculty
     */
    private function getRecentActivities($facultyId)
    {
        // Get recent syllabi uploads
        $syllabi = DB::table('syllabi')
            ->where('faculty_id', $facultyId)
            ->join('subjects', 'syllabi.subject_id', '=', 'subjects.id')
            ->select('syllabi.*', 'subjects.name as subject_name', 'subjects.code as subject_code')
            ->orderBy('upload_timestamp', 'desc')
            ->limit(3)
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'syllabus',
                    'title' => $item->subject_code . ' - ' . $item->subject_name,
                    'timestamp' => $item->upload_timestamp,
                    'description' => 'Uploaded Syllabus'
                ];
            });

        // Get recent assessments created
        $assessments = DB::table('assessments')
            ->where('faculty_id', $facultyId)
            ->join('subjects', 'assessments.subject_id', '=', 'subjects.id')
            ->select('assessments.*', 'subjects.name as subject_name', 'subjects.code as subject_code')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'assessment',
                    'title' => $item->subject_code . ' - ' . $item->subject_name,
                    'timestamp' => $item->created_at,
                    'description' => 'Created ' . ucfirst($item->type) . ': ' . $item->title
                ];
            });

        // Get recent score entries
        $scores = DB::table('student_scores')
            ->join('assessments', 'student_scores.assessment_id', '=', 'assessments.id')
            ->where('assessments.faculty_id', $facultyId)
            ->join('subjects', 'assessments.subject_id', '=', 'subjects.id')
            ->select('student_scores.*', 'assessments.title as assessment_title',
                     'subjects.name as subject_name', 'subjects.code as subject_code')
            ->orderBy('student_scores.created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'score',
                    'title' => $item->subject_code . ' - ' . $item->subject_name,
                    'timestamp' => $item->created_at,
                    'description' => 'Entered Scores: ' . $item->assessment_title
                ];
            });

        // Combine and sort activities
        $activities = collect()
            ->merge($syllabi)
            ->merge($assessments)
            ->merge($scores)
            ->sortByDesc('timestamp')
            ->take(5)
            ->values()
            ->all();

        return $activities;
    }

    /**
     * Display list of classes assigned to faculty.
     */
    public function myClasses()
    {
        $user = Auth::user();

        $classes = DB::table('section_subject')
            ->where('faculty_id', $user->id)
            ->join('sections', 'section_subject.section_id', '=', 'sections.id')
            ->join('subjects', 'section_subject.subject_id', '=', 'subjects.id')
            ->select('section_subject.*', 'sections.name as section_name',
                     'subjects.name as subject_name', 'subjects.code as subject_code',
                     'sections.id as section_id', 'subjects.id as subject_id')
            ->orderBy('section_subject.school_year', 'desc')
            ->orderBy('section_subject.semester', 'desc')
            ->get();

        return view('faculty.classes.index', compact('classes'));
    }

    /**
     * Display details of a specific class.
     */
    public function classDetails($sectionId, $subjectId, $schoolYear, $semester)
    {
        $user = Auth::user();

        // Verify this class belongs to the faculty
        $classExists = DB::table('section_subject')
            ->where('faculty_id', $user->id)
            ->where('section_id', $sectionId)
            ->where('subject_id', $subjectId)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->exists();

        if (!$classExists) {
            return redirect()->route('faculty.classes.index')
                ->with('error', 'You are not authorized to view this class');
        }

        $section = DB::table('sections')->where('id', $sectionId)->first();
        $subject = DB::table('subjects')->where('id', $subjectId)->first();

        // Get students in this section
        $students = DB::table('section_student')
            ->where('section_id', $sectionId)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->join('users', 'section_student.student_id', '=', 'users.id')
            ->select('users.*')
            ->get();

        // Get grading system for this subject
        $gradingSystem = DB::table('grading_systems')
            ->where('subject_id', $subjectId)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->first();

        // Get assessments for this class
        $assessments = DB::table('assessments')
            ->where('subject_id', $subjectId)
            ->where('faculty_id', $user->id)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->orderBy('term')
            ->orderBy('type')
            ->orderBy('created_at')
            ->get();

        // Check if syllabus exists
        $syllabus = DB::table('syllabi')
            ->where('subject_id', $subjectId)
            ->where('faculty_id', $user->id)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->first();

        // Check if seat plan exists
        $seatPlan = DB::table('seat_plans')
            ->where('section_id', $sectionId)
            ->where('subject_id', $subjectId)
            ->where('faculty_id', $user->id)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->first();

        return view('faculty.classes.details', compact('section', 'subject', 'students',
            'gradingSystem', 'assessments', 'syllabus', 'seatPlan', 'schoolYear', 'semester'));
    }

    /**
     * Show form to upload syllabus.
     */
    public function uploadSyllabus($sectionId, $subjectId, $schoolYear, $semester)
    {
        $user = Auth::user();

        // Verify this class belongs to the faculty
        $classExists = DB::table('section_subject')
            ->where('faculty_id', $user->id)
            ->where('section_id', $sectionId)
            ->where('subject_id', $subjectId)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->exists();

        if (!$classExists) {
            return redirect()->route('faculty.classes.index')
                ->with('error', 'You are not authorized to upload a syllabus for this class');
        }

        $section = DB::table('sections')->where('id', $sectionId)->first();
        $subject = DB::table('subjects')->where('id', $subjectId)->first();

        // Check if syllabus already exists
        $existingSyllabus = DB::table('syllabi')
            ->where('subject_id', $subjectId)
            ->where('faculty_id', $user->id)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->first();

        return view('faculty.syllabus.upload', compact('section', 'subject', 'schoolYear', 'semester', 'existingSyllabus'));
    }

    /**
     * Store uploaded syllabus.
     */
    public function storeSyllabus(Request $request, $sectionId, $subjectId, $schoolYear, $semester)
    {
        $request->validate([
            'syllabus_file' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $user = Auth::user();

        // Verify this class belongs to the faculty
        $classExists = DB::table('section_subject')
            ->where('faculty_id', $user->id)
            ->where('section_id', $sectionId)
            ->where('subject_id', $subjectId)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->exists();

        if (!$classExists) {
            return redirect()->route('faculty.classes.index')
                ->with('error', 'You are not authorized to upload a syllabus for this class');
        }

        // Check if syllabus already exists
        $existingSyllabus = DB::table('syllabi')
            ->where('subject_id', $subjectId)
            ->where('faculty_id', $user->id)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->first();

        if ($existingSyllabus) {
            // Delete old file if replacing
            Storage::delete($existingSyllabus->file_path);
            DB::table('syllabi')->where('id', $existingSyllabus->id)->delete();
        }

        // Store file
        $file = $request->file('syllabus_file');
        $originalFilename = $file->getClientOriginalName();
        $path = $file->store('syllabi');

        // Create syllabus record
        DB::table('syllabi')->insert([
            'subject_id' => $subjectId,
            'faculty_id' => $user->id,
            'file_path' => $path,
            'original_filename' => $originalFilename,
            'upload_timestamp' => now(),
            'school_year' => $schoolYear,
            'semester' => $semester,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('faculty.classes.details', [
            'sectionId' => $sectionId,
            'subjectId' => $subjectId,
            'schoolYear' => $schoolYear,
            'semester' => $semester,
        ])->with('success', 'Syllabus uploaded successfully');
    }

    /**
     * Download syllabus file
     */
    public function downloadSyllabus($id)
    {
        $user = Auth::user();

        $syllabus = DB::table('syllabi')->where('id', $id)->first();

        if (!$syllabus) {
            abort(404, 'Syllabus not found');
        }

        // Check if this is faculty's own syllabus or is assigned to this subject
        $isOwner = $syllabus->faculty_id == $user->id;
        $isAssigned = DB::table('section_subject')
            ->where('faculty_id', $user->id)
            ->where('subject_id', $syllabus->subject_id)
            ->where('school_year', $syllabus->school_year)
            ->where('semester', $syllabus->semester)
            ->exists();

        if (!$isOwner && !$isAssigned) {
            abort(403, 'Unauthorized access');
        }

        return Storage::download($syllabus->file_path, $syllabus->original_filename);
    }

    /**
     * Show form to create a seat plan
     */
    public function createSeatPlan($sectionId, $subjectId, $schoolYear, $semester)
    {
        $user = Auth::user();

        // Verify this class belongs to the faculty
        $classExists = DB::table('section_subject')
            ->where('faculty_id', $user->id)
            ->where('section_id', $sectionId)
            ->where('subject_id', $subjectId)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->exists();

        if (!$classExists) {
            return redirect()->route('faculty.classes.index')
                ->with('error', 'You are not authorized to create a seat plan for this class');
        }

        $section = DB::table('sections')->where('id', $sectionId)->first();
        $subject = DB::table('subjects')->where('id', $subjectId)->first();

        // Get students in this section
        $students = DB::table('section_student')
            ->where('section_id', $sectionId)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->join('users', 'section_student.student_id', '=', 'users.id')
            ->select('users.*')
            ->get();

        // Check if seat plan already exists
        $existingSeatPlan = DB::table('seat_plans')
            ->where('section_id', $sectionId)
            ->where('subject_id', $subjectId)
            ->where('faculty_id', $user->id)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->first();

        return view('faculty.seatplan.create', compact('section', 'subject', 'students',
            'existingSeatPlan', 'schoolYear', 'semester'));
    }

    /**
     * Store seat plan
     */
    public function storeSeatPlan(Request $request, $sectionId, $subjectId, $schoolYear, $semester)
    {
        $request->validate([
            'rows' => 'required|integer|min:1|max:20',
            'columns' => 'required|integer|min:1|max:20',
            'arrangement' => 'required|array',
        ]);

        $user = Auth::user();

        // Verify this class belongs to the faculty
        $classExists = DB::table('section_subject')
            ->where('faculty_id', $user->id)
            ->where('section_id', $sectionId)
            ->where('subject_id', $subjectId)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->exists();

        if (!$classExists) {
            return redirect()->route('faculty.classes.index')
                ->with('error', 'You are not authorized to create a seat plan for this class');
        }

        // Check if seat plan already exists
        $existingSeatPlan = DB::table('seat_plans')
            ->where('section_id', $sectionId)
            ->where('subject_id', $subjectId)
            ->where('faculty_id', $user->id)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->first();

        $data = [
            'section_id' => $sectionId,
            'subject_id' => $subjectId,
            'faculty_id' => $user->id,
            'rows' => $request->rows,
            'columns' => $request->columns,
            'arrangement' => json_encode($request->arrangement),
            'school_year' => $schoolYear,
            'semester' => $semester,
            'updated_at' => now(),
        ];

        if ($existingSeatPlan) {
            // Update existing seat plan
            DB::table('seat_plans')
                ->where('id', $existingSeatPlan->id)
                ->update($data);
        } else {
            // Create new seat plan
            $data['created_at'] = now();
            DB::table('seat_plans')->insert($data);
        }

        return redirect()->route('faculty.classes.details', [
            'sectionId' => $sectionId,
            'subjectId' => $subjectId,
            'schoolYear' => $schoolYear,
            'semester' => $semester,
        ])->with('success', 'Seat plan saved successfully');
    }

    /**
     * View seat plan
     */
    public function viewSeatPlan($sectionId, $subjectId, $schoolYear, $semester)
    {
        $user = Auth::user();

        // Verify this class belongs to the faculty
        $classExists = DB::table('section_subject')
            ->where('faculty_id', $user->id)
            ->where('section_id', $sectionId)
            ->where('subject_id', $subjectId)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->exists();

        if (!$classExists) {
            return redirect()->route('faculty.classes.index')
                ->with('error', 'You are not authorized to view this seat plan');
        }

        $section = DB::table('sections')->where('id', $sectionId)->first();
        $subject = DB::table('subjects')->where('id', $subjectId)->first();

        // Get students in this section
        $students = DB::table('section_student')
            ->where('section_id', $sectionId)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->join('users', 'section_student.student_id', '=', 'users.id')
            ->select('users.*')
            ->get()
            ->keyBy('id');

        // Get seat plan
        $seatPlan = DB::table('seat_plans')
            ->where('section_id', $sectionId)
            ->where('subject_id', $subjectId)
            ->where('faculty_id', $user->id)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->first();

        if (!$seatPlan) {
            return redirect()->route('faculty.seatplan.create', [
                'sectionId' => $sectionId,
                'subjectId' => $subjectId,
                'schoolYear' => $schoolYear,
                'semester' => $semester,
            ])->with('warning', 'No seat plan found. Please create one.');
        }

        $arrangementData = json_decode($seatPlan->arrangement, true);

        return view('faculty.seatplan.view', compact('section', 'subject', 'students',
            'seatPlan', 'arrangementData', 'schoolYear', 'semester'));
    }

    /**
     * Show form to create an assessment.
     */
    public function createAssessment($sectionId, $subjectId, $schoolYear, $semester)
    {
        $user = Auth::user();

        // Verify this class belongs to the faculty
        $classExists = DB::table('section_subject')
            ->where('faculty_id', $user->id)
            ->where('section_id', $sectionId)
            ->where('subject_id', $subjectId)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->exists();

        if (!$classExists) {
            return redirect()->route('faculty.classes.index')
                ->with('error', 'You are not authorized to create an assessment for this class');
        }

        $section = DB::table('sections')->where('id', $sectionId)->first();
        $subject = DB::table('subjects')->where('id', $subjectId)->first();

        return view('faculty.assessments.create', compact('section', 'subject', 'schoolYear', 'semester'));
    }

    /**
     * Store new assessment.
     */
    public function storeAssessment(Request $request, $sectionId, $subjectId, $schoolYear, $semester)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:quiz,unit_test,activity,midterm_exam,final_exam',
            'max_score' => 'required|integer|min:1',
            'term' => 'required|in:midterm,final',
            'schedule_date' => 'nullable|date',
            'schedule_time' => 'nullable|date_format:H:i',
        ]);

        $user = Auth::user();

        // Verify this class belongs to the faculty
        $classExists = DB::table('section_subject')
            ->where('faculty_id', $user->id)
            ->where('section_id', $sectionId)
            ->where('subject_id', $subjectId)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->exists();

        if (!$classExists) {
            return redirect()->route('faculty.classes.index')
                ->with('error', 'You are not authorized to create an assessment for this class');
        }

        // Create assessment
        $assessmentId = DB::table('assessments')->insertGetId([
            'subject_id' => $subjectId,
            'faculty_id' => $user->id,
            'title' => $request->title,
            'type' => $request->type,
            'max_score' => $request->max_score,
            'term' => $request->term,
            'schedule_date' => $request->schedule_date,
            'schedule_time' => $request->schedule_time,
            'school_year' => $schoolYear,
            'semester' => $semester,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('faculty.classes.details', [
            'sectionId' => $sectionId,
            'subjectId' => $subjectId,
            'schoolYear' => $schoolYear,
            'semester' => $semester,
        ])->with('success', 'Assessment created successfully');
    }

    /**
     * Show form to manage scores for an assessment.
     */
    public function manageScores($assessmentId)
    {
        $user = Auth::user();

        // Get assessment details
        $assessment = DB::table('assessments')->where('id', $assessmentId)->first();

        if (!$assessment || $assessment->faculty_id != $user->id) {
            return redirect()->route('faculty.classes.index')
                ->with('error', 'You are not authorized to manage scores for this assessment');
        }

        $subject = DB::table('subjects')->where('id', $assessment->subject_id)->first();

        // Get students
        $students = DB::table('section_subject')
            ->where('faculty_id', $user->id)
            ->where('subject_id', $assessment->subject_id)
            ->where('school_year', $assessment->school_year)
            ->where('semester', $assessment->semester)
            ->join('section_student', function($join) use ($assessment) {
                $join->on('section_subject.section_id', '=', 'section_student.section_id')
                     ->where('section_student.school_year', '=', $assessment->school_year)
                     ->where('section_student.semester', '=', $assessment->semester);
            })
            ->join('users', 'section_student.student_id', '=', 'users.id')
            ->select('users.*')
            ->distinct()
            ->get();

        // Get existing scores
        $scores = DB::table('student_scores')
            ->where('assessment_id', $assessmentId)
            ->pluck('score', 'student_id');

        return view('faculty.scores.manage', compact('assessment', 'subject', 'students', 'scores'));
    }

    /**
     * Save scores for an assessment.
     */
    public function saveScores(Request $request, $assessmentId)
    {
        $user = Auth::user();

        // Get assessment details
        $assessment = DB::table('assessments')->where('id', $assessmentId)->first();

        if (!$assessment || $assessment->faculty_id != $user->id) {
            return redirect()->route('faculty.classes.index')
                ->with('error', 'You are not authorized to manage scores for this assessment');
        }

        // Validate scores
        $request->validate([
            'scores' => 'required|array',
            'scores.*' => 'nullable|numeric|min:0|max:' . $assessment->max_score,
        ]);

        // Save scores
        foreach ($request->scores as $studentId => $score) {
            if ($score !== null) {
                // Check if score exists
                $exists = DB::table('student_scores')
                    ->where('assessment_id', $assessmentId)
                    ->where('student_id', $studentId)
                    ->exists();

                if ($exists) {
                    // Update
                    DB::table('student_scores')
                        ->where('assessment_id', $assessmentId)
                        ->where('student_id', $studentId)
                        ->update([
                            'score' => $score,
                            'updated_at' => now(),
                        ]);
                } else {
                    // Insert
                    DB::table('student_scores')->insert([
                        'assessment_id' => $assessmentId,
                        'student_id' => $studentId,
                        'score' => $score,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        return redirect()->route('faculty.scores.manage', ['assessmentId' => $assessmentId])
            ->with('success', 'Scores saved successfully');
    }

    /**
     * Display analytics for a class.
     */
    public function analytics($sectionId, $subjectId, $schoolYear, $semester)
    {
        $user = Auth::user();

        // Verify this class belongs to the faculty
        $classExists = DB::table('section_subject')
            ->where('faculty_id', $user->id)
            ->where('section_id', $sectionId)
            ->where('subject_id', $subjectId)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->exists();

        if (!$classExists) {
            return redirect()->route('faculty.classes.index')
                ->with('error', 'You are not authorized to view analytics for this class');
        }

        $section = DB::table('sections')->where('id', $sectionId)->first();
        $subject = DB::table('subjects')->where('id', $subjectId)->first();

        // Get students in this section
        $students = DB::table('section_student')
            ->where('section_id', $sectionId)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->join('users', 'section_student.student_id', '=', 'users.id')
            ->select('users.*')
            ->get();

        // Get grading system for this subject
        $gradingSystem = DB::table('grading_systems')
            ->where('subject_id', $subjectId)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->first();

        // Get assessments for this class
        $assessments = DB::table('assessments')
            ->where('subject_id', $subjectId)
            ->where('faculty_id', $user->id)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->get();

        // Calculate overall performance
        $performance = [];
        $passingCount = 0;
        $failingCount = 0;

        foreach ($students as $student) {
            $studentPerformance = [
                'student' => $student,
                'midterm' => $this->calculateTerm('midterm', $student->id, $assessments, $gradingSystem),
                'final' => $this->calculateTerm('final', $student->id, $assessments, $gradingSystem),
            ];

            // Calculate final grade (average of midterm and final)
            $studentPerformance['final_grade'] = ($studentPerformance['midterm'] + $studentPerformance['final']) / 2;

            // Determine if passing (75% is passing)
            $studentPerformance['is_passing'] = $studentPerformance['final_grade'] >= 75;

            if ($studentPerformance['is_passing']) {
                $passingCount++;
            } else {
                $failingCount++;
            }

            $performance[] = $studentPerformance;
        }

        // Statistics
        $stats = [
            'total_students' => count($students),
            'passing_count' => $passingCount,
            'failing_count' => $failingCount,
            'passing_percentage' => count($students) > 0 ? ($passingCount / count($students) * 100) : 0,
            'failing_percentage' => count($students) > 0 ? ($failingCount / count($students) * 100) : 0,
        ];

        return view('faculty.analytics.index', compact('section', 'subject', 'performance',
            'stats', 'schoolYear', 'semester'));
    }

    /**
     * Helper method to calculate term grades
     */
    private function calculateTerm($term, $studentId, $assessments, $gradingSystem)
    {
        if (!$gradingSystem) {
            return 0;
        }

        $termAssessments = collect($assessments)->where('term', $term);

        $quizzes = $termAssessments->where('type', 'quiz');
        $unitTests = $termAssessments->where('type', 'unit_test');
        $activities = $termAssessments->where('type', 'activity');
        $exams = $termAssessments->filter(function($assessment) {
            return $assessment->type == 'midterm_exam' || $assessment->type == 'final_exam';
        });

        // Calculate component grades
        $quizGrade = $this->calculateComponentGrade($quizzes, $studentId);
        $unitTestGrade = $this->calculateComponentGrade($unitTests, $studentId);
        $activityGrade = $this->calculateComponentGrade($activities, $studentId);
        $examGrade = $this->calculateComponentGrade($exams, $studentId);

        // Calculate weighted grade
        $grade = ($quizGrade * $gradingSystem->quiz_percentage / 100) +
                 ($unitTestGrade * $gradingSystem->unit_test_percentage / 100) +
                 ($activityGrade * $gradingSystem->activity_percentage / 100) +
                 ($examGrade * $gradingSystem->exam_percentage / 100);

        return round($grade, 2);
    }

    /**
     * Helper method to calculate component grades
     */
    private function calculateComponentGrade($assessments, $studentId)
    {
        if ($assessments->isEmpty()) {
            return 0;
        }

        $totalScore = 0;
        $totalMaxScore = 0;

        foreach ($assessments as $assessment) {
            $score = DB::table('student_scores')
                ->where('assessment_id', $assessment->id)
                ->where('student_id', $studentId)
                ->first();

            if ($score) {
                $totalScore += $score->score;
            }

            $totalMaxScore += $assessment->max_score;
        }

        if ($totalMaxScore == 0) {
            return 0;
        }

        return ($totalScore / $totalMaxScore) * 100;
    }

    /**
     * Generate report for a class
     */
    public function generateReport($sectionId, $subjectId, $schoolYear, $semester)
    {
        $user = Auth::user();

        // Verify this class belongs to the faculty
        $classExists = DB::table('section_subject')
            ->where('faculty_id', $user->id)
            ->where('section_id', $sectionId)
            ->where('subject_id', $subjectId)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->exists();

        if (!$classExists) {
            return redirect()->route('faculty.classes.index')
                ->with('error', 'You are not authorized to generate reports for this class');
        }

        $section = DB::table('sections')->where('id', $sectionId)->first();
        $subject = DB::table('subjects')->where('id', $subjectId)->first();

        // Get students in this section
        $students = DB::table('section_student')
            ->where('section_id', $sectionId)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->join('users', 'section_student.student_id', '=', 'users.id')
            ->select('users.*')
            ->get();

        // Get grading system for this subject
        $gradingSystem = DB::table('grading_systems')
            ->where('subject_id', $subjectId)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->first();

        // Get assessments for this class
        $assessments = DB::table('assessments')
            ->where('subject_id', $subjectId)
            ->where('faculty_id', $user->id)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->orderBy('term')
            ->orderBy('type')
            ->get();

        // Calculate grades for each student
        $studentGrades = [];

        foreach ($students as $student) {
            $midtermGrade = $this->calculateTerm('midterm', $student->id, $assessments, $gradingSystem);
            $finalGrade = $this->calculateTerm('final', $student->id, $assessments, $gradingSystem);
            $overallGrade = ($midtermGrade + $finalGrade) / 2;

            $studentGrades[] = [
                'student' => $student,
                'midterm_grade' => $midtermGrade,
                'final_grade' => $finalGrade,
                'overall_grade' => $overallGrade,
                'status' => $overallGrade >= 75 ? 'Passing' : 'Failing',
            ];
        }

        // Display the report view
        return view('faculty.reports.generate', compact('section', 'subject', 'students',
            'gradingSystem', 'assessments', 'studentGrades', 'schoolYear', 'semester'));
    }

    /**
     * Download report as PDF
     */
    public function downloadReport($sectionId, $subjectId, $schoolYear, $semester)
    {
        $user = Auth::user();

        // Verify this class belongs to the faculty
        $classExists = DB::table('section_subject')
            ->where('faculty_id', $user->id)
            ->where('section_id', $sectionId)
            ->where('subject_id', $subjectId)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->exists();

        if (!$classExists) {
            return redirect()->route('faculty.classes.index')
                ->with('error', 'You are not authorized to download reports for this class');
        }

        // Generate PDF report (implementation would depend on the PDF library used)
        // For example using dompdf:
        // $pdf = PDF::loadView('faculty.reports.pdf', $data);
        // return $pdf->download('report.pdf');

        // For now, just redirect back with a message
        return redirect()->route('faculty.reports.generate', [
            'sectionId' => $sectionId,
            'subjectId' => $subjectId,
            'schoolYear' => $schoolYear,
            'semester' => $semester,
        ])->with('info', 'PDF download functionality will be implemented soon');
    }

    /**
     * List all syllabi uploaded by faculty
     */
    public function listSyllabi()
    {
        $user = Auth::user();

        $syllabi = DB::table('syllabi')
            ->where('faculty_id', $user->id)
            ->join('subjects', 'syllabi.subject_id', '=', 'subjects.id')
            ->select('syllabi.*', 'subjects.name as subject_name', 'subjects.code as subject_code')
            ->orderBy('syllabi.school_year', 'desc')
            ->orderBy('syllabi.semester', 'desc')
            ->orderBy('syllabi.upload_timestamp', 'desc')
            ->get();

        return view('faculty.syllabus.index', compact('syllabi'));
    }
}
