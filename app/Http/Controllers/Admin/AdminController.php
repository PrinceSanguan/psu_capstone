<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\GradingSystem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Services\SchoolYearService;

class AdminController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $students = User::where('user_role', 'client')->get();
        $subjects = DB::table('subjects')->orderBy('name')->get();

        return view('admin.dashboard.index', compact('students', 'subjects', 'user'));
    }



    /**
     * =============== TEACHER SYLLABI UPLOADS ================
     * Show all syllabi with upload times
     * (timestamp stored in 'syllabi.upload_timestamp')
     */
    public function viewTeacherSyllabi()
    {
        // Join with users (for faculty name) and subjects (for subject code/name)
        $syllabi = DB::table('syllabi')
            ->join('users','syllabi.faculty_id','=','users.id')
            ->join('subjects','syllabi.subject_id','=','subjects.id')
            ->select(
                'syllabi.*',
                'users.name as faculty_name',
                'subjects.code as subject_code',
                'subjects.name as subject_name'
            )
            ->orderBy('syllabi.upload_timestamp','desc')
            ->get();

        return view('admin.syllabi.index', compact('syllabi'));
    }


    /**
     * =============== GRADING SYSTEM ================
     * Admin can set or update the grading system
     */
    public function editGradingSystem($subjectId)
    {
        // If you have an Eloquent model
        $gradingSystem = GradingSystem::firstOrNew(['subject_id' => $subjectId]);
        $subject = DB::table('subjects')->where('id', $subjectId)->first();


        return view('admin.grading.edit', compact('gradingSystem','subject'));
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

        return redirect()->back()
                         ->with('success','Grading System updated!');
    }

    /**
     * =============== STUDENT MANAGEMENT (Add/Remove) ================
     */
    public function createStudent()
    {
        return view('admin.students.create');
    }

    public function storeStudent(Request $request)
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
            'user_role'      => 'client', // student
        ]);

        return redirect()->route('admin.dashboard')
                         ->with('success','Student added successfully!');
    }

    public function deleteStudent($id)
    {
        // Remove the user with role=client
        $student = User::where('id',$id)->where('user_role','client')->firstOrFail();
        $student->delete();

        return redirect()->route('admin.dashboard')
                         ->with('success','Student removed successfully!');
    }

    /**
     * =============== FACULTY CRUD ================
     * (create, list, update, etc.)
     */
    public function facultyList()
    {
        $faculty = User::where('user_role', 'faculty')->get();
        return view('admin.faculty.index', compact('faculty'));
    }

    public function createFaculty()
    {
        return view('admin.faculty.create');
    }

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

        return redirect()
            ->route('admin.faculty.index')
            ->with('success', 'Faculty member added successfully');
    }

    public function editFaculty($id)
    {
        $faculty = User::findOrFail($id);
        return view('admin.faculty.edit', compact('faculty'));
    }
    public function updateFaculty(Request $request, $id)
    {
        $faculty = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'student_number' => 'required|string|max:255|unique:users,student_number,' . $id,
            'major' => 'nullable|string|max:255',
            'sex' => 'required|in:M,F',
            'course' => 'required|string|max:255',
            'year' => 'required|string|max:255',
        ]);

        $faculty->update([
            'name' => $validated['name'],
            'student_number' => $validated['student_number'],
            'major' => $validated['major'],
            'sex' => $validated['sex'],
            'course' => $validated['course'],
            'year' => $validated['year'],
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);

            $faculty->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()
            ->route('admin.faculty.index')
            ->with('success', 'Faculty member updated successfully');
    }
    /**
     * =============== FACULTY ASSIGNMENTS ================
     * Let admin assign a faculty to a brand-new section & subject
     */
        public function assignFaculty()
    {
        // 1) Load all faculty (user_role = 'faculty')
        $faculty = User::where('user_role', 'faculty')->get();

        // 2) Load all subjects from DB
        $subjects = DB::table('subjects')->get();

        // 3) Load existing assignments for display
        //    We'll join 'users' for faculty name, 'sections' for section name, 'subjects' for subject name
        $assignments = DB::table('section_subject')
            ->join('users', 'section_subject.faculty_id', '=', 'users.id')
            ->join('sections', 'section_subject.section_id', '=', 'sections.id')
            ->join('subjects', 'section_subject.subject_id', '=', 'subjects.id')
            ->select(
                'section_subject.*',
                'users.name as faculty_name',
                'sections.name as section_name',
                'subjects.name as subject_name'
            )
            ->get();

        // Return the Blade view with all data
        return view('admin.assignments.index', compact('faculty', 'subjects', 'assignments'));
    }


    public function storeFacultyAssignment(Request $request)
    {
        // Validate the data
        $validated = $request->validate([
            'faculty_id'   => 'required|exists:users,id',
            'section_name' => 'required|string|max:255',
            'subject_id'   => 'required|exists:subjects,id',
            'school_year'  => 'required|string|max:20',
            'semester'     => 'required|in:First,Second,Summer',
        ]);

        // Format school year consistently
        $schoolYear = SchoolYearService::format($validated['school_year']);

        // 1) Create a new row in 'sections' table
        $sectionId = DB::table('sections')->insertGetId([
            'name'       => $validated['section_name'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2) We already have an existing subject, so we use subject_id directly
        $subjectId = $validated['subject_id'];

        // 3) Insert into 'section_subject' pivot
        DB::table('section_subject')->insert([
            'faculty_id'  => $validated['faculty_id'],
            'section_id'  => $sectionId,
            'subject_id'  => $subjectId,
            'school_year' => $schoolYear,
            'semester'    => $validated['semester'],
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // Return to the assignments page
        return redirect()->route('admin.assignments.index')
            ->with('success','Faculty assigned successfully!');
    }

    public function deleteFacultyAssignment($id)
        {
            // Just remove from pivot
            DB::table('section_subject')->where('id', $id)->delete();

            return redirect()->route('admin.assignments.index')
                ->with('success','Assignment deleted successfully');
        }

        public function facultyClasses($id)
        {
            // Show all classes assigned to a particular faculty
            $faculty = User::findOrFail($id);

            $classes = DB::table('section_subject')
                ->where('faculty_id', $id)
                ->join('sections', 'section_subject.section_id', '=', 'sections.id')
                ->join('subjects', 'section_subject.subject_id', '=', 'subjects.id')
                ->select(
                    'section_subject.*',
                    'sections.name as section_name',
                    'subjects.name as subject_name',
                    'subjects.code as subject_code', // if you have it
                    'sections.id as section_id',
                    'subjects.id as subject_id'
                )
                ->get();

            return view('admin.assignments.faculty-classes', compact('faculty', 'classes'));
        }

    /**
     * =============== SECTION STUDENTS ================
     */
    public function showSectionStudents($sectionId)
    {
        $section = DB::table('sections')->where('id', $sectionId)->first();
        if (!$section) {
            return redirect()->back()->withErrors(['Section not found.']);
        }

        $allStudents = User::where('user_role', 'client')->get();
        $assigned = DB::table('section_student')
            ->where('section_id', $sectionId)
            ->pluck('student_id')
            ->toArray();

        return view('admin.sections.add-students', [
            'section'         => $section,
            'allStudents'     => $allStudents,
            'assignedStudents'=> $assigned,
        ]);
    }

    public function storeSectionStudents(Request $request, $sectionId)
    {
        $validated = $request->validate([
            'students'   => 'nullable|array',
            'students.*' => 'exists:users,id',
            'school_year'=> 'required|string|max:20',
            'semester'   => 'required|in:First,Second,Summer',
        ]);

        // Format school year consistently
        $schoolYear = SchoolYearService::format($validated['school_year']);

        DB::table('section_student')
            ->where('section_id', $sectionId)
            ->where('school_year', $schoolYear)
            ->where('semester', $validated['semester'])
            ->delete();

        if (!empty($validated['students'])) {
            foreach ($validated['students'] as $studentId) {
                DB::table('section_student')->insert([
                    'section_id'  => $sectionId,
                    'student_id'  => $studentId,
                    'school_year' => $schoolYear,
                    'semester'    => $validated['semester'],
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }

        return redirect()
            ->route('admin.sections.showStudents', $sectionId)
            ->with('success', 'Students updated for section.');
    }

    public function showEnrolledStudents($sectionId)
    {
        $section = DB::table('sections')->where('id', $sectionId)->first();
        if (!$section) {
            return redirect()->back()->withErrors(['error' => 'Section not found.']);
        }

        $enrolledStudents = DB::table('section_student')
            ->where('section_id', $sectionId)
            ->join('users', 'section_student.student_id', '=', 'users.id')
            ->select('users.*')
            ->get();

        return view('admin.assignments.enrolled-students', [
            'section'          => $section,
            'enrolledStudents' => $enrolledStudents,
        ]);
    }
    public function listSubjects()
    {
        $subjects = DB::table('subjects')->orderBy('name')->get();
        return view('admin.subjects.index', compact('subjects'));
    }

    public function createSubject()
    {
        // Show a form to create a new subject
        return view('admin.subjects.create');
    }

    public function storeSubject(Request $request)
    {
        $validated = $request->validate([
            'code' => 'nullable|string|max:100',
            'name' => 'required|string|max:255|unique:subjects,name',
            'units' => 'nullable|integer',
            'description' => 'nullable|string',
        ]);

        DB::table('subjects')->insert([
            'code' => $validated['code'] ?? null,
            'name' => $validated['name'],
            'units' => $validated['units'] ?? null,
            'description' => $validated['description'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.subjects.index')
                         ->with('success','Subject created successfully!');
    }

    public function editSubject($id)
    {
        $subject = DB::table('subjects')->where('id',$id)->first();
        if (!$subject) {
            return redirect()->route('admin.subjects.index')
                             ->withErrors(['Subject not found.']);
        }

        return view('admin.subjects.edit', compact('subject'));
    }

    public function updateSubject(Request $request, $id)
    {
        $subject = DB::table('subjects')->where('id',$id)->first();
        if (!$subject) {
            return redirect()->route('admin.subjects.index')
                             ->withErrors(['Subject not found.']);
        }

        $validated = $request->validate([
            'code' => 'nullable|string|max:100',
            'name' => 'required|string|max:255|unique:subjects,name,' . $id,
            'units' => 'nullable|integer',
            'description' => 'nullable|string',
        ]);

        DB::table('subjects')
            ->where('id',$id)
            ->update([
                'code' => $validated['code'] ?? null,
                'name' => $validated['name'],
                'units' => $validated['units'] ?? null,
                'description' => $validated['description'] ?? null,
                'updated_at' => now(),
            ]);

        return redirect()->route('admin.subjects.index')
                         ->with('success','Subject updated successfully!');
    }
}
