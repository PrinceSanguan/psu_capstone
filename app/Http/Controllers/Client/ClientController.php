<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    /**
     * Display student dashboard with overview
     */
    public function index()
    {
        $user = Auth::user();

        // Get enrolled sections and subjects for the student
        $enrollments = DB::table('section_student')
            ->where('student_id', $user->id)
            ->join('sections', 'section_student.section_id', '=', 'sections.id')
            ->join('section_subject', function ($join) {
                $join->on('section_student.section_id', '=', 'section_subject.section_id')
                     ->on('section_student.school_year', '=', 'section_subject.school_year')
                     ->on('section_student.semester', '=', 'section_subject.semester');
            })
            ->join('subjects', 'section_subject.subject_id', '=', 'subjects.id')
            ->join('users as faculty', 'section_subject.faculty_id', '=', 'faculty.id')
            ->select(
                'sections.id as section_id',
                'sections.name as section_name',
                'subjects.id as subject_id',
                'subjects.name as subject_name',
                'subjects.code as subject_code',
                'faculty.name as faculty_name',
                'section_student.school_year',
                'section_student.semester'
            )
            ->orderBy('section_student.school_year', 'desc')
            ->orderBy('section_student.semester', 'desc')
            ->get();

        // Get upcoming assessments (quizzes, activities, etc.) for the student's enrolled classes
        $upcomingAssessments = DB::table('assessments')
            ->join('section_subject', 'assessments.subject_id', '=', 'section_subject.subject_id')
            ->join('section_student', function ($join) {
                $join->on('section_subject.section_id', '=', 'section_student.section_id')
                     ->on('section_subject.school_year', '=', 'section_student.school_year')
                     ->on('section_subject.semester', '=', 'section_student.semester');
            })
            ->where('section_student.student_id', $user->id)
            ->whereNotNull('assessments.schedule_date')
            ->whereDate('assessments.schedule_date', '>=', now()->toDateString())
            ->join('subjects', 'assessments.subject_id', '=', 'subjects.id')
            ->join('users as faculty', 'assessments.faculty_id', '=', 'faculty.id')
            ->select(
                'assessments.*',
                'subjects.name as subject_name',
                'subjects.code as subject_code',
                'faculty.name as faculty_name'
            )
            ->orderBy('assessments.schedule_date')
            ->orderBy('assessments.schedule_time')
            ->limit(10)
            ->get();

        // Get recent scores/grades for the student
        $recentScores = DB::table('student_scores')
            ->where('student_id', $user->id)
            ->join('assessments', 'student_scores.assessment_id', '=', 'assessments.id')
            ->join('subjects', 'assessments.subject_id', '=', 'subjects.id')
            ->select(
                'student_scores.*',
                'assessments.title as assessment_title',
                'assessments.type as assessment_type',
                'assessments.max_score as max_score',
                'subjects.name as subject_name',
                'subjects.code as subject_code'
            )
            ->orderBy('student_scores.created_at', 'desc')
            ->limit(10)
            ->get();

        // Calculate total subjects and other statistics
        $totalSubjects = $enrollments->unique('subject_id')->count();
        $totalSections = $enrollments->unique('section_id')->count();

        // Get unread messages count
        $unreadMessages = DB::table('messages')
            ->where('recipient_id', $user->id)
            ->where('read', false)
            ->count();

        return view('client.dashboard', compact(
            'user',
            'enrollments',
            'upcomingAssessments',
            'recentScores',
            'totalSubjects',
            'totalSections',
            'unreadMessages'
        ));
    }

    /**
     * Display list of enrolled subjects/classes
     */
    public function myClasses()
    {
        $user = Auth::user();

        // Get all enrolled classes for the student
        $enrollments = DB::table('section_student')
            ->where('student_id', $user->id)
            ->join('sections', 'section_student.section_id', '=', 'sections.id')
            ->join('section_subject', function ($join) {
                $join->on('section_student.section_id', '=', 'section_subject.section_id')
                     ->on('section_student.school_year', '=', 'section_subject.school_year')
                     ->on('section_student.semester', '=', 'section_subject.semester');
            })
            ->join('subjects', 'section_subject.subject_id', '=', 'subjects.id')
            ->join('users as faculty', 'section_subject.faculty_id', '=', 'faculty.id')
            ->select(
                'sections.id as section_id',
                'sections.name as section_name',
                'subjects.id as subject_id',
                'subjects.name as subject_name',
                'subjects.code as subject_code',
                'faculty.name as faculty_name',
                'faculty.id as faculty_id',
                'section_student.school_year',
                'section_student.semester'
            )
            ->orderBy('section_student.school_year', 'desc')
            ->orderBy('section_student.semester', 'desc')
            ->get();

        return view('client.classes.index', compact('enrollments'));
    }

    /**
     * View details of a specific class including grades, assessments, etc.
     */
    public function classDetails($sectionId, $subjectId, $schoolYear, $semester)
    {
        $user = Auth::user();

        // Check if student is enrolled in this class
        $isEnrolled = DB::table('section_student')
            ->where('student_id', $user->id)
            ->where('section_id', $sectionId)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->exists();

        if (!$isEnrolled) {
            return redirect()->route('client.classes.index')
                ->with('error', 'You are not enrolled in this class');
        }

        // Get section and subject details
        $section = DB::table('sections')->where('id', $sectionId)->first();
        $subject = DB::table('subjects')->where('id', $subjectId)->first();

        // Get faculty details
        $faculty = DB::table('section_subject')
            ->where('section_id', $sectionId)
            ->where('subject_id', $subjectId)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->join('users', 'section_subject.faculty_id', '=', 'users.id')
            ->select('users.*')
            ->first();

        // Get assessments for this class
        $assessments = DB::table('assessments')
            ->where('subject_id', $subjectId)
            ->where('faculty_id', $faculty->id)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->orderBy('term')
            ->orderBy('type')
            ->orderBy('created_at')
            ->get();

        // Get student's scores for these assessments
        $scores = DB::table('student_scores')
            ->where('student_id', $user->id)
            ->whereIn('assessment_id', $assessments->pluck('id')->toArray())
            ->pluck('score', 'assessment_id');

        // Get grading system
        $gradingSystem = DB::table('grading_systems')
            ->where('subject_id', $subjectId)
            ->first();

        if (!$gradingSystem) {
            $gradingSystem = (object)[
                'quiz_percentage' => 25,
                'unit_test_percentage' => 25,
                'activity_percentage' => 25,
                'exam_percentage' => 25
            ];
        }

        // Calculate midterm and final grades
        $midtermGrade = $this->calculateTermGrade('midterm', $user->id, $assessments, $gradingSystem);
        $finalGrade = $this->calculateTermGrade('final', $user->id, $assessments, $gradingSystem);
        $overallGrade = ($midtermGrade + $finalGrade) / 2;

        // Get syllabus
        $syllabus = DB::table('syllabi')
            ->where('subject_id', $subjectId)
            ->where('faculty_id', $faculty->id)
            ->where('school_year', $schoolYear)
            ->where('semester', $semester)
            ->first();

        return view('client.classes.details', compact(
            'section',
            'subject',
            'faculty',
            'assessments',
            'scores',
            'gradingSystem',
            'midtermGrade',
            'finalGrade',
            'overallGrade',
            'syllabus',
            'schoolYear',
            'semester'
        ));
    }

    /**
     * Helper method to calculate term grades
     */
    private function calculateTermGrade($term, $studentId, $assessments, $gradingSystem)
    {
        $termAssessments = $assessments->where('term', $term);

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
     * View scheduled assessments/activities
     */
    public function viewSchedules()
    {
        $user = Auth::user();

        // Get upcoming assessments
        $upcomingAssessments = DB::table('assessments')
            ->join('section_subject', 'assessments.subject_id', '=', 'section_subject.subject_id')
            ->join('section_student', function ($join) {
                $join->on('section_subject.section_id', '=', 'section_student.section_id')
                     ->on('section_subject.school_year', '=', 'section_student.school_year')
                     ->on('section_subject.semester', '=', 'section_student.semester');
            })
            ->where('section_student.student_id', $user->id)
            ->whereNotNull('assessments.schedule_date')
            ->join('subjects', 'assessments.subject_id', '=', 'subjects.id')
            ->join('users as faculty', 'assessments.faculty_id', '=', 'faculty.id')
            ->select(
                'assessments.*',
                'subjects.name as subject_name',
                'subjects.code as subject_code',
                'faculty.name as faculty_name'
            )
            ->orderBy('assessments.schedule_date')
            ->orderBy('assessments.schedule_time')
            ->get();

        // Group by month for calendar view
        $assessmentsByMonth = $upcomingAssessments->groupBy(function ($assessment) {
            return date('Y-m', strtotime($assessment->schedule_date));
        });

        return view('client.schedules.index', compact('upcomingAssessments', 'assessmentsByMonth'));
    }

    /**
     * View all grades
     */
    public function viewGrades()
    {
        $user = Auth::user();

        // Get enrolled sections and subjects for the student
        $enrollments = DB::table('section_student')
            ->where('student_id', $user->id)
            ->join('sections', 'section_student.section_id', '=', 'sections.id')
            ->join('section_subject', function ($join) {
                $join->on('section_student.section_id', '=', 'section_subject.section_id')
                     ->on('section_student.school_year', '=', 'section_subject.school_year')
                     ->on('section_student.semester', '=', 'section_subject.semester');
            })
            ->join('subjects', 'section_subject.subject_id', '=', 'subjects.id')
            ->join('users as faculty', 'section_subject.faculty_id', '=', 'faculty.id')
            ->select(
                'section_subject.section_id',
                'section_subject.subject_id',
                'section_student.school_year',
                'section_student.semester',
                'sections.name as section_name',
                'subjects.name as subject_name',
                'subjects.code as subject_code',
                'faculty.name as faculty_name',
                'faculty.id as faculty_id'
            )
            ->orderBy('section_student.school_year', 'desc')
            ->orderBy('section_student.semester', 'desc')
            ->get();

        $subjectGrades = [];

        foreach ($enrollments as $enrollment) {
            // Get assessments for this class
            $assessments = DB::table('assessments')
                ->where('subject_id', $enrollment->subject_id)
                ->where('faculty_id', $enrollment->faculty_id)
                ->where('school_year', $enrollment->school_year)
                ->where('semester', $enrollment->semester)
                ->get();

            if ($assessments->isEmpty()) {
                continue;
            }

            // Get grading system
            $gradingSystem = DB::table('grading_systems')
                ->where('subject_id', $enrollment->subject_id)
                ->first();

            if (!$gradingSystem) {
                $gradingSystem = (object)[
                    'quiz_percentage' => 25,
                    'unit_test_percentage' => 25,
                    'activity_percentage' => 25,
                    'exam_percentage' => 25
                ];
            }

            // Calculate grades
            $midtermGrade = $this->calculateTermGrade('midterm', $user->id, $assessments, $gradingSystem);
            $finalGrade = $this->calculateTermGrade('final', $user->id, $assessments, $gradingSystem);
            $overallGrade = ($midtermGrade + $finalGrade) / 2;

            $subjectGrades[] = [
                'subject_code' => $enrollment->subject_code,
                'subject_name' => $enrollment->subject_name,
                'section_name' => $enrollment->section_name,
                'faculty_name' => $enrollment->faculty_name,
                'school_year' => $enrollment->school_year,
                'semester' => $enrollment->semester,
                'midterm_grade' => $midtermGrade,
                'final_grade' => $finalGrade,
                'overall_grade' => $overallGrade,
                'status' => $overallGrade >= 75 ? 'Passing' : 'Failing',
                'section_id' => $enrollment->section_id,
                'subject_id' => $enrollment->subject_id
            ];
        }

        return view('client.grades.index', compact('subjectGrades'));
    }

    /**
     * View chat interface
     */
    public function viewMessages()
    {
        $user = Auth::user();

        // Get all faculty members teaching the student
        $teachers = DB::table('section_student')
            ->where('student_id', $user->id)
            ->join('section_subject', function ($join) {
                $join->on('section_student.section_id', '=', 'section_subject.section_id')
                     ->on('section_student.school_year', '=', 'section_subject.school_year')
                     ->on('section_student.semester', '=', 'section_subject.semester');
            })
            ->join('users as faculty', 'section_subject.faculty_id', '=', 'faculty.id')
            ->join('subjects', 'section_subject.subject_id', '=', 'subjects.id')
            ->select(
                'faculty.id as faculty_id',
                'faculty.name as faculty_name',
                'subjects.name as subject_name',
                'subjects.code as subject_code'
            )
            ->distinct()
            ->get();

        // Get recent conversations
        $conversations = DB::table('messages')
            ->where('sender_id', $user->id)
            ->orWhere('recipient_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique(function ($message) use ($user) {
                return $message->sender_id == $user->id ? $message->recipient_id : $message->sender_id;
            })
            ->take(10);

        $conversationUsers = collect();

        foreach ($conversations as $conversation) {
            $otherId = $conversation->sender_id == $user->id ? $conversation->recipient_id : $conversation->sender_id;

            $otherUser = DB::table('users')
                ->where('id', $otherId)
                ->first();

            if ($otherUser) {
                $conversationUsers->push($otherUser);
            }
        }

        // Mark all messages as read for initial display
        DB::table('messages')
            ->where('recipient_id', $user->id)
            ->where('read', false)
            ->update(['read' => true]);

        return view('client.messages.index', compact('teachers', 'conversationUsers'));
    }

    /**
     * Get conversation with a specific user
     */
    public function getConversation($userId)
    {
        $user = Auth::user();

        // Get messages between the two users
        $messages = DB::table('messages')
            ->where(function ($query) use ($user, $userId) {
                $query->where('sender_id', $user->id)
                      ->where('recipient_id', $userId);
            })
            ->orWhere(function ($query) use ($user, $userId) {
                $query->where('sender_id', $userId)
                      ->where('recipient_id', $user->id);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Get user details
        $otherUser = DB::table('users')
            ->where('id', $userId)
            ->first();

        // Mark messages as read
        DB::table('messages')
            ->where('sender_id', $userId)
            ->where('recipient_id', $user->id)
            ->where('read', false)
            ->update(['read' => true]);

        return response()->json([
            'messages' => $messages,
            'user' => $otherUser
        ]);
    }

    /**
     * Send a message
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000'
        ]);

        $user = Auth::user();

        // Check if recipient is a faculty member teaching this student
        $isTeacher = DB::table('section_student')
            ->where('student_id', $user->id)
            ->join('section_subject', function ($join) {
                $join->on('section_student.section_id', '=', 'section_subject.section_id')
                     ->on('section_student.school_year', '=', 'section_subject.school_year')
                     ->on('section_student.semester', '=', 'section_subject.semester');
            })
            ->where('section_subject.faculty_id', $request->recipient_id)
            ->exists();

        if (!$isTeacher) {
            return response()->json([
                'error' => 'You can only send messages to your teachers'
            ], 403);
        }

        // Save the message
        $messageId = DB::table('messages')->insertGetId([
            'sender_id' => $user->id,
            'recipient_id' => $request->recipient_id,
            'message' => $request->message,
            'read' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $message = DB::table('messages')
            ->where('id', $messageId)
            ->first();

        return response()->json([
            'message' => $message,
            'success' => true
        ]);
    }

    /**
     * Download a syllabus file
     */
    public function downloadSyllabus($id)
    {
        $user = Auth::user();
        $syllabus = DB::table('syllabi')->where('id', $id)->first();

        if (!$syllabus) {
            abort(404, 'Syllabus not found');
        }

        // Check if student is enrolled in the class that has this syllabus
        $isEnrolled = DB::table('section_student')
            ->where('student_id', $user->id)
            ->join('section_subject', function ($join) use ($syllabus) {
                $join->on('section_student.section_id', '=', 'section_subject.section_id')
                     ->on('section_student.school_year', '=', 'section_subject.school_year')
                     ->on('section_student.semester', '=', 'section_subject.semester')
                     ->where('section_subject.subject_id', '=', $syllabus->subject_id)
                     ->where('section_subject.faculty_id', '=', $syllabus->faculty_id);
            })
            ->exists();

        if (!$isEnrolled) {
            abort(403, 'Unauthorized access');
        }

        return Storage::download($syllabus->file_path, $syllabus->original_filename);
    }
}
