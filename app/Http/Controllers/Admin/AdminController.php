<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\GradingSystem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function index()
    {
        $user = Auth::user();
        // All "client" users are students
        $students = User::where('user_role', 'client')->get();
        return view('admin.dashboard.index', compact('students', 'user'));
    }

    /**
     * Create a Student (only admin can do)
     */
    public function createStudent()
    {
        return view('admin.students.create');
    }

    /**
     * Store a new Student
     */
    public function storeStudent(Request $request)
{
    $validated = $request->validate([
        'name'           => 'required|string|max:255',
        'student_number' => 'required|string|max:255|unique:users,student_number',
        'major'          => 'nullable|string|max:255',  // major can be optional/nullable
        'sex'            => 'required|in:M,F',
        'course'         => 'required|string|max:255',
        'year'           => 'required|string|max:255',
        'password'       => 'required|string|min:8',
    ]);

    User::create([
        'name'           => $validated['name'],
        'student_number' => $validated['student_number'],
        'major'          => $validated['major'] ?? null,
        'sex'            => $validated['sex'],
        'course'         => $validated['course'],
        'year'           => $validated['year'],
        'password'       => Hash::make($validated['password']),
        'user_role'      => 'client', // Mark as student
    ]);

    return redirect()->route('admin.dashboard')
                     ->with('success', 'Student added successfully!');
}


    /**
     * Remove a student from the system
     */
    public function deleteStudent($id)
    {
        $student = User::where('id', $id)
                       ->where('user_role', 'client')
                       ->firstOrFail();
        $student->delete();

        return redirect()->route('admin.dashboard')
                         ->with('success', 'Student removed successfully!');
    }

    /**
     * Show list of all faculty
     */
    public function facultyList()
    {
        $faculty = User::where('user_role', 'faculty')->get();
        return view('admin.faculty.index', compact('faculty'));
    }

    /**
     * Create new faculty
     */
    public function createFaculty()
    {
        return view('admin.faculty.create');
    }

    /**
     * Store new faculty
     */
    public function storeFaculty(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'student_number' => 'required|string|max:255|unique:users,student_number',
            'major'          => 'nullable|string|max:255',
            'sex'            => 'required|in:M,F',
            'course'         => 'required|string|max:255',
            'year'           => 'required|string|max:255',
            'password'       => 'required|string|min:8',
        ]);

        User::create([
            'name'           => $validated['name'],
            'student_number' => $validated['student_number'],
            'major'          => $validated['major'] ?? null,
            'sex'            => $validated['sex'],
            'course'         => $validated['course'],
            'year'           => $validated['year'],
            'password'       => Hash::make($validated['password']),
            'user_role'      => 'faculty',
        ]);

        return redirect()->route('admin.faculty.index')
                         ->with('success', 'Faculty member added successfully');
    }

    /**
     * Edit existing faculty
     */
    public function editFaculty($id)
    {
        $faculty = User::findOrFail($id);
        return view('admin.faculty.edit', compact('faculty'));
    }

    /**
     * Update faculty
     */
    public function updateFaculty(Request $request, $id)
    {
        $faculty = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $faculty->update([
            'name' => $validated['name'],
        ]);

        if ($request->filled('password')) {
            $faculty->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('admin.faculty.index')
                         ->with('success', 'Faculty member updated successfully');
    }

    /**
     * Show the "assign faculty to a section/subject" page
     */
    public function assignFaculty()
    {
        $faculty = User::where('user_role','faculty')->get();
        // existing sections/subjects if you want them
        $sections = DB::table('sections')->get();
        $subjects = DB::table('subjects')->get();

        $assignments = DB::table('section_subject')
            ->join('users','section_subject.faculty_id','=','users.id')
            ->join('sections','section_subject.section_id','=','sections.id')
            ->join('subjects','section_subject.subject_id','=','subjects.id')
            ->select(
                'section_subject.*',
                'users.name as faculty_name',
                'sections.name as section_name',
                'subjects.name as subject_name'
            )
            ->get();

        return view('admin.assignments.index', compact('faculty','sections','subjects','assignments'));
    }

    /**
     * Store a new faculty assignment
     */
    public function storeFacultyAssignment(Request $request)
    {
        $validated = $request->validate([
            'faculty_id'   => 'required|exists:users,id',
            'section_name' => 'required|string|max:255',
            'subject_name' => 'required|string|max:255',
            'school_year'  => 'required|string|max:20',
            'semester'     => 'required|in:First,Second,Summer',
        ]);

        // create new Section
        $sectionId = DB::table('sections')->insertGetId([
            'name'       => $validated['section_name'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // create new Subject
        $subjectId = DB::table('subjects')->insertGetId([
            'name'       => $validated['subject_name'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // link them in pivot
        DB::table('section_subject')->insert([
            'faculty_id'  => $validated['faculty_id'],
            'section_id'  => $sectionId,
            'subject_id'  => $subjectId,
            'school_year' => $validated['school_year'],
            'semester'    => $validated['semester'],
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return redirect()->route('admin.assignments.index')
                         ->with('success','Faculty assigned successfully!');
    }

    /**
     * Delete an assignment from section_subject
     */
    public function deleteFacultyAssignment($id)
    {
        DB::table('section_subject')->where('id',$id)->delete();
        return redirect()->route('admin.assignments.index')
                         ->with('success','Assignment deleted successfully');
    }

    /**
     * Show classes assigned to a particular faculty
     */
    public function facultyClasses($id)
    {
        $faculty = User::findOrFail($id);

        $classes = DB::table('section_subject')
            ->where('faculty_id',$id)
            ->join('sections','section_subject.section_id','=','sections.id')
            ->join('subjects','section_subject.subject_id','=','subjects.id')
            ->select(
                'section_subject.*',
                'sections.name as section_name',
                'subjects.name as subject_name',
                'subjects.code as subject_code',
                'sections.id as section_id',
                'subjects.id as subject_id'
            )
            ->get();

        return view('admin.assignments.faculty-classes', compact('faculty','classes'));
    }

    /**
     * Show the list of students for a particular section
     */
    public function showSectionStudents($sectionId)
    {
        $section = DB::table('sections')->where('id',$sectionId)->first();
        if (!$section) {
            return redirect()->back()->withErrors(['Section not found.']);
        }

        $allStudents = User::where('user_role','client')->get();
        $assigned = DB::table('section_student')
            ->where('section_id',$sectionId)
            ->pluck('student_id')
            ->toArray();

        return view('admin.sections.add-students', [
            'section'        => $section,
            'allStudents'    => $allStudents,
            'assignedStudents' => $assigned,
        ]);
    }

    /**
     * Store the updated list of students for that section
     */
    public function storeSectionStudents(Request $request, $sectionId)
    {
        $validated = $request->validate([
            'students'    => 'nullable|array',
            'students.*'  => 'exists:users,id',
            'school_year' => 'required|string|max:20',
            'semester'    => 'required|in:First,Second,Summer',
        ]);

        // remove old
        DB::table('section_student')
            ->where('section_id',$sectionId)
            ->where('school_year',$validated['school_year'])
            ->where('semester',$validated['semester'])
            ->delete();

        // insert new
        if (!empty($validated['students'])) {
            foreach($validated['students'] as $studentId) {
                DB::table('section_student')->insert([
                    'section_id'  => $sectionId,
                    'student_id'  => $studentId,
                    'school_year' => $validated['school_year'],
                    'semester'    => $validated['semester'],
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }

        return redirect()->route('admin.sections.showStudents',$sectionId)
                         ->with('success','Students updated for section.');
    }

    /**
     * Show the students actually enrolled in a section
     */
    public function showEnrolledStudents($sectionId)
    {
        $section = DB::table('sections')->where('id',$sectionId)->first();
        if (!$section) {
            return redirect()->back()->withErrors(['error'=>'Section not found.']);
        }

        $enrolledStudents = DB::table('section_student')
            ->where('section_id',$sectionId)
            ->join('users','section_student.student_id','=','users.id')
            ->select('users.*')
            ->get();

        return view('admin.assignments.enrolled-students', [
            'section' => $section,
            'enrolledStudents' => $enrolledStudents
        ]);
    }

    /**
     * =============== GRADING SYSTEM ================
     * Admin can set or update the grading system
     */
    public function editGradingSystem($subjectId)
    {
        // If you have a GradingSystem model
        $gradingSystem = GradingSystem::firstOrNew(['subject_id' => $subjectId]);

        return view('admin.grading.edit', compact('gradingSystem'));
    }

    public function updateGradingSystem(Request $request, $subjectId)
    {
        $validated = $request->validate([
            'quiz_percentage'      => 'required|numeric|min:0|max:100',
            'unit_test_percentage' => 'required|numeric|min:0|max:100',
            'activity_percentage'  => 'required|numeric|min:0|max:100',
            'exam_percentage'      => 'required|numeric|min:0|max:100',
        ]);

        $gradingSystem = GradingSystem::firstOrNew(['subject_id' => $subjectId]);
        $gradingSystem->quiz_percentage      = $validated['quiz_percentage'];
        $gradingSystem->unit_test_percentage = $validated['unit_test_percentage'];
        $gradingSystem->activity_percentage  = $validated['activity_percentage'];
        $gradingSystem->exam_percentage      = $validated['exam_percentage'];
        $gradingSystem->save();

        return redirect()->back()->with('success','Grading System updated!');
    }

    /**
     * =============== TRACK TEACHER SYLLABUS UPLOADS ================
     * Show all syllabi with upload times
     */
    public function viewTeacherSyllabi()
    {
        // Example: 'syllabi' table with 'upload_timestamp' & 'faculty_id'
        $syllabi = DB::table('syllabi')
            ->join('users','syllabi.faculty_id','=','users.id')
            ->select('syllabi.*','users.name as faculty_name')
            ->orderBy('syllabi.upload_timestamp','desc')
            ->get();

        return view('admin.syllabi.index', compact('syllabi'));
    }
}
