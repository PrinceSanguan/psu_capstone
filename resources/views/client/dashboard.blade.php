@extends('layouts.client')

@section('title', 'Student Dashboard')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

    <!-- Dashboard Cards -->
    <div class="row">
        <!-- Total Subjects Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Subjects</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalSubjects }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Sections Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Sections</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalSections }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Unread Messages</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $unreadMessages }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Schedule Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Upcoming Assessments</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $upcomingAssessments->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Enrolled Classes -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">My Classes</h6>
                </div>
                <div class="card-body">
                    @if($enrollments->isEmpty())
                        <p class="text-center">You are not enrolled in any classes.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th>Section</th>
                                        <th>Teacher</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($enrollments->take(5) as $enrollment)
                                        <tr>
                                            <td>{{ $enrollment->subject_code }} - {{ $enrollment->subject_name }}</td>
                                            <td>{{ $enrollment->section_name }}</td>
                                            <td>{{ $enrollment->faculty_name }}</td>
                                            <td>
                                                <a href="{{ route('client.classes.details', [
                                                    'sectionId' => $enrollment->section_id,
                                                    'subjectId' => $enrollment->subject_id,
                                                    'schoolYear' => $enrollment->school_year,
                                                    'semester' => $enrollment->semester
                                                ]) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($enrollments->count() > 5)
                            <div class="text-center mt-3">
                                <a href="{{ route('client.classes.index') }}" class="btn btn-link">View All Classes</a>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Upcoming Assessments -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Upcoming Assessments</h6>
                </div>
                <div class="card-body">
                    @if($upcomingAssessments->isEmpty())
                        <p class="text-center">No upcoming assessments scheduled.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingAssessments->take(5) as $assessment)
                                        <tr>
                                            <td>{{ $assessment->subject_code }}</td>
                                            <td>{{ $assessment->title }}</td>
                                            <td>{{ ucfirst(str_replace('_', ' ', $assessment->type)) }}</td>
                                            <td>
                                                {{ date('M d, Y', strtotime($assessment->schedule_date)) }}
                                                @if($assessment->schedule_time)
                                                    <br>{{ date('h:i A', strtotime($assessment->schedule_time)) }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($upcomingAssessments->count() > 5)
                            <div class="text-center mt-3">
                                <a href="{{ route('client.schedules.index') }}" class="btn btn-link">View All Schedules</a>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Scores -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Scores</h6>
                    <a href="{{ route('client.grades.index') }}" class="btn btn-sm btn-primary">View All Grades</a>
                </div>
                <div class="card-body">
                    @if($recentScores->isEmpty())
                        <p class="text-center">No recent scores available.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th>Assessment</th>
                                        <th>Type</th>
                                        <th>Score</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentScores as $score)
                                        <tr>
                                            <td>{{ $score->subject_code }}</td>
                                            <td>{{ $score->assessment_title }}</td>
                                            <td>{{ ucfirst(str_replace('_', ' ', $score->assessment_type)) }}</td>
                                            <td>
                                                {{ $score->score }} / {{ $score->max_score }}
                                                ({{ round(($score->score / $score->max_score) * 100, 2) }}%)
                                            </td>
                                            <td>{{ date('M d, Y', strtotime($score->created_at)) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
