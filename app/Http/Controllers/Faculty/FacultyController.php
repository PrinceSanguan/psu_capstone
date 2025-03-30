<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\SchoolYearService;
use Illuminate\Support\Str;

class FacultyController extends Controller
{
    /**
     * Display faculty dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Get assigned classes for the faculty user.
        $assignedClasses = DB::table('section_subject')
            ->where('faculty_id', $user->id)
            ->join('sections', 'section_subject.section_id', '=', 'sections.id')
            ->join('subjects', 'section_subject.subject_id', '=', 'subjects.id')
            ->select(
                'section_subject.*',
                'sections.name as section_name',
                'subjects.name as subject_name',
                'subjects.code as subject_code',
                'sections.id as section_id',
                'subjects.id as subject_id'
            )
            ->get();

        // Count distinct students in assigned classes.
        $studentCount = DB::table('section_subject')
            ->where('faculty_id', $user->id)
            ->join('section_student', function ($join) {
                $join->on('section_subject.section_id', '=', 'section_student.section_id')
                     ->on('section_subject.school_year', '=', 'section_student.school_year')
                     ->on('section_subject.semester', '=', 'section_student.semester');
            })
            ->count(DB::raw('DISTINCT section_student.student_id'));

        // Count syllabi uploaded by this faculty.
        $syllabiCount = DB::table('syllabi')
            ->where('faculty_id', $user->id)
            ->count();

        // Get recent activities.
        $recentActivities = $this->getRecentActivities($user->id);

        return view('faculty.dashboard', compact('user', 'assignedClasses', 'studentCount', 'syllabiCount', 'recentActivities'));
    }

    /**
     * Get recent activities for the faculty.
     *
     * @param  int  $facultyId
     * @return array
     */
    private function getRecentActivities($facultyId)
    {
        // Recent syllabus uploads.
        $syllabi = DB::table('syllabi')
            ->where('faculty_id', $facultyId)
            ->join('subjects', 'syllabi.subject_id', '=', 'subjects.id')
            ->select(
                'syllabi.*',
                'subjects.name as subject_name',
                'subjects.code as subject_code'
            )
            ->orderBy('upload_timestamp', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($item) {
                return [
                    'type'        => 'syllabus',
                    'title'       => $item->subject_code . ' - ' . $item->subject_name,
                    'timestamp'   => $item->upload_timestamp,
                    'description' => 'Uploaded Syllabus'
                ];
            });

        // Recent assessments created.
        $assessments = DB::table('assessments')
            ->where('faculty_id', $facultyId)
            ->join('subjects', 'assessments.subject_id', '=', 'subjects.id')
            ->select(
                'assessments.*',
                'subjects.name as subject_name',
                'subjects.code as subject_code'
            )
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($item) {
                return [
                    'type'        => 'assessment',
                    'title'       => $item->subject_code . ' - ' . $item->subject_name,
                    'timestamp'   => $item->created_at,
                    'description' => 'Created ' . ucfirst($item->type) . ': ' . $item->title
                ];
            });

        // Recent scores entered.
        $scores = DB::table('student_scores')
            ->join('assessments', 'student_scores.assessment_id', '=', 'assessments.id')
            ->where('assessments.faculty_id', $facultyId)
            ->join('subjects', 'assessments.subject_id', '=', 'subjects.id')
            ->select(
                'student_scores.*',
                'assessments.title as assessment_title',
                'subjects.name as subject_name',
                'subjects.code as subject_code'
            )
            ->orderBy('student_scores.created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($item) {
                return [
                    'type'        => 'score',
                    'title'       => $item->subject_code . ' - ' . $item->subject_name,
                    'timestamp'   => $item->created_at,
                    'description' => 'Entered Scores: ' . $item->assessment_title
                ];
            });

        // Merge activities.
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
     * Display list of classes assigned to the faculty.
     */
    public function myClasses()
    {
        $user = Auth::user();

        $classes = DB::table('section_subject')
            ->where('faculty_id', $user->id)
            ->join('sections', 'section_subject.section_id', '=', 'sections.id')
            ->join('subjects', 'section_subject.subject_id', '=', 'subjects.id')
            ->select(
                'section_subject.*',
                'sections.name as section_name',
                'subjects.name as subject_name',
                'subjects.code as subject_code',
                'sections.id as section_id',
                'subjects.id as subject_id'
            )
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

        $students = DB::table('section_student')
            ->where('section_id', $sectionId)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->join('users', 'section_student.student_id', '=', 'users.id')
            ->select('users.*')
            ->get();

        $gradingSystem = DB::table('grading_systems')
            ->where('subject_id', $subjectId)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->first();

        $assessments = DB::table('assessments')
            ->where('subject_id', $subjectId)
            ->where('faculty_id', $user->id)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->orderBy('term')
            ->orderBy('type')
            ->orderBy('created_at')
            ->get();

        $syllabus = DB::table('syllabi')
            ->where('subject_id', $subjectId)
            ->where('faculty_id', $user->id)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->first();

        $seatPlan = DB::table('seat_plans')
            ->where('section_id', $sectionId)
            ->where('subject_id', $subjectId)
            ->where('faculty_id', $user->id)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->first();

        return view('faculty.classes.details', compact(
            'section', 'subject', 'students', 'gradingSystem', 'assessments', 'syllabus', 'seatPlan', 'schoolYear', 'semester'
        ));
    }

    /**
     * Show the form to upload a syllabus.
     */
    public function uploadSyllabus($sectionId, $subjectId, $schoolYear, $semester)
    {
        $user = Auth::user();
        $section = DB::table('sections')->where('id', $sectionId)->first();
        $subject = DB::table('subjects')->where('id', $subjectId)->first();
        $existingSyllabus = DB::table('syllabi')
            ->where('subject_id', $subjectId)
            ->where('faculty_id', $user->id)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->first();

        return view('faculty.syllabus.upload', compact('section', 'subject', 'schoolYear', 'semester', 'existingSyllabus'));
    }

    /**
     * Store the uploaded syllabus file.
     */
    public function storeSyllabus(Request $request, $sectionId, $subjectId, $schoolYear, $semester)
    {
        $request->validate([
            'syllabus_file' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $user = Auth::user();

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

        $existingSyllabus = DB::table('syllabi')
            ->where('subject_id', $subjectId)
            ->where('faculty_id', $user->id)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->first();

        if ($existingSyllabus) {
            Storage::delete($existingSyllabus->file_path);
            DB::table('syllabi')->where('id', $existingSyllabus->id)->delete();
        }

        $file = $request->file('syllabus_file');
        $originalFilename = $file->getClientOriginalName();
        $path = $file->store('syllabi');

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
     * Download a syllabus file.
     */
    public function downloadSyllabus($id)
    {
        $user = Auth::user();
        $syllabus = DB::table('syllabi')->where('id', $id)->first();

        if (!$syllabus) {
            abort(404, 'Syllabus not found');
        }

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
     * Show form to create a seat plan.
     */
    public function createSeatPlan($sectionId, $subjectId, $schoolYear, $semester)
    {
        $user = Auth::user();

        // Format school year consistently
        $schoolYear = SchoolYearService::format($schoolYear);

        $classExists = DB::table('section_subject')
            ->where('faculty_id', $user->id)
            ->where('section_id', $sectionId)
            ->where('subject_id', $subjectId)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->exists();

        if (!$classExists) {
            // Log the query for debugging if needed
            \Log::info('Class check query failed', [
                'faculty_id' => $user->id,
                'section_id' => $sectionId,
                'subject_id' => $subjectId,
                'school_year' => $schoolYear,
                'semester' => $semester
            ]);

            return redirect()->route('faculty.classes.index')
                ->with('error', 'You are not authorized to create a seat plan for this class');
        }

        $section = DB::table('sections')->where('id', $sectionId)->first();
        $subject = DB::table('subjects')->where('id', $subjectId)->first();

        // Retrieve enrolled students with consistent school year format
        $students = DB::table('section_student')
            ->where('section_id', $sectionId)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->join('users', 'section_student.student_id', '=', 'users.id')
            ->select('users.*')
            ->get();

        // Log student count for debugging if needed
        \Log::info('Students retrieved for seat plan', [
            'section_id' => $sectionId,
            'school_year' => $schoolYear,
            'semester' => $semester,
            'count' => $students->count()
        ]);

        $existingSeatPlan = DB::table('seat_plans')
            ->where('section_id', $sectionId)
            ->where('subject_id', $subjectId)
            ->where('faculty_id', $user->id)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->first();

        return view('faculty.seatplan.create', compact('section', 'subject', 'students', 'existingSeatPlan', 'schoolYear', 'semester'));
    }

    /**
     * Store a new or updated seat plan.
     */
    public function storeSeatPlan(Request $request, $sectionId, $subjectId, $schoolYear, $semester)
    {
        $request->validate([
            'rows' => 'required|integer|min:1|max:20',
            'columns' => 'required|integer|min:1|max:20',
            'arrangement' => 'required', // Will validate JSON manually
        ]);

        $user = Auth::user();

        // Format school year consistently
        $schoolYear = SchoolYearService::format($schoolYear);

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

        // Check if arrangement is valid JSON
        try {
            // Log the received arrangement data for debugging
            \Log::info('Received arrangement data:', ['data' => $request->arrangement]);

            // Try to decode the JSON
            $arrangementData = json_decode($request->arrangement, true);

            // Check for JSON errors
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON format: ' . json_last_error_msg());
            }

            // Ensure decoded data is an array
            if (!is_array($arrangementData)) {
                throw new \Exception('Arrangement data is not a valid array');
            }

            // Log successful decode
            \Log::info('Successfully decoded arrangement', ['count' => count($arrangementData)]);
        } catch (\Exception $e) {
            \Log::error('Error parsing seat plan arrangement: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['arrangement' => 'Invalid arrangement data: ' . $e->getMessage()])
                ->withInput();
        }

        $existingSeatPlan = DB::table('seat_plans')
            ->where('section_id', $sectionId)
            ->where('subject_id', $subjectId)
            ->where('faculty_id', $user->id)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->first();

        $data = [
            'section_id'  => $sectionId,
            'subject_id'  => $subjectId,
            'faculty_id'  => $user->id,
            'rows'        => $request->rows,
            'columns'     => $request->columns,
            'arrangement' => $request->arrangement, // Store the raw JSON string
            'school_year' => $schoolYear,
            'semester'    => $semester,
            'updated_at'  => now(),
        ];

        // Log data being saved
        \Log::info('Saving seat plan data', ['data' => $data]);

        if ($existingSeatPlan) {
            DB::table('seat_plans')
                ->where('id', $existingSeatPlan->id)
                ->update($data);

            \Log::info('Updated existing seat plan', ['id' => $existingSeatPlan->id]);
        } else {
            $data['created_at'] = now();
            $newId = DB::table('seat_plans')->insertGetId($data);

            \Log::info('Created new seat plan', ['id' => $newId]);
        }

        return redirect()->route('faculty.classes.details', [
            'sectionId' => $sectionId,
            'subjectId' => $subjectId,
            'schoolYear' => $schoolYear,
            'semester' => $semester,
        ])->with('success', 'Seat plan saved successfully');
    }

    /**
     * View an existing seat plan.
     */
    public function viewSeatPlan($sectionId, $subjectId, $schoolYear, $semester)
    {
        $user = Auth::user();

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

        $students = DB::table('section_student')
            ->where('section_id', $sectionId)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->join('users', 'section_student.student_id', '=', 'users.id')
            ->select('users.*')
            ->get()
            ->keyBy('id');

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

        return view('faculty.seatplan.view', compact('section', 'subject', 'students', 'seatPlan', 'arrangementData', 'schoolYear', 'semester'));
    }

    /**
     * Show form to create an assessment.
     */
    public function createAssessment($sectionId, $subjectId, $schoolYear, $semester)
    {
        $user = Auth::user();

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
     * Store a new assessment.
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
        $assessment = DB::table('assessments')->where('id', $assessmentId)->first();

        if (!$assessment || $assessment->faculty_id != $user->id) {
            return redirect()->route('faculty.classes.index')
                ->with('error', 'You are not authorized to manage scores for this assessment');
        }

        $subject = DB::table('subjects')->where('id', $assessment->subject_id)->first();

        // Fixed query to avoid ambiguous column references
        $students = DB::table('section_subject')
            ->where('section_subject.faculty_id', $user->id)
            ->where('section_subject.subject_id', $assessment->subject_id)
            ->where('section_subject.school_year', $assessment->school_year)
            ->where('section_subject.semester', $assessment->semester)
            ->join('section_student', function ($join) use ($assessment) {
                $join->on('section_subject.section_id', '=', 'section_student.section_id')
                     ->where('section_student.school_year', '=', $assessment->school_year)
                     ->where('section_student.semester', '=', $assessment->semester);
            })
            ->join('users', 'section_student.student_id', '=', 'users.id')
            ->select('users.*')
            ->distinct()
            ->get();

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
        $assessment = DB::table('assessments')->where('id', $assessmentId)->first();

        if (!$assessment || $assessment->faculty_id != $user->id) {
            return redirect()->route('faculty.classes.index')
                ->with('error', 'You are not authorized to manage scores for this assessment');
        }

        $request->validate([
            'scores' => 'required|array',
            'scores.*' => 'nullable|numeric|min:0|max:' . $assessment->max_score,
        ]);

        foreach ($request->scores as $studentId => $score) {
            if ($score !== null) {
                $exists = DB::table('student_scores')
                    ->where('assessment_id', $assessmentId)
                    ->where('student_id', $studentId)
                    ->exists();

                if ($exists) {
                    DB::table('student_scores')
                        ->where('assessment_id', $assessmentId)
                        ->where('student_id', $studentId)
                        ->update([
                            'score' => $score,
                            'updated_at' => now(),
                        ]);
                } else {
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

    // Get the students with null check
    $students = DB::table('section_student')
        ->where('section_id', $sectionId)
        ->where('school_year', $schoolYear)
        ->where('semester', $semester)
        ->join('users', 'section_student.student_id', '=', 'users.id')
        ->select('users.*')
        ->get();

    // Get the grading system with fallbacks - log what we're getting
    $gradingSystem = $this->getGradingSystem($subjectId, $schoolYear, $semester);
    \Log::info('Using grading system in analytics', [
        'quiz_percentage' => $gradingSystem->quiz_percentage,
        'unit_test_percentage' => $gradingSystem->unit_test_percentage,
        'activity_percentage' => $gradingSystem->activity_percentage,
        'exam_percentage' => $gradingSystem->exam_percentage
    ]);

    // Get assessments with null check
    $assessments = DB::table('assessments')
        ->where('subject_id', $subjectId)
        ->where('faculty_id', $user->id)
        ->where('school_year', $schoolYear)
        ->where('semester', $semester)
        ->orderBy('term')
        ->orderBy('type')
        ->get();

    // If no assessments found, initialize an empty collection
    if (!$assessments) {
        $assessments = collect([]);
    }

    $studentGrades = [];
    $passingCount = 0;
    $failingCount = 0;

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

        if ($overallGrade >= 75) {
            $passingCount++;
        } else {
            $failingCount++;
        }
    }

    // Use safer array filtering with explicit count checks
    $student_count = is_countable($students) ? count($students) : 0;
    $passing_students = is_countable($studentGrades) ? count(array_filter($studentGrades, fn($sg) => $sg['status'] == 'Passing')) : 0;
    $failing_students = is_countable($studentGrades) ? count(array_filter($studentGrades, fn($sg) => $sg['status'] == 'Failing')) : 0;

    $stats = [
        'total_students' => $student_count,
        'passing_count' => $passing_students,
        'failing_count' => $failing_students,
        'passing_percentage' => $student_count > 0 ? ($passing_students / $student_count * 100) : 0,
        'failing_percentage' => $student_count > 0 ? ($failing_students / $student_count * 100) : 0,
    ];

    return view('faculty.analytics.index', compact(
        'section',
        'subject',
        'stats',
        'schoolYear',
        'semester',
        'studentGrades',
        'students',
        'gradingSystem',
        'assessments'
    ));
}

/**
 * Add this method to your FacultyController
 * This is a diagnostic route to check the grading system for a subject
 */
public function checkGradingSystem($subjectId, $schoolYear, $semester)
{
    // Only allow this in development environment
    if (config('app.env') !== 'local') {
        abort(404);
    }

    $gradingSystem = $this->getGradingSystem($subjectId, $schoolYear, $semester);

    return response()->json([
        'subject_id' => $subjectId,
        'school_year' => $schoolYear,
        'semester' => $semester,
        'grading_system' => [
            'quiz_percentage' => $gradingSystem->quiz_percentage,
            'unit_test_percentage' => $gradingSystem->unit_test_percentage,
            'activity_percentage' => $gradingSystem->activity_percentage,
            'exam_percentage' => $gradingSystem->exam_percentage,
        ]
    ]);
}
/**
 * Get grading system for a subject, with fallbacks.
 *
 * @param int $subjectId Subject ID
 * @param string $schoolYear School year
 * @param string $semester Semester
 * @return object Grading system with percentage weights
 */
private function getGradingSystem($subjectId, $schoolYear, $semester)
{
    // First, try to find a specific grading system for this subject, school year, and semester
    $gradingSystem = DB::table('grading_systems')
        ->where('subject_id', $subjectId)
        ->where('school_year', $schoolYear)
        ->where('semester', $semester)
        ->first();

    // Log what we found for debugging
    \Log::info('Searching for grading system', [
        'subject_id' => $subjectId,
        'school_year' => $schoolYear,
        'semester' => $semester,
        'found' => !is_null($gradingSystem)
    ]);

    // If found, return it
    if ($gradingSystem) {
        \Log::info('Found specific grading system', [
            'quiz_percentage' => $gradingSystem->quiz_percentage,
            'unit_test_percentage' => $gradingSystem->unit_test_percentage,
            'activity_percentage' => $gradingSystem->activity_percentage,
            'exam_percentage' => $gradingSystem->exam_percentage
        ]);
        return $gradingSystem;
    }

    // If not found, try to find a default grading system for this subject
    $gradingSystem = DB::table('grading_systems')
        ->where('subject_id', $subjectId)
        ->whereNull('school_year')
        ->whereNull('semester')
        ->first();

    // If found, return default for subject
    if ($gradingSystem) {
        \Log::info('Found default grading system for subject', [
            'subject_id' => $subjectId,
            'quiz_percentage' => $gradingSystem->quiz_percentage,
            'unit_test_percentage' => $gradingSystem->unit_test_percentage,
            'activity_percentage' => $gradingSystem->activity_percentage,
            'exam_percentage' => $gradingSystem->exam_percentage
        ]);
        return $gradingSystem;
    }

    // Try to find any grading system for this subject
    $gradingSystem = DB::table('grading_systems')
        ->where('subject_id', $subjectId)
        ->first();

    // If found, return it
    if ($gradingSystem) {
        \Log::info('Found any grading system for subject', [
            'subject_id' => $subjectId,
            'quiz_percentage' => $gradingSystem->quiz_percentage,
            'unit_test_percentage' => $gradingSystem->unit_test_percentage,
            'activity_percentage' => $gradingSystem->activity_percentage,
            'exam_percentage' => $gradingSystem->exam_percentage
        ]);
        return $gradingSystem;
    }

    // If still not found, create a default with equal weights
    \Log::warning('No grading system found for subject ' . $subjectId . ', using default values');
    return (object)[
        'quiz_percentage' => 25,
        'unit_test_percentage' => 25,
        'activity_percentage' => 25,
        'exam_percentage' => 25
    ];
}

    /**
     * Helper method to calculate term grades.
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
        $exams = $termAssessments->filter(function ($assessment) {
            return in_array($assessment->type, ['midterm_exam', 'final_exam']);
        });

        $quizGrade = $this->calculateComponentGrade($quizzes, $studentId);
        $unitTestGrade = $this->calculateComponentGrade($unitTests, $studentId);
        $activityGrade = $this->calculateComponentGrade($activities, $studentId);
        $examGrade = $this->calculateComponentGrade($exams, $studentId);

        $grade = ($quizGrade * $gradingSystem->quiz_percentage / 100) +
                 ($unitTestGrade * $gradingSystem->unit_test_percentage / 100) +
                 ($activityGrade * $gradingSystem->activity_percentage / 100) +
                 ($examGrade * $gradingSystem->exam_percentage / 100);

        return round($grade, 2);
    }

    /**
     * Helper method to calculate component grades.
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

    public function generateReport($sectionId, $subjectId, $schoolYear, $semester)
    {
        $user = Auth::user();

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

        // Get students with null check
        $students = DB::table('section_student')
            ->where('section_id', $sectionId)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->join('users', 'section_student.student_id', '=', 'users.id')
            ->select('users.*')
            ->get();

        // Get the grading system with fallbacks using the helper method
        $gradingSystem = $this->getGradingSystem($subjectId, $schoolYear, $semester);

        // Get assessments with null check
        $assessments = DB::table('assessments')
            ->where('subject_id', $subjectId)
            ->where('faculty_id', $user->id)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->orderBy('term')
            ->orderBy('type')
            ->get();

        // If no assessments found, initialize an empty collection
        if (!$assessments || $assessments->count() == 0) {
            $assessments = collect([]);
        }

        $studentGrades = [];
        $passingCount = 0;
        $failingCount = 0;

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

            if ($overallGrade >= 75) {
                $passingCount++;
            } else {
                $failingCount++;
            }
        }

        // Use safer array filtering with explicit count checks
        $student_count = is_countable($students) ? count($students) : 0;
        $passing_students = is_countable($studentGrades) ? count(array_filter($studentGrades, fn($sg) => $sg['status'] == 'Passing')) : 0;
        $failing_students = is_countable($studentGrades) ? count(array_filter($studentGrades, fn($sg) => $sg['status'] == 'Failing')) : 0;

        $stats = [
            'total_students' => $student_count,
            'passing_count' => $passing_students,
            'failing_count' => $failing_students,
            'passing_percentage' => $student_count > 0 ? ($passing_students / $student_count * 100) : 0,
            'failing_percentage' => $student_count > 0 ? ($failing_students / $student_count * 100) : 0,
        ];

        // Get department chair info for report signature
        $departmentChair = DB::table('users')
            ->where('user_role', 'department_chair')
            ->first();

        if (!$departmentChair) {
            $departmentChair = (object)[
                'name' => 'Department Chair',
                'department' => 'Department of Computer Science'
            ];
        }

        // Get faculty info for report
        $faculty = Auth::user();

        return view('faculty.reports.generate', compact(
            'section',
            'subject',
            'students',
            'gradingSystem',
            'assessments',
            'studentGrades',
            'schoolYear',
            'semester',
            'stats',
            'faculty',
            'departmentChair'
        ));
    }
/**
 * Download report in the requested format.
 */
public function downloadReport(Request $request, $sectionId, $subjectId, $schoolYear, $semester)
{
    $user = Auth::user();

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

    // Get report options from form data
    $reportType = $request->input('reportType', 'class_grades');
    $fileFormat = $request->input('fileFormat', 'html');
    $includeCharts = $request->has('includeCharts');
    $includeComments = $request->has('includeComments');
    $includeAttendance = $request->has('includeAttendance');
    $teacherComments = $request->input('teacherComments', '');

    // Log the report generation request
    \Log::info('Report download requested', [
        'faculty_id' => $user->id,
        'section_id' => $sectionId,
        'subject_id' => $subjectId,
        'school_year' => $schoolYear,
        'semester' => $semester,
        'report_type' => $reportType,
        'file_format' => $fileFormat,
        'include_charts' => $includeCharts,
        'include_comments' => $includeComments,
        'include_attendance' => $includeAttendance
    ]);

    // Get necessary data for the report
    $section = DB::table('sections')->where('id', $sectionId)->first();
    $subject = DB::table('subjects')->where('id', $subjectId)->first();

    // Get students
    $students = DB::table('section_student')
        ->where('section_id', $sectionId)
        ->where('school_year', $schoolYear)
        ->where('semester', $semester)
        ->join('users', 'section_student.student_id', '=', 'users.id')
        ->select('users.*')
        ->get();

    // Get the grading system
    $gradingSystem = $this->getGradingSystem($subjectId, $schoolYear, $semester);

    // Get assessments
    $assessments = DB::table('assessments')
        ->where('subject_id', $subjectId)
        ->where('faculty_id', $user->id)
        ->where('school_year', $schoolYear)
        ->where('semester', $semester)
        ->orderBy('term')
        ->orderBy('type')
        ->get();

    if (!$assessments) {
        $assessments = collect([]);
    }

    // Calculate student grades
    $studentGrades = [];
    $passingCount = 0;
    $failingCount = 0;

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

        if ($overallGrade >= 75) {
            $passingCount++;
        } else {
            $failingCount++;
        }
    }

    $stats = [
        'total_students' => count($students),
        'passing_count' => $passingCount,
        'failing_count' => $failingCount,
        'passing_percentage' => count($students) > 0 ? ($passingCount / count($students) * 100) : 0,
        'failing_percentage' => count($students) > 0 ? ($failingCount / count($students) * 100) : 0,
    ];

    // Generate a unique filename base
    $filenameBase = Str::slug("{$subject->code}_{$section->name}_{$reportType}_" . date('Y-m-d-His'));

    // Process based on requested format
    switch ($fileFormat) {
        case 'pdf':
            return $this->generatePdfReport(
                $filenameBase,
                $section,
                $subject,
                $students,
                $gradingSystem,
                $assessments,
                $studentGrades,
                $schoolYear,
                $semester,
                $stats,
                $includeCharts,
                $includeComments,
                $includeAttendance,
                $teacherComments,
                $reportType,
                $user
            );

        case 'excel':
            return $this->generateExcelReport(
                $filenameBase,
                $section,
                $subject,
                $students,
                $gradingSystem,
                $assessments,
                $studentGrades,
                $schoolYear,
                $semester,
                $stats,
                $includeCharts,
                $includeComments,
                $includeAttendance,
                $teacherComments,
                $reportType,
                $user
            );

        case 'html':
        default:
            return $this->generateHtmlReport(
                $filenameBase,
                $section,
                $subject,
                $students,
                $gradingSystem,
                $assessments,
                $studentGrades,
                $schoolYear,
                $semester,
                $stats,
                $includeCharts,
                $includeComments,
                $includeAttendance,
                $teacherComments,
                $reportType,
                $user,
                $sectionId,
                $subjectId
            );
    }
}

/**
 * Generate HTML report.
 */
private function generateHtmlReport(
    $filenameBase,
    $section,
    $subject,
    $students,
    $gradingSystem,
    $assessments,
    $studentGrades,
    $schoolYear,
    $semester,
    $stats,
    $includeCharts,
    $includeComments,
    $includeAttendance,
    $teacherComments,
    $reportType,
    $user,
    $sectionId,
    $subjectId
) {
    // Generate HTML report - updated path from 'reports.html' to 'faculty.reports.html'
    $reportHtml = view('faculty.reports.html', compact(
        'section',
        'subject',
        'students',
        'gradingSystem',
        'assessments',
        'studentGrades',
        'schoolYear',
        'semester',
        'stats',
        'includeCharts',
        'includeComments',
        'includeAttendance',
        'teacherComments',
        'reportType',
        'user'
    ))->render();

    // Create filename with extension
    $filename = $filenameBase . '.html';
    $filepath = storage_path('app/public/reports/' . $filename);

    // Ensure the directory exists
    if (!file_exists(storage_path('app/public/reports'))) {
        mkdir(storage_path('app/public/reports'), 0755, true);
    }

    // Save the HTML report
    file_put_contents($filepath, $reportHtml);

    // Create a record in the database to track this report
    $reportId = DB::table('reports')->insertGetId([
        'faculty_id' => $user->id,
        'subject_id' => $subject->id,
        'section_id' => $section->id,
        'filename' => $filename,
        'report_type' => $reportType,
        'file_format' => 'html',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Return a success message with a link to view/download the report
    return redirect()->route('faculty.reports.generate', [
        'sectionId' => $sectionId,
        'subjectId' => $subjectId,
        'schoolYear' => $schoolYear,
        'semester' => $semester,
    ])->with('success', 'Report generated successfully. <a href="' . route('faculty.reports.view', ['id' => $reportId]) . '" class="alert-link" target="_blank">Click here to view the report</a>');
}/**
 * Generate PDF report.
 */
private function generatePdfReport(
    $filenameBase,
    $section,
    $subject,
    $students,
    $gradingSystem,
    $assessments,
    $studentGrades,
    $schoolYear,
    $semester,
    $stats,
    $includeCharts,
    $includeComments,
    $includeAttendance,
    $teacherComments,
    $reportType,
    $user
) {
    try {
        // Try to use a PDF library if available
        if (class_exists('\Barryvdh\DomPDF\Facade\Pdf') || class_exists('\Barryvdh\DomPDF\Facade')) {
            $pdf = null;

            if (class_exists('\Barryvdh\DomPDF\Facade\Pdf')) {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('faculty.reports.pdf', compact(
                    'section', 'subject', 'students', 'gradingSystem', 'assessments', 'studentGrades',
                    'schoolYear', 'semester', 'stats', 'includeCharts', 'includeComments',
                    'includeAttendance', 'teacherComments', 'reportType', 'user'
                ));
            } else {
                $pdf = app('dompdf.wrapper')->loadView('faculty.reports.pdf', compact(
                    'section', 'subject', 'students', 'gradingSystem', 'assessments', 'studentGrades',
                    'schoolYear', 'semester', 'stats', 'includeCharts', 'includeComments',
                    'includeAttendance', 'teacherComments', 'reportType', 'user'
                ));
            }

            return $pdf->download($filenameBase . '.pdf');
        }

        // If TCPDF is available
        if (class_exists('\TCPDF')) {
            // Implementation for TCPDF would go here
            throw new \Exception('TCPDF implementation not available yet.');
        }

        // If mPDF is available
        if (class_exists('\Mpdf\Mpdf')) {
            // Implementation for mPDF would go here
            throw new \Exception('mPDF implementation not available yet.');
        }

        // No PDF library available, fallback to HTML
        throw new \Exception('No PDF generation library available.');

    } catch (\Exception $e) {
        \Log::warning('PDF generation failed, falling back to HTML: ' . $e->getMessage());

        // Generate HTML with notice about PDF - fixed path
        $reportHtml = view('faculty.reports.html', compact(
            'section', 'subject', 'students', 'gradingSystem', 'assessments', 'studentGrades',
            'schoolYear', 'semester', 'stats', 'includeCharts', 'includeComments',
            'includeAttendance', 'teacherComments', 'reportType', 'user'
        ))->render();

        // Insert PDF fallback notice
        $pdfNotice = '<div style="background-color: #f8d7da; color: #721c24; padding: 15px; margin: 20px 0; border: 1px solid #f5c6cb; border-radius: 4px;">';
        $pdfNotice .= '<strong>PDF Generation Not Available</strong><br>PDF generation is currently unavailable. This HTML report has been provided instead. ';
        $pdfNotice .= 'You can use your browser\'s print function to save this as a PDF file.</div>';

        $reportHtml = str_replace('<body>', '<body>' . $pdfNotice, $reportHtml);

        // Create filename with extension
        $filename = $filenameBase . '_pdf_fallback.html';
        $filepath = storage_path('app/public/reports/' . $filename);

        // Ensure the directory exists
        if (!file_exists(storage_path('app/public/reports'))) {
            mkdir(storage_path('app/public/reports'), 0755, true);
        }

        // Save the HTML report
        file_put_contents($filepath, $reportHtml);

        // Create a record in the database to track this report
        $reportId = DB::table('reports')->insertGetId([
            'faculty_id' => $user->id,
            'subject_id' => $subject->id,
            'section_id' => $section->id,
            'filename' => $filename,
            'report_type' => $reportType,
            'file_format' => 'html',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $message = 'PDF generation is not available. An HTML report has been generated instead. ';
        $message .= '<a href="' . route('faculty.reports.view', ['id' => $reportId]) . '" class="alert-link" target="_blank">Click here to view the report</a>';

        return redirect()->back()->with('info', $message);
    }
}

/**
 * Generate Excel report.
 */
private function generateExcelReport(
    $filenameBase,
    $section,
    $subject,
    $students,
    $gradingSystem,
    $assessments,
    $studentGrades,
    $schoolYear,
    $semester,
    $stats,
    $includeCharts,
    $includeComments,
    $includeAttendance,
    $teacherComments,
    $reportType,
    $user
) {
    try {
        // Try to use Laravel Excel if available
        if (class_exists('\Maatwebsite\Excel\Facades\Excel')) {
            // Check if the export class exists
            if (!class_exists('\App\Exports\ReportExport')) {
                throw new \Exception('ReportExport class is not available.');
            }

            $export = new \App\Exports\ReportExport(
                $section,
                $subject,
                $students,
                $gradingSystem,
                $assessments,
                $studentGrades,
                $schoolYear,
                $semester,
                $stats,
                $includeCharts,
                $includeComments,
                $includeAttendance,
                $teacherComments,
                $reportType
            );

            return \Maatwebsite\Excel\Facades\Excel::download($export, $filenameBase . '.xlsx');
        }

        // If PhpSpreadsheet is available directly
        if (class_exists('\PhpOffice\PhpSpreadsheet\Spreadsheet')) {
            // Implementation for direct PhpSpreadsheet would go here
            throw new \Exception('Direct PhpSpreadsheet implementation not available yet.');
        }

        // No Excel library available, fallback to CSV
        throw new \Exception('No Excel generation library available.');

    } catch (\Exception $e) {
        \Log::warning('Excel generation failed, falling back to CSV: ' . $e->getMessage());

        // Generate a simple CSV file
        $filename = $filenameBase . '.csv';
        $filepath = storage_path('app/public/reports/' . $filename);

        // Ensure the directory exists
        if (!file_exists(storage_path('app/public/reports'))) {
            mkdir(storage_path('app/public/reports'), 0755, true);
        }

        // Create CSV file
        $fp = fopen($filepath, 'w');

        // Write headers
        fputcsv($fp, ['Report Type', $reportType]);
        fputcsv($fp, ['Subject', $subject->code . ' - ' . $subject->name]);
        fputcsv($fp, ['Section', $section->name]);
        fputcsv($fp, ['School Year', $schoolYear]);
        fputcsv($fp, ['Semester', $semester]);
        fputcsv($fp, ['Generated On', date('F d, Y')]);
        fputcsv($fp, []);

        // Class statistics
        fputcsv($fp, ['Class Statistics']);
        fputcsv($fp, ['Total Students', $stats['total_students']]);
        fputcsv($fp, ['Passing', $stats['passing_count'], number_format($stats['passing_percentage'], 2) . '%']);
        fputcsv($fp, ['Failing', $stats['failing_count'], number_format($stats['failing_percentage'], 2) . '%']);
        fputcsv($fp, []);

        // Grading system
        fputcsv($fp, ['Grading System']);
        fputcsv($fp, ['Quizzes', $gradingSystem->quiz_percentage . '%']);
        fputcsv($fp, ['Unit Tests', $gradingSystem->unit_test_percentage . '%']);
        fputcsv($fp, ['Activities', $gradingSystem->activity_percentage . '%']);
        fputcsv($fp, ['Exams', $gradingSystem->exam_percentage . '%']);
        fputcsv($fp, []);

        // Student grades header
        fputcsv($fp, ['Student Grades']);
        fputcsv($fp, ['#', 'Student Number', 'Student Name', 'Midterm', 'Final', 'Overall', 'Status']);

        // Student grades data
        foreach ($studentGrades as $index => $grade) {
            fputcsv($fp, [
                $index + 1,
                $grade['student']->student_number,
                $grade['student']->name,
                number_format($grade['midterm_grade'], 2),
                number_format($grade['final_grade'], 2),
                number_format($grade['overall_grade'], 2),
                $grade['status']
            ]);
        }
        fputcsv($fp, []);

        // Assessment breakdown if there are any
        if (count($assessments) > 0) {
            fputcsv($fp, ['Assessment Breakdown']);
            fputcsv($fp, ['Assessment', 'Type', 'Term', 'Max Score', 'Class Average']);

            foreach ($assessments as $assessment) {
                $scores = DB::table('student_scores')
                    ->where('assessment_id', $assessment->id)
                    ->get();

                $count = $scores->count();
                $avg = $count > 0 ? $scores->sum('score') / $count : 0;
                $avgPercent = $assessment->max_score > 0 ? ($avg / $assessment->max_score) * 100 : 0;

                fputcsv($fp, [
                    $assessment->title,
                    ucfirst(str_replace('_', ' ', $assessment->type)),
                    ucfirst($assessment->term),
                    $assessment->max_score,
                    number_format($avg, 2) . ' (' . number_format($avgPercent, 2) . '%)'
                ]);
            }
            fputcsv($fp, []);
        }

        // Teacher comments if included
        if ($includeComments && !empty($teacherComments)) {
            fputcsv($fp, ['Faculty Comments']);
            fputcsv($fp, [$teacherComments]);
            fputcsv($fp, []);
        }

        // Close the file
        fclose($fp);

        // Return the file as download
        return response()->download($filepath)->deleteFileAfterSend(true);
    }
}

/**
 * View a generated report.
 */
public function viewReport($id)
{
    $user = Auth::user();

    // Find the report
    $report = DB::table('reports')->where('id', $id)->first();

    if (!$report) {
        return abort(404, 'Report not found');
    }

    // Check if this user is authorized to view this report
    if ($report->faculty_id != $user->id) {
        return abort(403, 'You are not authorized to view this report');
    }

    // Get the file path
    $filepath = storage_path('app/public/reports/' . $report->filename);

    if (!file_exists($filepath)) {
        return abort(404, 'Report file not found');
    }

    // Return the file content
    return response()->file($filepath);
}

    /**
     * List all syllabi uploaded by the faculty.
     */
    public function listSyllabi()
    {
        $user = Auth::user();

        $syllabi = DB::table('syllabi')
            ->where('faculty_id', $user->id)
            ->join('subjects', 'syllabi.subject_id', '=', 'subjects.id')
            ->select('syllabi.*', 'subjects.name as subject_name', 'subjects.code as subject_code')
            ->orderBy('syllabi.upload_timestamp', 'desc')
            ->get();

        $assignedClass = DB::table('section_subject')
            ->where('faculty_id', $user->id)
            ->first();

        if ($assignedClass) {
            $section = DB::table('sections')->where('id', $assignedClass->section_id)->first();
            $subject = DB::table('subjects')->where('id', $assignedClass->subject_id)->first();
            $schoolYear = $assignedClass->school_year;
            $semester = $assignedClass->semester;
        } else {
            $section = null;
            $subject = null;
            $schoolYear = null;
            $semester = null;
        }

        return view('faculty.syllabus.index', compact('syllabi', 'section', 'subject', 'schoolYear', 'semester'));
    }

    public function exportExcel($id)
{
    $user = Auth::user();

    // Find the report
    $report = DB::table('reports')->where('id', $id)->first();

    if (!$report) {
        return abort(404, 'Report not found');
    }

    // Check if this user is authorized to view this report
    if ($report->faculty_id != $user->id) {
        return abort(403, 'You are not authorized to view this report');
    }

    // Get necessary data for the report
    $section = DB::table('sections')->where('id', $report->section_id)->first();
    $subject = DB::table('subjects')->where('id', $report->subject_id)->first();

    // Create a simple CSV file as a fallback
    $filename = Str::slug("{$subject->code}_{$section->name}_{$report->report_type}_" . date('Y-m-d-His')) . '.csv';
    $filepath = storage_path('app/public/reports/' . $filename);

    // Retrieve all the data we need to reconstruct the report
    $students = DB::table('section_student')
        ->where('section_id', $report->section_id)
        ->join('users', 'section_student.student_id', '=', 'users.id')
        ->select('users.*')
        ->get();

    $gradingSystem = $this->getGradingSystem($report->subject_id, null, null);

    $assessments = DB::table('assessments')
        ->where('subject_id', $report->subject_id)
        ->where('faculty_id', $user->id)
        ->orderBy('term')
        ->orderBy('type')
        ->get();

    if (!$assessments) {
        $assessments = collect([]);
    }

    // Calculate student grades
    $studentGrades = [];
    $passingCount = 0;
    $failingCount = 0;

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

        if ($overallGrade >= 75) {
            $passingCount++;
        } else {
            $failingCount++;
        }
    }

    $stats = [
        'total_students' => count($students),
        'passing_count' => $passingCount,
        'failing_count' => $failingCount,
        'passing_percentage' => count($students) > 0 ? ($passingCount / count($students) * 100) : 0,
        'failing_percentage' => count($students) > 0 ? ($failingCount / count($students) * 100) : 0,
    ];

    // Create CSV file
    $fp = fopen($filepath, 'w');

    // Write headers
    fputcsv($fp, ['Report Type', $report->report_type]);
    fputcsv($fp, ['Subject', $subject->code . ' - ' . $subject->name]);
    fputcsv($fp, ['Section', $section->name]);
    fputcsv($fp, ['Generated On', date('F d, Y')]);
    fputcsv($fp, []);

    // Class statistics
    fputcsv($fp, ['Class Statistics']);
    fputcsv($fp, ['Total Students', $stats['total_students']]);
    fputcsv($fp, ['Passing', $stats['passing_count'], number_format($stats['passing_percentage'], 2) . '%']);
    fputcsv($fp, ['Failing', $stats['failing_count'], number_format($stats['failing_percentage'], 2) . '%']);
    fputcsv($fp, []);

    // Grading system
    fputcsv($fp, ['Grading System']);
    fputcsv($fp, ['Quizzes', $gradingSystem->quiz_percentage . '%']);
    fputcsv($fp, ['Unit Tests', $gradingSystem->unit_test_percentage . '%']);
    fputcsv($fp, ['Activities', $gradingSystem->activity_percentage . '%']);
    fputcsv($fp, ['Exams', $gradingSystem->exam_percentage . '%']);
    fputcsv($fp, []);

    // Student grades header
    fputcsv($fp, ['Student Grades']);
    fputcsv($fp, ['#', 'Student Number', 'Student Name', 'Midterm', 'Final', 'Overall', 'Status']);

    // Student grades data
    foreach ($studentGrades as $index => $grade) {
        fputcsv($fp, [
            $index + 1,
            $grade['student']->student_number,
            $grade['student']->name,
            number_format($grade['midterm_grade'], 2),
            number_format($grade['final_grade'], 2),
            number_format($grade['overall_grade'], 2),
            $grade['status']
        ]);
    }
    fputcsv($fp, []);

    // Assessment breakdown if there are any
    if (count($assessments) > 0) {
        fputcsv($fp, ['Assessment Breakdown']);
        fputcsv($fp, ['Assessment', 'Type', 'Term', 'Max Score', 'Class Average']);

        foreach ($assessments as $assessment) {
            $scores = DB::table('student_scores')
                ->where('assessment_id', $assessment->id)
                ->get();

            $count = $scores->count();
            $avg = $count > 0 ? $scores->sum('score') / $count : 0;
            $avgPercent = $assessment->max_score > 0 ? ($avg / $assessment->max_score) * 100 : 0;

            fputcsv($fp, [
                $assessment->title,
                ucfirst(str_replace('_', ' ', $assessment->type)),
                ucfirst($assessment->term),
                $assessment->max_score,
                number_format($avg, 2) . ' (' . number_format($avgPercent, 2) . '%)'
            ]);
        }
    }

    // Close the file
    fclose($fp);

    // Return the file as download
    return response()->download($filepath, $filename, [
        'Content-Type' => 'text/csv',
    ])->deleteFileAfterSend(true);
}

/**
 * Export a report to PDF format
 */
public function exportPdf($id)
{
    $user = Auth::user();

    // Find the report
    $report = DB::table('reports')->where('id', $id)->first();

    if (!$report) {
        return abort(404, 'Report not found');
    }

    // Check if this user is authorized to view this report
    if ($report->faculty_id != $user->id) {
        return abort(403, 'You are not authorized to view this report');
    }

    // For now, just redirect to the HTML report view
    // In a future update, this would generate an actual PDF
    return redirect()->route('faculty.reports.view', ['id' => $id]);
}
}
