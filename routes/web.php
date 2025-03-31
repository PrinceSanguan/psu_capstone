<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Faculty\FacultyController;

/*
|--------------------------------------------------------------------------
| Public/Homepage
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('welcome');

/*
|--------------------------------------------------------------------------
| Login Logic
|--------------------------------------------------------------------------
*/
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('login', [LoginController::class, 'processLogin'])->name('login.submit');
Route::get('logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Logic
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware(['auth','admin'])
    ->name('admin.')
    ->group(function() {

        // Admin Dashboard
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

        // Student Management (Add/Remove)
        Route::get('/students/create', [AdminController::class, 'createStudent'])->name('createStudent');
        Route::post('/students', [AdminController::class, 'storeStudent'])->name('storeStudent');
        Route::delete('/students/{id}', [AdminController::class, 'deleteStudent'])->name('deleteStudent');

        // Faculty Management
        Route::get('/faculty', [AdminController::class, 'facultyList'])->name('faculty.index');
        Route::get('/faculty/create', [AdminController::class, 'createFaculty'])->name('faculty.create');
        Route::post('/faculty', [AdminController::class, 'storeFaculty'])->name('faculty.store');
        Route::get('/faculty/{id}/edit', [AdminController::class, 'editFaculty'])->name('faculty.edit');
        Route::put('/faculty/{id}', [AdminController::class, 'updateFaculty'])->name('faculty.update');

        // Faculty Assignments
        Route::get('/assignments', [AdminController::class, 'assignFaculty'])->name('assignments.index');
        Route::post('/assignments', [AdminController::class, 'storeFacultyAssignment'])->name('assignments.store');
        Route::delete('/assignments/{id}', [AdminController::class, 'deleteFacultyAssignment'])->name('assignments.delete');
        Route::get('/assignments/faculty/{id}', [AdminController::class, 'facultyClasses'])->name('assignments.facultyClasses');

        // Subject Management
        Route::get('/subjects', [AdminController::class, 'listSubjects'])->name('subjects.index');
        Route::get('/subjects/create', [AdminController::class, 'createSubject'])->name('subjects.create');
        Route::post('/subjects', [AdminController::class, 'storeSubject'])->name('subjects.store');
        Route::get('/subjects/{id}/edit', [AdminController::class, 'editSubject'])->name('subjects.edit');
        Route::put('/subjects/{id}', [AdminController::class, 'updateSubject'])->name('subjects.update');

        // Show/Add Students to a Section
        Route::get('/sections/{sectionId}/students', [AdminController::class, 'showSectionStudents'])->name('sections.showStudents');
        Route::post('/sections/{sectionId}/students', [AdminController::class, 'storeSectionStudents'])->name('sections.storeStudents');
        Route::get('/assignments/section/{sectionId}/enrolled-students', [AdminController::class, 'showEnrolledStudents'])->name('assignments.showEnrolledStudents');

        // Grading System
        Route::get('/grading/{subjectId}/edit', [AdminController::class, 'editGradingSystem'])
            ->name('editGradingSystem');

        // Handle form submission (PUT)
        Route::put('/grading/{subjectId}', [AdminController::class, 'updateGradingSystem'])
            ->name('updateGradingSystem');

        // Syllabus Upload Times
        Route::get('/syllabi', [AdminController::class, 'viewTeacherSyllabi'])->name('syllabi.index');
    });

/*
|--------------------------------------------------------------------------
| Faculty Logic
|--------------------------------------------------------------------------
*/
Route::prefix('faculty')
    ->middleware(['auth','faculty'])
    ->name('faculty.')
    ->group(function() {
        Route::get('/dashboard', [FacultyController::class, 'index'])->name('dashboard');

        // Class Management
        Route::get('/classes', [FacultyController::class, 'myClasses'])->name('classes.index');
        Route::get('/classes/{sectionId}/{subjectId}/{schoolYear}/{semester}', [FacultyController::class, 'classDetails'])->name('classes.details');

        // Syllabus
        Route::get('/syllabi', [FacultyController::class, 'listSyllabi'])->name('syllabus.index');
        Route::get('/syllabus/{sectionId}/{subjectId}/{schoolYear}/{semester}/upload',
            [FacultyController::class, 'uploadSyllabus'])
            ->name('syllabus.upload');
        // Process the syllabus file upload
        Route::post('/syllabus/{sectionId}/{subjectId}/{schoolYear}/{semester}',
            [FacultyController::class, 'storeSyllabus'])
            ->name('syllabus.store');
            Route::get('/syllabus/{id}/download', [FacultyController::class, 'downloadSyllabus'])->name('syllabus.download');

            Route::get('/seatplan/{sectionId}/{subjectId}/{schoolYear}/{semester}/create', [FacultyController::class, 'createSeatPlan'])->name('seatplan.create');
            Route::post('/seatplan/{sectionId}/{subjectId}/{schoolYear}/{semester}', [FacultyController::class, 'storeSeatPlan'])->name('seatplan.store');
            Route::get('/seatplan/{sectionId}/{subjectId}/{schoolYear}/{semester}/view', [FacultyController::class, 'viewSeatPlan'])->name('seatplan.view');
        // Assessment
        Route::get('/assessment/{sectionId}/{subjectId}/{schoolYear}/{semester}/create', [FacultyController::class, 'createAssessment'])->name('assessment.create');
        Route::post('/assessment/{sectionId}/{subjectId}/{schoolYear}/{semester}', [FacultyController::class, 'storeAssessment'])->name('assessment.store');

        // Scores
        Route::get('/scores/{assessmentId}', [FacultyController::class, 'manageScores'])->name('scores.manage');
        Route::post('/scores/{assessmentId}', [FacultyController::class, 'saveScores'])->name('scores.save');

        // Analytics
        Route::get('/analytics/{sectionId}/{subjectId}/{schoolYear}/{semester}', [FacultyController::class, 'analytics'])->name('analytics');

        Route::get('/reports/export-excel/{id}', [FacultyController::class, 'exportExcel'])->name('reports.export-excel');
        Route::get('/reports/export-pdf/{id}', [FacultyController::class, 'exportPdf'])->name('reports.export-pdf');

        // Reports - fixed routes with consistent naming
        Route::get('/reports/generate/{sectionId}/{subjectId}/{schoolYear}/{semester}', [FacultyController::class, 'generateReport'])->name('reports.generate');
        Route::post('/reports/download/{sectionId}/{subjectId}/{schoolYear}/{semester}', [FacultyController::class, 'downloadReport'])->name('reports.download');
        Route::get('/reports/view/{id}', [FacultyController::class, 'viewReport'])->name('reports.view');
    });
/*
|--------------------------------------------------------------------------
| Client/Student Logic
|--------------------------------------------------------------------------
*/
Route::prefix('student')
    ->middleware(['auth','client'])
    ->name('client.')
    ->group(function() {
        // Dashboard
        Route::get('/dashboard', [ClientController::class, 'index'])->name('dashboard');

        // Classes
        Route::get('/classes', [ClientController::class, 'myClasses'])->name('classes.index');
        Route::get('/classes/{sectionId}/{subjectId}/{schoolYear}/{semester}', [ClientController::class, 'classDetails'])->name('classes.details');

        // Schedules
        Route::get('/schedules', [ClientController::class, 'viewSchedules'])->name('schedules.index');

        // Grades
        Route::get('/grades', [ClientController::class, 'viewGrades'])->name('grades.index');

        // Messages/Chat
        Route::get('/messages', [ClientController::class, 'viewMessages'])->name('messages.index');
        Route::get('/messages/{userId}', [ClientController::class, 'getConversation'])->name('messages.conversation');
        Route::post('/messages', [ClientController::class, 'sendMessage'])->name('messages.send');

        // Syllabus download
        Route::get('/syllabus/{id}/download', [ClientController::class, 'downloadSyllabus'])->name('syllabus.download');
    });
