@extends('layouts.client')

@section('title', 'Assessment Schedules')

@section('styles')
<style>
    .calendar-container {
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .calendar-header {
        background-color: #4e73df;
        color: #fff;
        padding: 15px;
        text-align: center;
    }

    .calendar-header h2 {
        margin: 0;
        font-size: 1.5rem;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        padding: 10px;
    }

    .calendar-day {
        padding: 10px;
        min-height: 100px;
        border: 1px solid #e3e6f0;
        position: relative;
    }

    .day-header {
        font-weight: bold;
        text-align: center;
        padding: 5px;
        background-color: #f8f9fc;
    }

    .day-number {
        position: absolute;
        top: 5px;
        right: 5px;
        font-size: 0.8rem;
        color: #858796;
    }

    .calendar-event {
        background-color: #4e73df;
        color: white;
        padding: 3px 5px;
        border-radius: 3px;
        margin-bottom: 5px;
        font-size: 0.8rem;
        cursor: pointer;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .calendar-event.quiz {
        background-color: #1cc88a;
    }

    .calendar-event.unit_test {
        background-color: #f6c23e;
    }

    .calendar-event.activity {
        background-color: #36b9cc;
    }

    .calendar-event.midterm_exam, .calendar-event.final_exam {
        background-color: #e74a3b;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Assessment Schedules</h1>

    <!-- List View -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Upcoming Assessments</h6>
        </div>
        <div class="card-body">
            @if($upcomingAssessments->isEmpty())
                <p class="text-center">No assessments scheduled at this time.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Term</th>
                                <th>Teacher</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($upcomingAssessments as $assessment)
                                <tr>
                                    <td>{{ $assessment->subject_code }} - {{ $assessment->subject_name }}</td>
                                    <td>{{ $assessment->title }}</td>
                                    <td>
                                        <span class="badge
                                            @if($assessment->type == 'quiz') badge-success
                                            @elseif($assessment->type == 'unit_test') badge-warning
                                            @elseif($assessment->type == 'activity') badge-info
                                            @else badge-danger
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $assessment->type)) }}
                                        </span>
                                    </td>
                                    <td>{{ date('M d, Y', strtotime($assessment->schedule_date)) }}</td>
                                    <td>
                                        @if($assessment->schedule_time)
                                            {{ date('h:i A', strtotime($assessment->schedule_time)) }}
                                        @else
                                            Not specified
                                        @endif
                                    </td>
                                    <td>{{ ucfirst($assessment->term) }}</td>
                                    <td>{{ $assessment->faculty_name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Calendar View -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Calendar View</h6>
        </div>
        <div class="card-body">
            @php
                // Get current month and year
                $currentMonth = date('n');
                $currentYear = date('Y');

                // Month name
                $monthName = date('F Y');

                // Get number of days in month
                $daysInMonth = date('t');

                // Get first day of month (0 = Sunday, 6 = Saturday)
                $firstDayOfMonth = date('w', strtotime(date('Y-m-01')));
            @endphp

            <div class="calendar-container">
                <div class="calendar-header">
                    <h2>{{ $monthName }}</h2>
                </div>

                <div class="calendar-grid">
                    <!-- Day headers -->
                    <div class="day-header">Sun</div>
                    <div class="day-header">Mon</div>
                    <div class="day-header">Tue</div>
                    <div class="day-header">Wed</div>
                    <div class="day-header">Thu</div>
                    <div class="day-header">Fri</div>
                    <div class="day-header">Sat</div>

                    <!-- Empty cells for days before the 1st of the month -->
                    @for($i = 0; $i < $firstDayOfMonth; $i++)
                        <div class="calendar-day"></div>
                    @endfor

                    <!-- Days of the month -->
                    @for($day = 1; $day <= $daysInMonth; $day++)
                        @php
                            $date = date('Y-m-d', strtotime("$currentYear-$currentMonth-$day"));
                            $dayEvents = $upcomingAssessments->filter(function($assessment) use ($date) {
                                return date('Y-m-d', strtotime($assessment->schedule_date)) == $date;
                            });
                        @endphp

                        <div class="calendar-day">
                            <div class="day-number">{{ $day }}</div>

                            @foreach($dayEvents as $event)
                                <div class="calendar-event {{ $event->type }}" title="{{ $event->subject_code }} - {{ $event->title }}">
                                    {{ $event->title }}
                                </div>
                            @endforeach
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Calendar Legend -->
            <div class="mt-4">
                <h6 class="font-weight-bold">Legend:</h6>
                <div class="d-flex flex-wrap">
                    <div class="mr-3 mb-2">
                        <span class="badge badge-success">Quiz</span>
                    </div>
                    <div class="mr-3 mb-2">
                        <span class="badge badge-warning">Unit Test</span>
                    </div>
                    <div class="mr-3 mb-2">
                        <span class="badge badge-info">Activity</span>
                    </div>
                    <div class="mr-3 mb-2">
                        <span class="badge badge-danger">Exam (Midterm/Final)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#dataTable').DataTable({
            "order": [[3, "asc"]]  // Sort by date by default
        });
    });
</script>
@endsection
